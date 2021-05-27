.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

Here you find all configuration possibilities.


.. _configuration-typoscript:

TypoScript Reference
--------------------

Configuration via TypoScript (and FlexForms).


Properties for settings
^^^^^^^^^^^^^^^^^^^^^^^

.. container:: ts-properties

	================================= =========== ===================================================================== =================================
	Property                          Data type   Description                                                           Default
	================================= =========== ===================================================================== =================================
	table                             string      Today only tt_address or none (empty value) supported                  tt_address
	optionalFields                    string      Optional fields: see below                                            gender,firstname,lastname
	optionalFieldsRequired            string      Optional required* fields: see below
	doubleOptOut                      boolean     Enable double out out unsubscription?                                 0
    disableErrorMsg                   boolean     Disable some error messages (e.g. already/not subscribed)?            0
	enableUnsubscribeForm             boolean     Enable unsubscribe form at the subscribe page?**                      0
	enableUnsubscribeGdprAsHidden     boolean     Do not show the gdpr-checkbox at unsubscribe form?                    0
	subscribeUid                      integer     Page for the subscription                                             1
	subscribeMessageUid               integer     Optional page for the redirect after subscription
	subscribeVerifyUid                integer     Page for the subscription-verification
	subscribeVerifyMessageUid         integer     Optional page for the redirect after subscription-verification
	unsubscribeUid                    integer     Page for the unsubscription                                           1
	unsubscribeMessageUid             integer     Optional page for the redirect after unsubscription
	unsubscribeVerifyUid              integer     Page for the unsubscription-verification
	unsubscribeVerifyMessageUid       integer     Optional page for the redirect after unsubscription-verification***
	gdprUid                           integer     Page with the GDPR text                                               1
	daysExpire                        integer     The link expires after X days                                         2
    searchPidMode                     integer     Search in tt_address: 0: only in the 1. folder; 1: in all folders°    0
	deleteMode                        integer     1: set deletion flag; 2: delete entry                                 1
	languageMode                      integer     0: uses -1 if L>0; 1: uses the sys_language_uid from pages            0
	module_sys_dmail_html             integer     0: only TEXT; 1: TEXT and HTML; -1: ignore this field in tt_address   1
	module_sys_dmail_category         string      Comma separated list of categories (uid) from sys_dmail_category
	reCAPTCHA_site_key                string      Website-key for Google reCaptcha v3. curl needed!
	reCAPTCHA_secret_key              string      Secret key for Google reCaptcha v3
	mathCAPTCHA                       integer     Show a mathematical captcha? 0: no; 1: with 1 digit; 2: with 2 digits 0
	honeypot                          boolean     Enable a honeypot against spam?                                       0
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
	email.enableConfirmationMails     boolean     Send confirmation email to the user after verification? 0: no; 1: yes 0
	email.dontAppendL                 boolean     Append the language UID to a template when L>0? 0: yes; 1: no         0
	overrideFlexformSettingsIfEmpty   string      Empty FlexForms should be overwritten by TypoScript                   all uid settings
	================================= =========== ===================================================================== =================================

Note*: only a check via browser is made for the optional required fields.

Note**: you need an own page for the unsubscription! unsubscribeUid should be defined therefore.

Note***: this page is used too, if doubleOptOut=0. unsubscribeMessageUid is not used if doubleOptOut=0.

Note°: this works only at the unsubscription.


Property details / examples
---------------------------

Languages
^^^^^^^^^

You can overrite the text for other languges like this::

  [siteLanguage("languageId") == "1"]
  plugin.tx_fpnewsletter_pi1.settings.company = Your company
  [END]

Note: the default language of the email-templates is german! You find the english version in the files that end with 1.html.
You should copy the files and modify the path to the templates via TypoScript. See chapter "Administrator manual".

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

Captchas
^^^^^^^^

You can use 2 different captchas. If you want to use the Google reCaptcha v3 you need to provide the website key and the secret key.
If you want to use a mathematical captcha, you can use 1 or 2 digits. The maximum value for 2 digits is 19. Example::

  plugin.tx_fpnewsletter.settings.mathCAPTCHA = 2

Optional and requiered fields
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Only email and gdpr are mandatory fields in the model. If you need more mandatory fields, you can make them only via TypoScript and the templates required.
There are the following optional fields awailable: gender, title, firstname, lastname, address, zip, city, region, country, phone, mobile, fax, www, position, company.
You can make all this fields required. Here an example to enable some of this fields in the subscription form via TypoScript setup::

  plugin.tx_fpnewsletter_pi1.settings.optionalFields = gender,title,firstname,lastname,www,position,company
  plugin.tx_fpnewsletter_pi1.settings.optionalFieldsRequired = firstname,lastname,company
  
Using of categories
^^^^^^^^^^^^^^^^^^^

The table module_sys_dmail_category contains categories for direct_mail. This extension uses that categories instaed of the categories from sys_category.
If you use them like this::

  plugin.tx_fpnewsletter_pi1.settings.module_sys_dmail_category = 1,3

Then this extension will do the same like the direct_mail_subscription extension. It will make two entires into sys_dmail_ttaddress_category_mm
and it will set module_sys_dmail_category in tt_address (after the verification). Do you expect something else?

The categories are as hidden-field in the template. You could add checkboxes and copy the checked values by jQuery to the hidden-field if you need a more flexible solution.

Changing the labels
^^^^^^^^^^^^^^^^^^^

Like in every extension, you can change the labels via TypoScript. Here 2 examples::

  plugin.tx_fpnewsletter._LOCAL_LANG.de.tx_fpnewsletter_domain_model_log.email = Email
  plugin.tx_fpnewsletter._LOCAL_LANG.de.tx_fpnewsletter_domain_model_log.gdpr_desc2 = Ich bin damit einverstanden, dass die von mir angegebenen Daten elektronisch erhoben und gespeichert werden.

You find the designations in the templates used in f:translate key.