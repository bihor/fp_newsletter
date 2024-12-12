# fp_newsletter

Version 7.1.0

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


Version 7.0.0/1:
- Refactoring with the rector tool.
- Adding of the language code to f:translate in the email-templates.
- TypoScript-files have now the ending .typoscript.
- setting dontAppendL is now deprecated.
- Bugfix: search in all folders now for mail-unsubscription and edit too.

Version 7.0.2/3:
- Bugfix: backend preview.
- Bugfix: Plugin-Updater.

Version 7.0.4:
- Unnecessary sql-fields removed.

Version 7.0.5:
- deleteMode 4 (set hidden/disable flag) added.