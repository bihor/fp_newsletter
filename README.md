# fp_newsletter

version 6.2.1

The TYPO3 extension fp_newsletter is designed to provide a newsletter subscription and unsubscription service for the 
table tt_address which can be used by the extension mail OR for the table fe_users which can be used by luxletter or mail. 
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
- First release for TYPO3 12.
- Breaking: the email-templates and -partials must be changed! Add extensionName="FpNewsletter" to every f:translate.
- Breaking: in template-forms the argument pluginName must be specified! You find them in the templates of this extension.
- Breaking: all plugins must be changed via an update-script (in the install-tool)!

Version 5.1:
- Custom validator added.
- Bugfix for TYPO3 12.

Version 5.2:
- Automatically set the correct PluginName in templates.
- PHP-Bugfix.

Version 6.0:
- Breaking: support for direct_mail removed. Instead, support for the extension "mail" added.
- Breaking: TypoScript settings dmUnsubscribeMode, module_sys_dmail_html, module_sys_dmail_category renamed to
  unsubscribeMode, html, categoryOrGroup. See chapter Administrator / Updating to version 6.x.

Version 6.1.0:
- Compatibility to TYPO3 11.5 added again.
- Bugfix: language added to the links in the email.

Version 6.2.0:
- fe_users can now be used with the extension luxletter or mail.
- deleteMode 3 added: delete only categories (Mail) or user-group (Luxletter).
- new setting: newsletterExtension (mail or luxletter).