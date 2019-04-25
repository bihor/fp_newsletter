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

The extension fp_newsletter is designed to provide a newsletter subscription and unsubscription service for the table tt_address which can be used
by the extension direct_mail. Furthermore it is designed to be compatible with the GDPR. A log is written about (every) action in a separate table.
Note: there are more TypoScript-settings than FlexForm-settings.
But the extension can be used without tt_address too. Therefore an admin-email-address can be specified.
The admin will then get an email with the subscription data.
Google reCaptcha v3 can be enabled too.
Available languages: english, german/deutsch and italian/italiano.


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
`fixpunkt werbeagentur gmbh, Bonn <https://www.fixpunkt.com/webentwicklung/typo3/>`_
for giving me the possibility to realize
`this extension <https://www.fixpunkt.com/webentwicklung/typo3/typo3-extensions/>`_
and share it with the TYPO3 community.
