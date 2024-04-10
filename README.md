# fp_newsletter

Version 7.0.1

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
- New setting: newsletterExtension (mail or luxletter).

Version 6.3.0:
- New action: direct unsubscribe via Mail-link and new setting: authCodeFields.

Version 6.3.1:
- Bugfix: Luxletter is now the default newsletter-extension - to prevent a PHP warning.

Version 6.3.2:
- Bugfix: reCAPTCHA fixed.

Version 6.4.0:
- Additional fields can now be copied from the log-entry to the tt_address-table. Setting additionalTtAddressFields added.

Version 6.4.3:
- Small bugfixes.

Version 7.0.1:
- Refactoring with the rector tool.
- Adding of the language code to f:translate in the email-templates.
- TypoScript-files have now the ending .typoscript.
- setting dontAppendL is now deprecated.
- Bugfix: search in all folders now for mail-unsubscription and edit too.