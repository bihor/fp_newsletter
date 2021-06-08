# fp_newsletter

version 2.0.5

The TYPO3 extension fp_newsletter is designed to provide a newsletter subscription and unsubscription service for the table tt_address which can be used
by the extension direct_mail. Furthermore it is designed to be compatible with the GDPR. A log is written about every action in a separate table.
Old log entries can be deleted by a scheduler task.
Supports Google reCaptcha v3 or a mathematical captcha.
Available languages: english, german/deutsch and italian/italiano.

You find the documentation the the folder "Documentation" / at typo3.org:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/master/en-us/

Es gibt auch eine deutsche Anleitung/Dokumentation zu dieser Erweiterung:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/master/de-de/

New in version 2.0.0:
With the new setting languageMode you can define the language of the entries.
There is now a new behavior when L>0. Furthermore the setting email.dontAppendL is new.
Confirmation emails can now be send by enabeling them with the setting email.enableConfirmationMails.
The translate-viewhelper can now be used in the email-templates.
Name and salutation can be used now in the email-templates.
More FlexForms.

Version 2.1.0: setting searchPidMode and disableErrorMsg added.
extension-key added to composer.json.