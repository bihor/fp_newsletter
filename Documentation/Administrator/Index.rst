.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

You will need the extension tt_address and direct_mail for this extension.
Users can subscribe to your newsletter, if you uses addresses from tt_address. fe_users is not supported today, but it is planed.


.. _admin-templates:

Templates
---------

You will find 2 folders in the templates-folders: Email and Log. In the Log-folder are the form-templates.
If a user submits the form, the entries lands in the table tx_fpnewsletter_domain_model_log.
Only if a user verifies his email-address, his entry will be copied to the table tt_address.

In the Email-folder you find the templates for the email to use user. Its the email for verifing the email-address.
There is a text and a HTML version. And there is an english and a german version of this template.
The translate-ViewHelper can not be used in this templates thats why you need to create a template for every used language.
The default template is in german. SubscribeVerify1.html contains the english text. You can use this email-templates like this::

  SubscribeVerify<LANGUID>.html and SubscribeVerify<LANGUID>.txt

Only for the language 0 you must remove the number. For the language 1 SubscribeVerify1.html is used automatically.


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