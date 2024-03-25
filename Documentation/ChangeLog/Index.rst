.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

Version 0.10.0: Important change: plugin.tx_fpnewsletter_pi1 renamed to plugin.tx_fpnewsletter, because otherwise empty TS-values overwrite given FlexForm-values.
New action: subscribeExt for newsletter subscription via other extensions. Bugfix: partial-path.

Version 0.10.2: Links in the email-templates changed.
Bugfix: text-email was missing.

Version 0.11.0: Links in the email-templates works now with TYPO3 8 too.
Empty FlexForms will now be overwritten by TypoScript.

Version 0.12.0: now double opt out possible. More FlexForms.

Version 0.13.0: italian translation added.
First version for TYPO3 9 (runs only if typo3db_legacy is installed).

Version 0.14.0: composer-file added.
Email to an admin now possible.
One bug fixed: email-check.

Version 0.15.0: gender divers added.
Switch to the QueryBuilder.
reCaptcha v3 implemented (optional).

Version 0.16.0: f:format.raw added to text-links.
Setting module_sys_dmail_category added.
Address-object in verify-actions now available.
TS optionalFieldsRequired added. required-attribute added.

Version 0.17.0: new TypoScript setting: email.adminMailBeforeVerification
Email to admin now before or after verification. Default status changed!
Email to admin only in one language.
Very last $GLOBALS['TYPO3_DB'] replaced.

Version 0.18.0: optional mathematical captcha added.
Set sys_language_uid=-1 if l>0.
The categories are stored now in the log-entry too.
More optional fields: address, zip, city, region, country, phone, mobile, fax, www, position, company.

Version 1.0.0: possibility added, to delete old log-entries via a task.
Important change: redirect to the new- or unsubscribe-action on email-format- or captcha-errors.
Bugfix: you can use now reCAPTCHA and mathCAPTCHA together.

Version 1.0.4: Bugfix: subscription via external form.

Version 1.1.0: possibility added, to activate a honeypot.
Bugfix: prevent error on unsubscribe when a captcha is enabled.

Version 1.2.0: deprecated methods replaced.

Version 2.0.0: with the new setting languageMode you can define the language of the entries.
There is now a new behavior when L>0. Furthermore the setting email.dontAppendL is new.
Confirmation emails can now be send by enabling them with the setting email.enableConfirmationMails.
The translate-viewhelper can now be used in the email-templates.
Name and salutation can be used now in the email-templates.
More FlexForms.

Version 2.1.0: setting searchPidMode and disableErrorMsg added.
extension-key added to composer.json.

Version 2.2.1: more variables/translate keys for emails added. See chapter Administration.
Now for TYPO3 10 and 11.

Version 2.3.2: a widget for the dashboard added. The extension dashboard is required in TYPO3 11.
Setting checkForRequiredExtensions added (does not work for dashboard in TYPO3 11).
The table fe_users can now be used too.
Form with button added to the verification emails.
no-cache parameter removed.

Version 2.4.0: Setting dmUnsubscribeMode added. Flexform for "unsubscribe via link" needs to be saved again.
The extension dashboard is no longer required in TYPO3 11.
New action: resend verification email.
French added (thanks to lucmuller).
StopActionException on create when no parameter is there.

Version 3.0.0: breaking change: default value of email.dontAppendL changed from 0 to 1.
The email-templates without a number as ending uses now translated texts.
If email.dontAppendL=0 even 0 will now be added to the template name.
French emails now possible (thanks to lucmuller).
Bugfix: form replaced with a normal button in the emails.

Version 3.1.0: salutation in emails moved to a partial. Gender divers will now be ignored in the salutation in emails.
The name is now available in the email to the admin on unsubscription.
A second dashboard widget added: status diagram.
Bugfix: retoken column was too small.

Version 3.2.0: module_sys_dmail_category now in FlexForms too.
Important: Layout optimized for Bootstrap 4.
IDs in unsubscribe form changed.
Backend: preview added.

Version 3.2.5:
- Switch from cURL to RequestFactory.
- Bugfix: no categories added in tt_address.

Version 3.2.6:
- PHP email validation added.
- Security fix: default password for fe_users set to a random password.
- Security fix: mathematical captcha check enhanced (it was possible to cheat).
- Security fix: settings.doubleOptOut set from 0 to 1. You can set it to 0 if you don´t want a double opt out subscription.
- Security fix: additional check added to the delete-action (it was possible to unsubscribe all users).
- Security fix: Information Disclosure in the new- and unsubscribe-action.

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

Version 5.0:
- Refactored for TYPO3 12 LTS.
- Breaking: the email-templates and -partials must be changed! Add extensionName="FpNewsletter" to every f:translate.
- Breaking: in template-forms the argument pluginName must be specified! You find them in the templates of this extension.
- Breaking: all plugins must be changed via an update-script (in the install-tool)!

Version 5.1:
- Custom validator added which can be used for other captcha solutions.
- Bugfix for TYPO3 12.

Version 5.2:
- Automatically set the correct PluginName in templates.
- PHP-Bugfix.

Version 6.0.0:
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

Version 7.0.0:
- Refactoring with the rector tool.
- Adding of the language code to the email text.
- TypoScript-files have now the ending .typoscript.
- setting dontAppendL is now deprecated.