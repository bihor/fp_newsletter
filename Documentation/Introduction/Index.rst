.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _introduction:

Introduction
============


.. _what-it-does:

What does it do?
----------------

The extension fp_newsletter is designed to provide a newsletter subscription and unsubscription service for the table
tt_address or fe_users which can be used by the extension mail or luxletter.
Furthermore it is designed to be compatible with the GDPR.
A log is written about (every) action in a separate table.
Old log entries can be deleted by a scheduler task.
Note: there are more TypoScript-settings than FlexForm-settings.
But the extension can be used without tt_address/fe_users too. Therefore an admin-email-address can be specified.
The admin will then get an email with the subscription data.
Google reCaptcha v3 or a mathematical captcha can be enabled too.
There is a widget for the dashboard available.
Available languages: english, german/deutsch, french/français and italian/italiano.
The standard language is german, but english texts are also available.

Attention!
^^^^^^^^^^

This extension is not designed for multiple newsletter-categories! It is possible to subscribe to more than one
category, but it is not possible to unsubscribe only from a specific category (at the unsubscribe-form).
The whole user will be deleted at an unsubscription so it is not possible to unsubscribe only from category/newsletter X.
But from version 4.1.0 it is possible to edit an newsletter subscription!


.. _screenshots:

Screenshots
-----------

One example view of a frontend page:

.. figure:: ../Images/frontend.png
   :width: 814px
   :alt: Frontend page

   Subscription form.

Thanks to ...
^^^^^^^^^^^^^

Thanks to the
`fixpunkt für digitales GmbH, Bonn <https://www.fixpunkt.com/webentwicklung/typo3>`_
for giving me the possibility to realize
`this extension <https://www.fixpunkt.com/webentwicklung/typo3/typo3-programmierung>`_
and share it with the TYPO3 community.
