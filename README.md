# fp_newsletter

version 4.2.0

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


Version 3.2.6:
- PHP email validation added.
- Security fix: default password for fe_users set to a random password.
- Security fix: mathematical captcha check enhanced (it was possible to cheat).
- Security fix: settings.doubleOptOut set from 0 to 1. You can set it to 0 if you don´t want a double opt out subscription.
- Security fix: additional check added to the delete-action (it was possible to unsubscribe all users).
- Security fix: Information Disclosure in the  new- and unsubscribe-action.


Version 4.0.0/1:
- Breaking: default TypoScript values for sys_language_uid 1 removed!
- New actions: unsubscribe from Luxletter and cacheable form for subscription.
- New task: import newsletter-subscribers from tt_address to fe_users.
- Setting preferXlfFile added. If 1, genders and email subjects will come from the xlf file instead of the settings.

Version 4.0.3:
- Compatibility to direct_mail 11 added.
- Bugfix: PHP 8 and delete-action.

Version 4.1.0:
- Edit/update-action added.
- New TypoScript settings: enableEditForm, editUid, categoryMode, categoryOrderBy and editSubject.

Version 4.1.1:
- Bugfix for translated text when using preferXlfFile=1.

Version 4.2.0:
- Custom validator added.