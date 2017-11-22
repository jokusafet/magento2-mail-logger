# magento2-mail-logger

Module will log either "all" or just "exception triggering" emails that utilize Magento's Magento\Framework\Mail\Transport sendMessage() method. Module logs both "email body content" (.html) info and "overall debug info" info (.log) into to a var/log/foggyline_mail_logger/ folder. Mind you, logging full email information to a file system imposes a potential security risk; whereas public exposure of var/log/foggyline_mail_logger/ folder will lead to exposure of sensitive customer information. Therefore, use this functionality at your own risk - preferably only in development environments.

Tested on Magento ver. 2.1.9.
