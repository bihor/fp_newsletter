# fp_newsletter

version 2.1.2

The TYPO3 extension fp_newsletter is designed to provide a newsletter subscription and unsubscription service for the table tt_address which can be used
by the extension direct_mail. Furthermore it is designed to be compatible with the GDPR. A log is written about every action in a separate table.
Old log entries can be deleted by a scheduler task.
Supports Google reCaptcha v3 or a mathematical captcha.
Available languages: english, german/deutsch and italian/italiano.

You find the documentation the the folder "Documentation" / at typo3.org:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/master/en-us/

Es gibt auch eine deutsche Anleitung/Dokumentation zu dieser Erweiterung:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/master/de-de/

Version 2.1.1: more variables/translate keys for emails added. See chapter Administration.

Version 2.1.2:

Security fix: mathematical captcha check enhanced (it was possible to cheat).

Security fix: settings.doubleOptOut set from 0 to 1. You can set it to 0 if you don´t want a double opt out subscription.

Security fix: additional check added to the delete-action (it was possible to unsubscribe all users).

Security fix: Information Disclosure in the  new- and unsubscribe-action.