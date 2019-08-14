.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _admin-manual:

Administrator-Handbuch
======================

Man braucht die Extension tt_address und direct_mail um diese Extension benutzen zu können.
Benutzer können sich zum Newsletter anmelden, wenn die Tabelle tt_address in der Newsletter-Extension benutzt wird.
Die Tabelle fe_users sollte auch noch benutzt werden können, aber das geht noch nicht.


.. _admin-templates:

Templates
---------

Man findet 2 Ordner mit Templates: Email und Log. Im Log-Ordner findet man die Templates für die Formulare.
Wenn ein Benutzer solch ein Formular absendet, landen die Daten in der Tabelle tx_fpnewsletter_domain_model_log.
Erst nachdem ein Benutzer seine E-Mail-Adresse verifiziert hat, werden die Daten in die Tabelle tt_address kopiert.

Im Email-Ordner findet man die Templates, die per E-Mail verschickt werden.
Im Moment gibt es nur Email-Templates für die Anmeldung. Das Double-Opt-Out-Verfahren ist noch nicht implementiert!
Das Email-Templates ist für die Verifizierung der E-Mail-Adresse da.
Es gibt eine Text- und eine HTML-Version. Und es gibt beide Templates in deutsch und in englisch.
Der Übersetzungs-Viewhelper funktioniert bei diesen Templates nicht, weshalb man die Texte dort direkt eintragen muss.
Man muss deshalb für jede Sprache neue Templates anlegen. Das Default-Template ist in deutsch und SubscribeVerify1.html
ist in englisch verfasst. Es werden automatisch diese Templates verwendet::

  SubscribeVerify<LANGUID>.html and SubscribeVerify<LANGUID>.txt

Nur für die Sprache 0 muss man die Zahl weglassen. SubscribeVerify1.txt ist das Template für die Sprache 1.


.. _admin-newsletter:

Newsletter
----------

Wenn du einen Newsletter verschickt, soll darin sicherlich auch ein Abmeldelink drin stehen. Das kann man so machen, wenn man direct_mail benutzt::

  Newsletter abbestellen:
  http://www.domain.de/newsletter/abmelden.html?u=###USER_uid###&t=###SYS_TABLE_NAME###&a=###SYS_AUTHCODE###

Die 3 Werte ###USER_uid###, ###SYS_TABLE_NAME### und ###SYS_AUTHCODE### wird direct_mail ersetzen.
Du musst nur den Link mit deinem Abmeldelink ersetzen.
Die Extension fp_newsletter wird die Parameter überprüfen und den angegebenen Benutzer sofort abmelden.
Achtung: bei der Zielseite muss dabei das Template "Newsletter: Abmeldung via Link" ausgewählt sein.


.. _admin-faq:

FAQ
---

- Es läuft nicht richtig. Was kann ich tun?

  Möglicherweise muss man die storagePID doppelt angeben: via Plugin und via TypoScript.
  Beachte, dass man für die Abmeldung eine eigene Seite braucht!

- Die Domain fehlt im Link in der E-Mail. Wieso?

  TYPO3 9 ignoriert anscheinend den Parameter absolute="1"? Oder du hast keine Domain im Backend angegeben?
  Füge die Domain dann selber hinzu.