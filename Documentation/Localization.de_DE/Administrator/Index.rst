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
Erst nachdem ein Benutzer seine E-Mail-Adresse verifiziert hat, werden die Daten in die Tabelle tt_address oder fe_users kopiert.

Im Email-Ordner findet man die Templates, die per E-Mail verschickt werden.
Es gibt Email-Templates für die Verifizierung der E-Mail-Adresse und für den Admin.
UserToAdmin wird vor der Verifikation benutzt und SubscribeToAdmin nach der Verifikation der E-Mail-Adresse.
Zum ändern der Templates muss man sie z.B. nach fileadmin kopieren und den Link dazu angeben::

  plugin.tx_fpnewsletter.view.templateRootPaths.1 = fileadmin/bsdist/theme/tmpl/fp_newsletter/Templates/

Es gibt eine Text- und eine HTML-Version für die E-Mails. Und es gibt beide Templates in deutsch und in englisch (außer die für den Admin).
Standardmässig muss man für jede Sprache neue Templates anlegen. Das Default-Template ist in deutsch und SubscribeVerify1.html
ist in englisch verfasst. Es werden automatisch diese Templates verwendet::

  SubscribeVerify<LANGUID>.html and SubscribeVerify<LANGUID>.txt

Nur für die Sprache 0 muss man die Zahl weglassen. SubscribeVerify1.txt ist das Template für die Sprache 1.

Man kann dieses Verhalten jedoch mit der Einstellung email.dontAppendL=1 abschalten!
In dem Fall sollte man die Variable {sys_language_uid} in den E-Mail-Templates verwenden.
Man kann also mit Hilfe von <f:if condition="{sys_language_uid} == 1"> mehrere Sprachen in einem Template verwenden.

Man kann folgende keys in den E-Mail-Templates benutzen:
email.dear-gender-first-and-last-name, email.dear-first-and-last-name, email.dear-first-name, email.dear,
email.gender-first-and-last-name, email.first-and-last-name und email.first-name.


.. _admin-fluid-page-template:

Fluid Page Template
-------------------

Du willst ein Anmeldeformular in dein Seiten-Template einbauen? Z.B. auf jeder Seite in den Footer?
Da gibt es 2 Möglichkeiten.

1. Möglichkeit: füge ein statisches Formular in dein Footer-Template ein. Diese Extension kann die Parameter aus diesem Formular auslesen.
Lies das Kapitel "Konfiguration -> Externe Felder" für mehr Details dazu.

2. Möglichkeit: du kannst ein Plugin via f:cObject typoscriptObjectPath in dein Template einbauen. Beispiel::

  <f:cObject typoscriptObjectPath="lib.nlsubscriptionContent" />

Dafür musst du lib.nlsubscriptionContent in deinem TypoScript-Template wie folgt definieren::

  lib.nlsubscriptionContent = CONTENT
  lib.nlsubscriptionContent {
    table = tt_content
    wrap = |
    select {
      pidInList = 22
      where = colPos = 0
    }
  }

Ersetze 0 und 22 durch die colPos und page-uid, welche du benutzt hast auf der Seite mit dem Anmelde-Plugin.
 

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

- Was ist der username wenn ich die Tabelle fe_users verwende?

  Als username wird die E-Mail-Adresse verwendet. Das Standard-Passwort ist joh316. Die Kategorie setzt man mittels module_sys_dmail_category.

- Ich benutzt die fe_users Tabelle, aber es passiert nichts.

  Hast du auch settings.module_sys_dmail_category gesetzt?

- Wie kann man sich bei luxletter abmelden?

  Weiß ich noch nicht.

- Ich brauche / will keine Log-Einträge. Kann man das ausschalten?

  Nicht ganz. Man kann nur alte Log-Einträge automatisch löschen lassen. Dazu fügt man einen Task
  "Tabellen-Müllsammlung" hinzu und wählt da die Tabelle tx_fpnewsletter_domain_model_log aus.
  Dann kann man angeben, nach wie vielen Tagen ein Log-Eintrag gelöscht werden soll.
  Wenn der ConJob läuft, werden alte Log-Einträge dann automatisch gelöscht.