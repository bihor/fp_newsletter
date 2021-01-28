.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator manual
====================

You will need the extension tt_address and direct_mail for this extension.
Users can subscribe to your newsletter, if you uses addresses from tt_address. fe_users is not supported today.


.. _admin-templates:

Templates
---------

You will find 2 folders in the templates-folders: Email and Log. In the Log-folder are the form-templates.
If a user submits the form, the entries lands in the table tx_fpnewsletter_domain_model_log.
Only if a user verifies his email-address, his entry will be copied to the table tt_address.

In the Email-folder you find the templates for the email to use user. Its the email for verifing the email-address.
And there are email-templates for the admin: UserToAdmin is sent before verification and SubscribeToAdmin is sent after the email verification.
If you want to change the text of the email, copy the templates e.g. to fileadmin and set the new path via TypoScript setup::

  plugin.tx_fpnewsletter.view.templateRootPaths.1 = fileadmin/bsdist/theme/tmpl/fp_newsletter/Templates/

There is a text and a HTML version for email-templates. And there is an english and a german version of this template (but not for the admin-templates).
The default template is in german. SubscribeVerify1.html contains the english text. You can use this email-templates like this::

  SubscribeVerify<LANGUID>.html and SubscribeVerify<LANGUID>.txt

Only for the language 0 you must remove the number. For the language 1 SubscribeVerify1.html is used automatically.

You can switch off this behavior with the setting email.dontAppendL = 1!
In this case you should use the variable {sys_language_uid} in the email templates.
You could use <f:if condition="{sys_language_uid} == 1"> to use more than one language in one template.


.. _admin-fluid-page-template:

Fluid Page Template
-------------------

You want to insert a newsletter subscription form to your fluid page template? E.g. in the footer of every page?
Then you have 2 possibilities.

First way: insert a static form in your template. This extension can read this variables if you provide the used form elements.
Read the chapter "Configuration -> External fields" for more informations about this way.

Second way: you can load the plugin via f:cObject typoscriptObjectPath in your page template like this::

  <f:cObject typoscriptObjectPath="lib.nlsubscriptionContent" />

Therefore you need to define lib.nlsubscriptionContent like this::

  lib.nlsubscriptionContent = CONTENT
  lib.nlsubscriptionContent {
    table = tt_content
    wrap = |
    select {
      pidInList = 22
      where = colPos = 0
    }
  }

Replace the 0 and 22 with your used colPos and page-uid at the page with your subscription form.
 

.. _admin-newsletter:

Newsletter
----------

When you send a newsletter you want to add an unsubscription link to your newsletter. If you are using direct_mail, you can do that this way::

  Unsubscribe from the newsletter:
  http://www.domain.com/newsletter/unsubscribe.html?u=###USER_uid###&t=###SYS_TABLE_NAME###&a=###SYS_AUTHCODE###

The 3 values ###USER_uid###, ###SYS_TABLE_NAME### and ###SYS_AUTHCODE### will be replaced by direct_mail.
Replace the link with the link to your unsubscribe page.
The extension fp_newsletter will check the parameters and will unsubscribe the given user directly.
Note: at the target page you need to set the template "Newsletter: unsubscribe via link" in this extension.


.. _admin-faq:

FAQ
---

- It does not work correct. What can I do?

  Maybe you need to set the storage PID twice: via plugin and via TypoScript.
  Note, that you need an own page for the newsletter unsubscription.

- The domain is missing in the email. Why?

  TYPO3 9 ignores the parameter absolute="1"? Or you have not added a domain in the backend?
  Add the domain by your own in that case.

- I don´t want/need a log entry. Can I avoid that?

  Not at all. You can add a task to your scheduler: select the task Scheduler / Table garbage collection.
  Select there the table tx_fpnewsletter_domain_model_log and set the days after the entries should be deleted.
  If the CronJob is running, the task will delete all old log entries. 