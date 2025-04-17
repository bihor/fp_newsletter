.. include:: /Includes.txt


.. _known-problems:

Bekannte Probleme
=================

Die Domain fehlt in den E-Mails wenn man TYPO3 9 benutzt? Dann muss man die Domain in der Site-Verwaltung angeben!

Der Modus unsubscribeMode=1 funktioniert nur, wenn auch unsubscribeUid gesetzt ist.

Es scheint ein TYPO3-Bug zu geben, wo die Abmelde-Seite nicht funktioniert.
In solch einem Fall sollte man versuchen, die settings.unsubscribeUid via FlexForms zu setzen.
Bei mir funktionierte es dann.

Seit Version 5.x wird nicht nur ein Plugin-Name verwendet. In manchen Fällen muss man deshalb leider die Templates
anpassen und entweder den pi-Parameter hinzufügen, ändern oder entfernen bei f:form oder f:link.external!

Seit Version 5.x werden E-Mail-Inhalte nicht übersetzt, wenn man settings.email.dontAppendL=1 setzt.

Einen
`Bug-Tracker <https://github.com/bihor/fp_newsletter/issues>`_
für diese Extension findet man bei GitHub.
