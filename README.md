# fp_newsletter

version 3.1.3

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

Version 3.0.0:
Breaking change: default value of email.dontAppendL changed from 0 to 1.
The email-templates without a number as ending uses now translated texts.
If email.dontAppendL=0 even 0 will now be added to the template name.
French emails now possible too.
Bugfix: form replaced with a normal button in the emails.

Version 3.1.0:
Salutation in emails moved to a partial. Gender divers will now be ignored in the emails.
The name is now available in the email to the admin on unsubscription.
A second dashboard widget added: status diagram.
Bugfix: retoken column was too small.

Version 3.2.0:
module_sys_dmail_category now in FlexForms too.
Important: Layout optimized for Bootstrap 4.
IDs in unsubscribe form changed.