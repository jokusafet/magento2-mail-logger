<?php

namespace Foggyline\MailLogger\Plugin\Mail\Transport;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Mail\Transport;
use Magento\Framework\Filesystem\DriverPool;
use Foggyline\MailLogger\Helper\Data;

class OnSendMessage
{
    private $filesystem;
    private $storeManager;
    protected $writeFactory;
    protected $directoryList;
    protected $helper;

    /**
     * OnSendMessage constructor.
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     * @param DirectoryList $directoryList
     * @param WriteFactory $writeFactory
     * @param Helper $helper
     */
    public function __construct(
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        DirectoryList $directoryList,
        WriteFactory $writeFactory,
        Data $helper
    )
    {
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->writeFactory = $writeFactory;
        $this->helper = $helper;
    }

    /**
     * Wrap around \Magento\Framework\Mail\Transport -> sendMessage()
     *
     * @param \Magento\Framework\Mail\Transport $subject
     * @param \Closure $proceed
     * @return mixed
     * @throws \Exception
     */
    public function aroundSendMessage(Transport $subject, \Closure $proceed)
    {
        try {
            $result = $proceed();

            // Within this try block we are within successful email sending scenario
            if ($this->helper->getLogAllEmails()) {
                $this->logSendMessageActivity($subject);
            }

            return $result;
        } catch (\Exception $e) {

            // Within this catch block we are within failed email sending scenario
            if ($this->helper->getLogAllEmails() || $this->helper->getLogExceptionEmails()) {
                $this->logSendMessageActivity($subject);
            }

            throw $e;
        }
    }

    /**
     * The actual logging logic implementation
     * @param Transport $subject
     */
    private function logSendMessageActivity(Transport $subject)
    {
        try {
            $reflection = new \ReflectionClass($subject);

            $_message = $reflection->getProperty('_message');
            $_message->setAccessible(true);

            /* @var $message \Magento\Framework\Mail\Message */
            $message = $_message->getValue($subject);

            // Build log/email HTML file name, should yield something like 132100.5a1579bc861f6.html
            $file = sprintf('%s_%s.html',
                date('His'),
                uniqid()
            );

            // Build log/email debug file name, should yield something like 132100.5a1579bc861f6.log
            $file2 = str_replace('.html', '.log', $file);

            // Build absolute directory path, should yield something like /var/www/html/var/log/foggyline_mail_logger/default/22112017
            $directory = sprintf('%s%s%s%s%s%s%s',
                $this->directoryList->getPath(DirectoryList::LOG),
                DIRECTORY_SEPARATOR,
                'foggyline_mail_logger',
                DIRECTORY_SEPARATOR,
                $this->storeManager->getStore()->getCode(),
                DIRECTORY_SEPARATOR,
                date('dmY')
            );

            // Fetch raw email HTML content
            $content = $message->getBody()->getRawContent();

            // Init writer against proper directory
            $writer = $this->writeFactory->create($directory, DriverPool::FILE);

            // Write to the actual log/email HTML file
            $writer->writeFile($file, $content);

            // Write to the actual log/email debug file
            $writer->writeFile($file2, json_encode([
                'headers' => $message->getHeaders(),
                'from' => $message->getFrom(),
                'recipients' => $message->getRecipients(),
                'subject' => $message->getSubject(),
                'reply_to' => $message->getReplyTo(),
                'return_path' => $message->getReturnPath(),
                'charset' => $message->getCharset(),
            ], JSON_PRETTY_PRINT));
        } catch (\Throwable $t) {
            // fail silently
        }
    }
}
