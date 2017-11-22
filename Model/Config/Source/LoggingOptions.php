<?php

namespace Foggyline\MailLogger\Model\Config\Source;

class LoggingOptions implements \Magento\Framework\Option\ArrayInterface
{
    const LOGGING_DISABLED = 0;
    const LOGGING_ALL = 1;
    const LOGGING_EXCEPTION = 2;
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::LOGGING_DISABLED, 'label' => __('Disabled')],
            ['value' => self::LOGGING_ALL, 'label' => __('Log all emails')],
            ['value' => self::LOGGING_EXCEPTION, 'label' => __('Log only "exception triggering" emails')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::LOGGING_DISABLED => __('Disabled'),
            self::LOGGING_ALL => __('Log all emails'),
            self::LOGGING_EXCEPTION => __('Log only "exception triggering" emails'),
        ];
    }
}
