.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration/TypoScript Reference
==================================

Here you find all configuration (settings) possibilities.
Configuration via TypoScript (and FlexForms).

***Important***: for fp_newsletter-versions < 6 you should look here:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/5.2/en-us/Configuration/Index.html


Properties for settings
^^^^^^^^^^^^^^^^^^^^^^^

.. container:: ts-properties

================================= =========== ===================================================================== =================================
Property                          Data type   Description                                                           Default
================================= =========== ===================================================================== =================================
table                             string      tt_address, fe_users or none (empty value) supported                  tt_address
newsletterExtension               string      luxletter (default for fe_users) or mail (default for tt_address)     empty = luxletter
optionalFields                    string      Optional fields: see below                                            gender,firstname,lastname
optionalFieldsRequired            string      Optional required* fields: see below
doubleOptOut                      boolean     Enable double out out unsubscription?                                 1
disableErrorMsg                   boolean     Disable some error messages (e.g. already/not subscribed)?            0
enableUnsubscribeForm             boolean     Enable unsubscribe form at the subscribe page?**                      0
enableUnsubscribeGdprAsHidden     boolean     Do not show the gdpr-checkbox at unsubscribe form?                    0
enableEditForm                    boolean     Enable edit form at the subscribe page?**                             0
subscribeUid                      integer     Page for the subscription                                             1
subscribeMessageUid               integer     Optional page for the redirect after subscription
subscribeVerifyUid                integer     Page for the subscription-verification
subscribeVerifyMessageUid         integer     Optional page for the redirect after subscription-verification
unsubscribeUid                    integer     Page for the unsubscription                                           1
unsubscribeMessageUid             integer     Optional page for the redirect after unsubscription
unsubscribeVerifyUid              integer     Page for the unsubscription-verification
unsubscribeVerifyMessageUid       integer     Optional page for the redirect after unsubscription-verification***
resendVerificationUid             integer     Page, where a user can request the verification-email again
editUid                           integer     Page, where a user can edit his subscription
gdprUid                           integer     Page with the GDPR text                                               1
daysExpire                        integer     The link expires after X days                                         2
searchPidMode                     integer     Search in tt_address: 0: only in the 1. folder; 1: in all folders°    0
deleteMode                        integer     1: set deletion flag; 2: delete entry; 3: remove only the cat./group  1
languageMode                      integer     0: uses -1 if L>0; 1: uses the sys_language_uid from pages            0
categoryMode                      integer     0: allow only categories/groups specified in categoryOrGroup; 1: all  1
categoryOrderBy                   string      category order by: title, sorting or uid                              title
unsubscribeMode                   integer     0: direct unsubscription with a link from Luxletter; 1: show a form   0
categoryOrGroup                   string      List of categories/groups (uid) from sys_category or fe_groups°°
html                              integer     0: only TEXT; 1: TEXT and HTML; -1: ignore mail-fields in tt_address  1
password                          string      Password for the fe_users table. random creates a random password.    random
authCodeFields                    string      Copy "Fields ... of authentication codes" from mail here
reCAPTCHA_site_key                string      Website-key for Google reCaptcha v3.
reCAPTCHA_secret_key              string      Secret key for Google reCaptcha v3
mathCAPTCHA                       integer     Show a mathematical captcha? 0: no; 1: with 1 digit; 2: with 2 digits 0
honeypot                          boolean     Enable a honeypot against spam?                                       0
debug                             boolean     Don´t send email when debug=1                                         0
checkForRequiredExtensions        boolean     Check, if required extensions are installed. 0: no; 1: yes.           1
company                           string      Name of your company                                                  Ihre Firma
gender.please                     string      Text for gender selection                                             Bitte auswählen
gender.mr                         string      Text for the gender mr                                                Herr
gender.mrs                        string      Text for the gender mrs                                               Frau
parameters.active                 string      Parameter for newsletter subscription in external extension (POST)
parameters.email                  string      Parameter for the email from external source (GET/POST-parameter)
email.senderMail                  string      Your email-address                                                    beispiel@test.de
email.senderName                  string      Your name                                                             Absender-Name
email.subscribeVerifySubject      string      Subject of the verify email (subscription)                            Bitte verifizieren ...
email.unsubscribeVerifySubject    string      Subject of the verify email (unsubscription)                          Bitte verifizieren ...
email.adminMail                   string      Admin email-address - if not empty: an email goes to an admin too
email.adminName                   string      Admin name                                                            Admin
email.adminSubscribeSubject       string      Subject of the admin email (subscription)                             Neue Newsletter-Anmeldung
email.adminUnsubscribeSubject     string      Subject of the admin email (unsubscription)                           Neue Newsletter-Abmeldung
email.adminMailBeforeVerification boolean     0: send email to admin after verification; 1: before verification     0
email.subscribedSubject           string      Subject of the confirmation email (subscription)                      Bestätigung Newsletter-Anmeldung
email.unsubscribedSubject         string      Subject of the confirmation email (unsubscription)                    Bestätigung Newsletter-Abmeldung
email.editSubject                 string      Subject to the edit email                                             Ändern Sie Ihr Newsletter-Abo...
email.enableConfirmationMails     boolean     Send confirmation email to the user after verification? 0: no; 1: yes 0
email.dontAppendL                 boolean     Append the language UID to a template (when L>0)? 0: yes; 1: no°°°    1
overrideFlexformSettingsIfEmpty   string      Empty FlexForms should be overwritten by TypoScript                   all uid settings
================================= =========== ===================================================================== =================================

