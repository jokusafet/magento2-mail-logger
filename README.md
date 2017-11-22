# magento2-mail-logger

Module will log either "all" or just "exception triggering" emails that utilize Magento's `Magento\Framework\Mail\Transport` `sendMessage()` method. Module logs both "email body content" (.html) info and "overall debug info" info (.log) into to a `var/log/foggyline_mail_logger/` folder. Mind you, logging full email information to a file system imposes a potential security risk; whereas public exposure of `var/log/foggyline_mail_logger/` folder will lead to exposure of sensitive customer information. Therefore, use this functionality at your own risk - preferably only in development environments.

Once installed, module adds configuration options under `Stores > Settings > Configuration > Advanced > System > Mail Logging Settings` admin section.

Generated log files have a name pattern that matches store code and timestamp such as:
* `var/log/foggyline_mail_logger/default/22112017/170601_5a15ae796a009.html`
* `var/log/foggyline_mail_logger/default/22112017/170601_5a15ae796a009.log`

The `*.html` files can easily be opened in browser to reveal exact content of email, whereas the `*.log` files give detailed info such as: from, to, headers, etc.

Tested on Magento Open Source (formerly Community Edition) ver. 2.1.9.
