# fp_newsletter

version 2.5.2

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

Version 2.4.0:
Setting dmUnsubscribeMode added. Flexform for "unsubscribe via link" needs to be saved again.
New feature: request the verification email again.
The extension dashboard is no longer required in TYPO3 11.
French added.
StopActionException on create when no parameter is there.

Version 3.0.0:
Breaking change: default value of email.dontAppendL changed from 0 to 1.
The email-templates without a number as ending uses now translated texts.
If email.dontAppendL=0 even 0 will now be added to the template name.
French emails now possible too.