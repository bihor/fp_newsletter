# fp_newsletter

version 5.1.1

The TYPO3 extension fp_newsletter is designed to provide a newsletter subscription and unsubscription service for the 
table tt_address which can be used by the extension direct_mail OR for the table fe_users which can be used by luxletter. 
Furthermore, it is designed to be compatible with the GDPR. A log is written about every action in a separate table.
Old log entries can be deleted by a scheduler task.
Supports Google reCaptcha v3 or a mathematical captcha.
And there is a widget for the dashboard available.
Available languages: english, german/deutsch, french/français and italian/italiano.

You find the documentation in the folder "Documentation" / at typo3.org:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/master/en-us/

Es gibt auch eine deutsche Anleitung/Dokumentation zu dieser Erweiterung:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/master/de-de/


Version 5.0:

First release for TYPO3 12.

Breaking: the email-templates and -partials must be changed! Add extensionName="FpNewsletter" to every f:translate.

Breaking: in template-forms the argument pluginName must be specified! You find them in the templates of this extension.

Breaking: all plugins must be changed via an update-script (in the install-tool)!


Version 5.1.1:

Custom validator added.

Bugfix for TYPO3 12.