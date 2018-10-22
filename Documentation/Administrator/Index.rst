.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

You will need the extension tt_address for this extension.
Users can subscribe to your newsletter, if you uses addresses from tt_address. fe_users is not supported today, but it is planed.

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