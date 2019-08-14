.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

Version 0.9.8: German documentation added.

Version 0.9.9: Unsubscribe link added to the manual.

Version 0.9.11: Status 6 added.

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

Version 0.15.3: f:format.raw added to text-links.
Setting module_sys_dmail_category added.
Address-object in verify-actions now available.
TS optionalFieldsRequired added. required-attribute added.