Note*: only a check via browser is made for the optional required fields.

Note**: you need an own page for the unsubscription/edit! unsubscribeUid/editUid should be defined therefore.

Note***: this page is used too, if doubleOptOut=0. unsubscribeMessageUid is not used if doubleOptOut=0.

Note°: this works only at the unsubscription.

Note°°: comma separated list. E.g. 1,3. Without space. Required!

Note°°°: the default value was changed from 0 to 1 in version 3.0.0 and even when L=0 0 will be added from version 3.0.0
to the email-templates when email.dontAppendL=0.


Property details / examples
---------------------------

Languages
^^^^^^^^^

You can overwrite the text for other languages like this (if preferXlfFile=0, else see last line)::

  [siteLanguage("languageId") == "1"]
  plugin.tx_fpnewsletter.settings.company = Your company
  plugin.tx_fpnewsletter.settings.gender.please = Please select your gender
  plugin.tx_fpnewsletter.settings.gender.mr = Mr.
  plugin.tx_fpnewsletter.settings.gender.mrs = Mrs.
  plugin.tx_fpnewsletter.settings.email.senderMail = example@test.com
  plugin.tx_fpnewsletter.settings.email.senderName = Sender-name
  plugin.tx_fpnewsletter.settings.email.subscribeVerifySubject = Please verify your email
  plugin.tx_fpnewsletter.settings.email.unsubscribeVerifySubject = Please verify your email
  plugin.tx_fpnewsletter.settings.email.adminSubscribeSubject = New newsletter-subscription
  plugin.tx_fpnewsletter.settings.email.adminUnsubscribeSubject = New newsletter-unsubscription
  plugin.tx_fpnewsletter.settings.email.subscribedSubject = Newsletter-subscription confirmation
  plugin.tx_fpnewsletter.settings.email.unsubscribedSubject = Newsletter-unsubscription confirmation
  [END]
  plugin.tx_fpnewsletter._LOCAL_LANG.default.email.pleaseVerify = Please verify your email-address by clicking here:

Note: if you want to overwrite the text for the default language, remove this lines: [siteLanguage("languageId") == "1"]
and [END].

If you enable the setting preferXlfFile, then you can overwrite the text (subject and salutation) like this::

  plugin.tx_fpnewsletter._LOCAL_LANG.en.email.subscribedSubject = Your newsletter subscription is now confirmed
  plugin.tx_fpnewsletter._LOCAL_LANG.de.email.subscribedSubject = Deine Newsletter-Anmeldung ist nun bestätigt

