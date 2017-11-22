<?php

namespace Foggyline\MailLogger\Helper;

use Magento\Store\Model\ScopeInterface;
use Foggyline\MailLogger\Model\Config\Source\LoggingOptions;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_LOGGING = 'system/foggyline_maillogger/logging';

    public function getLogAllEmails()
    {
        $logging = $this->scopeConfig->getValue(self::XML_PATH_LOGGING, ScopeInterface::SCOPE_STORE);

        if (LoggingOptions::LOGGING_ALL == intval($logging)) {
            return true;
        }

        return false;
    }

    public function getLogExceptionEmails()
    {
        $logging = $this->scopeConfig->getValue(self::XML_PATH_LOGGING, ScopeInterface::SCOPE_STORE);

        if (LoggingOptions::LOGGING_EXCEPTION == intval($logging)) {
            return true;
        }

        return false;
    }
}
