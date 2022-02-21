# fp_newsletter

version 2.3.2

The TYPO3 extension fp_newsletter is designed to provide a newsletter subscription and unsubscription service for the 
table tt_address which can be used by the extension direct_mail OR for the table fe_users which can be used by luxletter. 
Furthermore, it is designed to be compatible with the GDPR. A log is written about every action in a separate table.
Old log entries can be deleted by a scheduler task.
Supports Google reCaptcha v3 or a mathematical captcha.
Available languages: english, german/deutsch and italian/italiano.

You find the documentation in the folder "Documentation" / at typo3.org:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/master/en-us/

Es gibt auch eine deutsche Anleitung/Dokumentation zu dieser Erweiterung:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/master/de-de/

Version 2.3.2: a widget for the dashboard added. The extension dashboard is required in TYPO3 11.
Setting checkForRequiredExtensions added (does not work for dashboard in TYPO3 11).
Supports now the fe_users table too.
Form with button added to the verification emails.
no-cache paramater removed.