Note: the default language of the email-templates is german if settings.email.dontAppendL=0!
You find the english version in the files that end with 1.html.
You should copy the files and modify the path to the templates via TypoScript. See chapter "Administrator manual".
Otherwise set settings.email.dontAppendL=1.
Note: till version 3.0.0, the default language is german even when settings.email.dontAppendL=1.
From version 3.0.0, the email-templates without a appended number are using translated texts by default.

*Deprecation-note*: dontAppendL will be removed in version 8.0.0. Even all the templates with the ending 0 and 1.

External fields
^^^^^^^^^^^^^^^

You can set a default email-address which was submitted before. E.g. you have a form in the footer and the field-name is nlemail, then set the parameter like this::

  plugin.tx_fpnewsletter.settings.parameters.email = nlemail

That parameter will be read and the value of that parameter will be used as default email-address.


You can do the subscription via an form in an other extension too. E.g. you have an shop and at the end of the order the user wants to subscribe to the newsletter?
Then put this extension under the shop extension an select the action "subscribe via external extension".
Furthermore you must specify the POST-parameter, which are used in the other extension like this::

  plugin.tx_fpnewsletter.settings.parameters.active = tx_myshop_pi1|newOrder|newsletter
  plugin.tx_fpnewsletter.settings.parameters.email = tx_myshop_pi1|newOrder|email

Only parameters of this format are possible. If they are there, a forward will be made to the action create.

Note:

If you have set the setting "[FE][cacheHash][enforceValidation] = 1", then you must add the parameter from above here:
"[FE][cacheHash][excludedParameters]".

Captchas
^^^^^^^^

You can use 3 different captcha methods.
If you want to use the Google reCaptcha v3 you need to provide the website key and the secret key.
If you want to use a mathematical captcha, you can use 1 or 2 digits. The maximum value for 2 digits is 19. Example::

  plugin.tx_fpnewsletter.settings.mathCAPTCHA = 2

You can even use an custom captcha like "Friendly Captcha". Read the chapter "Administrator" for more information.

Optional and required fields
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Only email and gdpr are mandatory fields in the model. If you need more mandatory fields, you can make them only via
TypoScript and the templates required.
There are the following optional fields available: gender, title, firstname, lastname, address, zip, city, region, country, phone, mobile, fax, www, position, company.
You can make all this fields required. Here an example to enable some of this fields in the subscription form via
TypoScript setup::

  plugin.tx_fpnewsletter.settings.optionalFields = gender,title,firstname,lastname,www,position,company
  plugin.tx_fpnewsletter.settings.optionalFieldsRequired = firstname,lastname,company
  
Using of categories
^^^^^^^^^^^^^^^^^^^

The table sys_category contains categories for mail. Use them like this::

  plugin.tx_fpnewsletter.settings.categoryOrGroup = 1,3

It will make two entires into sys_category_record_mm and it will set categories in tt_address (after the verification).

The categories are as hidden-field in the template. You could add checkboxes and copy the checked values by jQuery to
the hidden-field if you need a more flexible solution.

Note: this setting is used for groups of fe_users too! If you use fe_users, here you can set the fe_groups.
That works only with the extension Luxletter. If newsletterExtension=mail is set, user groups are ignored.
Therefore only categories are used.

Note: if deleteMode=3 is set, only categories set in categoryOrGroup will be removed. And the flag mail_active will be
set to 0, if newsletterExtension=mail is set.

Changing the labels
^^^^^^^^^^^^^^^^^^^

Like in every extension, you can change the labels and other messages via TypoScript. Here 2 examples::

  plugin.tx_fpnewsletter._LOCAL_LANG.de.tx_fpnewsletter_domain_model_log.email = Email
  plugin.tx_fpnewsletter._LOCAL_LANG.de.tx_fpnewsletter_domain_model_log.gdpr_desc2 = Ich bin damit einverstanden, dass die von mir angegebenen Daten elektronisch erhoben und gespeichert werden.

