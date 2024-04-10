.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _known-problems:

Known Problems
==============

The domain is missing in the emails when using TYPO3 9? Then you need to add the domain in the site-configuration!

The mode unsubscribeMode=1 works only if unsubscribeUid is set.

There is a TYPO3 bug in some releases where the unsubscribe form does not work.
In that case you can try to set the settings.unsubscribeUid via FlexForms. For me that helps.

Since version 5.x there is not only one plugin name (pi1). In some cases therefore you need to change the template
and add, modify or delete the pi-parameter at a f:link.external or f:form.

Since version 5.x the email content will not be translated when settings.email.dontAppendL=1 is set.

A
`bug tracker <https://github.com/bihor/fp_newsletter/issues>`_
is available for this project at GitHub.
