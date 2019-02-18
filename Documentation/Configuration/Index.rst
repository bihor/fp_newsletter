.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

Technical information: Installation, Reference of TypoScript options,
configuration options on system level, how to extend it, the technical
details, how to debug it and so on.

Language should be technical, assuming developer knowledge of TYPO3.
Small examples/visuals are always encouraged.

Target group: **Developers**


.. _configuration-typoscript:

TypoScript Reference
--------------------

Configuration via TypoScript (and FlexForms).


Properties for settings
^^^^^^^^^^^^^^^^^^^^^^^

.. container:: ts-properties

	================================ =========== ===================================================================== ==========================
	Property                         Data type   Description                                                           Default
	================================ =========== ===================================================================== ==========================
	table                            string      Today only tt_address suported                                        tt_address
	optionalFields                   string      gender,title,firstname,lastname are supported                         gender,firstname,lastname
	doubleOptOut                     boolean     Enable double out out unsubscription?                                 0
	enableUnsubscribeForm            boolean     Enable unsubscribe form at the subscribe page?                        0
	enableUnsubscribeGdprAsHidden    boolean     Do not show the gdpr-checkbox at unsubscribe form?                    0
	subscribeUid                     integer     Page for the subscription                                             1
	subscribeMessageUid              integer     Optional page for the redirect after subscription
	subscribeVerifyUid               integer     Page for the subscription-verification
	subscribeVerifyMessageUid        integer     Optional page for the redirect after subscription-verification
	unsubscribeUid                   integer     Page for the unsubscription                                           1
	unsubscribeMessageUid            integer     Optional page for the redirect after unsubscription
	unsubscribeVerifyUid             integer     Page for the unsubscription-verification
	unsubscribeVerifyMessageUid      integer     Optional page for the redirect after unsubscription-verification*
	gdprUid                          integer     Page with the GDPR text                                               1
	daysExpire                       intger      The link expires after X days                                         2
	deleteMode                       integer     1: set deletion flag; 2: delete entry                                 1
	module_sys_dmail_html            boolean     0: only TEXT; 1: TEXT and HTML                                        1
	company                          string      Name of your company                                                  Ihre Firma
	gender.please                    string      Text for gender selection                                             Bitte auswählen
	gender.mr                        string      Text for the gender mr                                                Herr
	gender.mrs                       string      Text for the gender mrs                                               Frau
	parameters.active                string      Parameter for newsletter subscription in external extension (POST)
	parameters.email                 string      Parameter for the email from external source (GET/POST-parameter)
	email.senderMail                 string      Your email-address                                                    beispiel@test.de
	email.senderName                 string      Your name                                                             Absender-Name
	email.subscribeVerifySubject     string      Subject of the verify email (subscription)                            Bitte verifizieren ...
	email.unsubscribeVerifySubject   string      Subject of the verify email (unsubscription)                          Bitte verifizieren ...
	overrideFlexformSettingsIfEmpty  string      Empty flexforms should be overwritten by FlexForms                    all uids...
	================================ =========== ===================================================================== ==========================

Note*: this page is used too if doubleOptOut=0. unsubscribeMessageUid is not used if doubleOptOut=0.


Property details
^^^^^^^^^^^^^^^^

You can overrite the text for other languges like this::

  [globalVar = GP:L = 1]
  plugin.tx_fpnewsletter_pi1.settings.company = Your company
  [end]


You can set a default email-address which was submitted before. E.g. you have a form in the footer and the field-name is nlemail, then set the parameter like this::

  plugin.tx_fpnewsletter.settings.parameters.email = nlemail

That parameter will be read and the value of that parameter will be used as default email-address.


You can do the subscription via an form in an other extension too. E.g. you have an shop and at the end of the order the user wants to subscribe to the newsletter?
Then put this extension under the shop extension an select the action "subscribe via external extension".
Furthermore you must specify the POST-parameter, which are used in the other extension like this::

  plugin.tx_fpnewsletter.settings.parameters.active = tx_myshop_pi1|newOrder|newsletter
  plugin.tx_fpnewsletter.settings.parameters.email = tx_myshop_pi1|newOrder|email

Only parameters of this format are possible. If they are there, a forward will be made to the action create.
