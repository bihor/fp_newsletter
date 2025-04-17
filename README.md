# fp_newsletter

Version 8.2.3

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


Version 8.0.0:
- First version for TYPO3 13.
- Breaking: setting dontAppendL removed: templates with language-id at the end removed.
- Breaking: setup.ts and constants.ts removed.

Version 8.0.1:
- New Flexform settings: newsletterExtension and table. "other" sets html to -1.
- Second Widget fixed. Backend preview fixed again. Import task fixed for TYPO3 13.

Version 8.0.4:
- Edit-plugin changed: step 1 is now the default one.
- Bugfix: unsubscription with captcha is enabled + deletion from Luxletter.

Version 8.0.5:
- Bugfix: email to admin.

Version 8.1.0:
- More fields/information added to the log-entry.

Version 8.1.1:
- Bugfix: update Log even no mail is sent.

Version 8.1.2:
- Bugfix: Widget fixed.

Version 8.2.0:
- Always save settings.categoryOrGroup in the log-entry.
- Take the categories or groups from the log-entry when settings.categoryOrGroup is empty.

Version 8.2.1/2/3:
- Documentation.