You find the designations in the templates used in f:translate key.
Note: _LOCAL_LANG.en. does´t work anymore. You need to use .default instead of .en for english text.

Required extensions
^^^^^^^^^^^^^^^^^^^

This extensions checks in the new action (subscription form) if required extensions are installed.
settings.table can be empty, tt_address or fe_users. When it is tt_address, mail is required too, if you use
settings.html or settings.categoryOrGroup. You can disable this check::

  plugin.tx_fpnewslettersettings.checkForRequiredExtensions = 0

Full working example
^^^^^^^^^^^^^^^^^^^^

Here an full example for luxletter and 2 languages::

    plugin.tx_fpnewsletter.view.templateRootPaths.10 = EXT:example/Resources/Private/Ext/fp_newsletter/Templates/
    plugin.tx_fpnewsletter.view.partialRootPaths.10 = EXT:example/Resources/Private/Ext/fp_newsletter/Partials/
    plugin.tx_fpnewsletter.settings {
        table = fe_users
        optionalFields =
        doubleOptOut = 0
        enableUnsubscribeGdprAsHidden = 1
        honeypot = 1
        preferXlfFile = 1
        gdprUid = 1138
        subscribeUid = 1167
        unsubscribeUid = 1002
        subscribeVerifyUid = 1001
        categoryOrGroup = 19
        company = Ihre Online-Redaktion von „Test“
    }
    plugin.tx_fpnewsletter._LOCAL_LANG.de {
        subscribe = Absenden
        tx_fpnewsletter_domain_model_log.email = E-Mail-Adresse
        tx_fpnewsletter_domain_model_log.gdpr_desc1 = Ich habe die
        tx_fpnewsletter_domain_model_log.gdpr_link_text = Datenschutzerklärung
        tx_fpnewsletter_domain_model_log.gdpr_desc2 = zur Kenntnis genommen und bin damit einverstanden, dass meine Daten unter Beachtung der gesetzlichen Bestimmungen satzungsgemäß verwendet und automatisiert verarbeitet werden.
        unsubscribe_it = Newsletter abbestellen
        email_send1 = Vielen Dank für Ihr Interesse.<br>Eine Bestätigungs-E-Mail wurde Ihnen zugesandt.
        email_verified = Ihre E-Mail-Adresse wurde erfolgreich aufgenommen.
        email_removed = Sie haben sich erfolgreich von unserem Newsletter abgemeldet.
        email.pleaseVerify = Sie haben sich für unseren Newsletter angemeldet.
        email.pleaseVerify2 = Um die Anmeldung zu bestätigen, klicken Sie bitte auf folgenden Link:
        email.subscribeVerifySubject = Anmeldung zum Newsletter bei www.test.de
    }
    plugin.tx_fpnewsletter._LOCAL_LANG.default {
        subscribe = Send
        required = required
        tx_fpnewsletter_domain_model_log.email = E-mail address
        tx_fpnewsletter_domain_model_log.gdpr_desc1 = I have noted the
        tx_fpnewsletter_domain_model_log.gdpr_link_text = privacy policy
        tx_fpnewsletter_domain_model_log.gdpr_desc2 = and I agree that my data will be used in accordance with the statutory provisions and processed automatically.
        unsubscribe_it = Unsubscribe newsletter
        email_send1 = Thank you for your interest.<br>A confirmation email has been sent to you.
        email_verified = Your email address has been successfully added.
        email_removed = You have successfully unsubscribed from our newsletter.
        email.pleaseVerify = You've signed up for our newsletter.
        email.pleaseVerify2 = To confirm the registration, please click on the following link:
        email.subscribeVerifySubject = Registration for the newsletter at www.test.com
    }
    [siteLanguage("languageId") == 1]
        plugin.tx_fpnewsletter.settings.company = Your online editors of “Test”
    [END]

As you can see, you can even define own variables and use them. (Example from here: unsubscribe_it.)