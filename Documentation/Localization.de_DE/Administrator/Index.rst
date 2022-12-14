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

Es gibt eine Text- und eine HTML-Version für die E-Mails. Und es gibt beide Templates auch in deutsch und in englisch (außer die für den Admin).
Standardmässig musste man bis Version 3.0.0 für jede Sprache neue Templates anlegen.
Das Default-Template enthält übersetzbare Texte ab Version 3.0.0 und bis Version 3.0.0 sind Template ohne Zahlen-Endung in deutsch verfasst und
z.B. SubscribeVerify1.html in englisch verfasst. Ab Version 3.0.0 haben die deutschen Templates die Endung 0.html.
Es werden automatisch diese Templates verwendet, wenn settings.email.dontAppendL=0 ist::

  SubscribeVerify<LANGUID>.html and SubscribeVerify<LANGUID>.txt

Für die Sprache 0 muss man die Zahl weglassen bis Version 3.0.0. SubscribeVerify1.txt ist das Template für die Sprache 1.

Man kann dieses Verhalten jedoch mit der Einstellung email.dontAppendL=1 abschalten!
In dem Fall kann man die Variable {sys_language_uid} in den E-Mail-Templates verwenden.
Man kann also mit Hilfe von <f:if condition="{sys_language_uid} == 1"> mehrere Sprachen in einem Template verwenden.

Man kann folgende keys in den E-Mail-Templates benutzen:
email.dear-gender-first-and-last-name, email.dear-first-and-last-name, email.dear-first-name, email.dear,
email.gender-first-and-last-name, email.first-and-last-name und email.first-name.

Beachte
~~~~~~~

Standardmässig wird neben der E-Mail-Adresse auch Anrede und Name in den E-Mails verwendet.
Es wird empfohlen, diese zu highlighten, um Spam/Pishing-Emails vorzubeugen.


.. _admin-Anmeldeformular:

Anmeldeformular auf jeder Seite
-------------------------------

Du willst ein Anmeldeformular in dein Seiten-Template einbauen? Z.B. auf jeder Seite in den Footer?
Da gibt es 2 Möglichkeiten.

Erste Möglichkeit: füge ein statisches Formular in dein Footer-Template ein. Diese Extension kann die Parameter aus diesem
Formular auslesen.
Lies das Kapitel "Konfiguration -> Externe Felder" für mehr Details dazu.

Zweite Möglichkeit: du kannst ein Plugin via f:cObject typoscriptObjectPath in dein Template einbauen. Beispiel::

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
Falls du das Anmelde-Plugin von fp_newsletter benutzt, solltest du das cachable Anmelde-Formular dort auswählen.
In dem Fall muss noch eine Seite für die Anmeldung definiert werden, wohin das Formular umleiten soll.
Ein mathematisches Captcha ist bei diesem cachable Anmelde-Formular nicht möglich!


.. _admin-direct_mail:

Direct_mail
-----------

Wenn du einen Newsletter verschickt, soll darin sicherlich auch ein Abmeldelink drin stehen. Das kann man so machen, wenn man direct_mail benutzt::

  Newsletter abbestellen:
  http://www.domain.de/newsletter/abmelden.html?u=###USER_uid###&t=###SYS_TABLE_NAME###&a=###SYS_AUTHCODE###

Die 3 Werte ###USER_uid###, ###SYS_TABLE_NAME### und ###SYS_AUTHCODE### wird direct_mail ersetzen.
Du musst nur den Link mit deinem Abmeldelink ersetzen.
Die Extension fp_newsletter wird die Parameter überprüfen und den angegebenen Benutzer sofort abmelden.
Achtung: bei der Zielseite muss dabei das Template "Newsletter: Abmeldung via direct_mail-Link" ausgewählt sein.
Nachteil: man kann sich mit fp_newsletter nicht nur von einem speziellen Newsletter abmelden.
Man wird von allen abonnierten Newslettern eines Ordners abgemeldet.


.. _admin-luxletter:

Luxletter
---------

Luxletter bietet auch einen Abmeldelink an. Zusätzlich bietet Luxletter ein Plugin, mit dem man sich aus dem
Newsletter austragen kann. Wenn man das Plugin benutzt, wird kein Log-Eintrag von fp_newsletter geändert. Der Status
ändert sich also nicht. Zudem wird nur die fe_groups Kategorie beim Abonnenten gelöscht.

Einen ganzen anderen Weg beschreitet fp_newsletter, wenn man auf der Zielseite "Newsletter: Abmeldung via luxletter-Link"
benutzt. In diesem Fall wird der Log-Eintrag aktuell gehalten und weiterhin wird nicht nur eine Kategorie beim
Abonnenten entfernt, sondern der ganze fe_users-Eintrag wird gelöscht. Nachteil: man kann sich mit fp_newsletter
nicht nur von einem speziellen Newsletter abmelden. Man wird von allen abonnierten Newslettern eines Ordners abgemeldet.


.. _admin-security:

Sicherheitshinweis zu Version 3.2.6
-----------------------------------

Falls du eine ältere Version benutzt, solltest du folgendes über die behobenen Fixes wissen:

1. Man konnte bisher alle Newsletter-Empfänger abmelden.

2. Der TypoScript-Wert für plugin.tx_fpnewsletter.settings.doubleOptOut wurde auf 1 gesetzt.
  Du könntest diesen Wert auch auf 1 setzen, falls nichts gegen double-opt-out bei der Abmeldung spricht.

3. Es war möglich, beim mathematischen Captcha-Check zu mogeln.

4. Es war möglich, Daten über andere Newsletter-Empfänger bei der An- oder Abmeldung zu erfahren.

Deshalb sollte man unbedingt updaten!


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

- Ich benutzte die fe_users Tabelle, aber es passiert nichts.

  Hast du auch settings.module_sys_dmail_category gesetzt?

- Ich benutzte die tt_address Tabelle, aber kein direct_mail und es passiert nichts.

  Hast du auch settings.module_sys_dmail_html=-1 gesetzt? Für das HTML-Feld wird nämlich direct_mail benötigt.

- Ich brauche / will keine Log-Einträge. Kann man das ausschalten?

  Nicht ganz. Man kann nur alte Log-Einträge automatisch löschen lassen. Dazu fügt man einen Task
  "Tabellen-Müllsammlung" hinzu und wählt da die Tabelle tx_fpnewsletter_domain_model_log aus.
  Dann kann man angeben, nach wie vielen Tagen ein Log-Eintrag gelöscht werden soll.
  Wenn der ConJob läuft, werden alte Log-Einträge dann automatisch gelöscht.