.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration:

Konfiguration
=============

Hier sieht man, welche TypoScript-Einstellungen vorgenommen werden können. Es gibt mehr TypoScript-Einstellungen
als FlexForm-Einstellungen, was bedeutet, dass man nicht alles per FlexForms einstellen kann.


.. _configuration-typoscript:

TypoScript Referenz
-------------------

Konfiguration via TypoScript (und FlexForms).


Settings-Einstellungen
^^^^^^^^^^^^^^^^^^^^^^

.. container:: ts-properties

	================================ =========== ===================================================================== ==========================
	Feld                             Typ         Beschreibung                                                          Standard-Wert
	================================ =========== ===================================================================== ==========================
	table                            string      Bisher nur tt_address möglich                                         tt_address
	optionalFields                   string      gender,title,firstname,lastname sind die optionalen Werte             gender,firstname,lastname
	doubleOptOut                     boolean     Double opt out Abmeldung einschalten?                                 0
	enableUnsubscribeForm            boolean     Abmeldeformular auf der Anmeldeseite mit ausgeben?**                  0
	enableUnsubscribeGdprAsHidden    boolean     DSGVO-Checkbox beim Abmeldeformular verbergen?                        0
	subscribeUid                     integer     Seite für die Anmeldung                                               1
	subscribeMessageUid              integer     Optionale Seite für einen Redirect nach der Anmeldung
	subscribeVerifyUid               integer     Seite für die Anmelde-Verifikation
	subscribeVerifyMessageUid        integer     Optionale Seite für den Redirect nach der Anmelde-Verifikation
	unsubscribeUid                   integer     Seite für die Abmeldung                                               1
	unsubscribeMessageUid            integer     Optionale Seite für den Redirect nach der Abmeldung
	unsubscribeVerifyUid             integer     Seite für die Abmelde-Verifikation (demnächst)
	unsubscribeVerifyMessageUid      integer     Optionale Seite für den Redirect nach der Abmelde-Verifikation*
	gdprUid                          integer     Seite mit den DSGVO-Texten                                            1
	daysExpire                       intger      Der Verifikations-Link wird ungültig nach X Tagen                     2
	deleteMode                       integer     1: setze delete-Flag; 2: lösche endgültig                             1
	module_sys_dmail_html            integer     0: nur TEXT; 1: TEXT und HTML; -1: ignoriere dieses Feld              1
	module_sys_dmail_category        string      Komma separierte Liste von Kategorien (uid) aus sys_dmail_category
	reCAPTCHA_site_key               string      Websiteschlüssel für Google reCaptcha v3
	reCAPTCHA_secret_key             string      Geheimer Schlüssel für Google reCaptcha v3
	company                          string      Name der Firma                                                        Ihre Firma
	gender.please                    string      Text für die Anrede-Auswahl                                           Bitte auswählen
	gender.mr                        string      Text für Herr                                                         Herr
	gender.mrs                       string      Text für Frau                                                         Frau
	parameters.active                string      Parameter für Anmeldung aus externer Extension (POST-Parameter)
	parameters.email                 string      Parameter für die E-Mail externer Herkunft (GET/POST-Parameter)
	email.senderMail                 string      E-Mail-Adresse des Absenders                                          beispiel@test.de
	email.senderName                 string      Absender-Name                                                         Absender-Name
	email.subscribeVerifySubject     string      Betreff der Verifikations-E-Mail (Anmeldung)                          Bitte verifizieren ...
	email.unsubscribeVerifySubject   string      Betreff der Verifikations-E-Mail (Abmeldung)                          Bitte verifizieren ...
	email.adminMail                  string      Admin E-Mail-Adresse - wenn nicht leer: der Admin wird informiert
	email.adminName                  string      Admin-Name                                                            Admin
	email.adminSubscribeSubject      string      Betreff der Admin-E-Mail (Anmeldung)                                  Neue Newsletter-Anmeldung
	email.adminUnsubscribeSubject    string      Betreff der Admin-E-Mail (Abmeldung)                                  Neue Newsletter-Abmeldung
	overrideFlexformSettingsIfEmpty  string      Leere Flexforms sollen durch TypoScript überschrieben werden          alle uids...
	================================ =========== ===================================================================== ==========================

Achtung*: diese Seite wird auch dann benutzt, wenn doubleOptOut=0. unsubscribeMessageUid wird dann nicht benutzt.

Achtung**: man braucht eine eigene Seite für die Abmeldung. unsubscribeUid muss also angebenen werden.


Beispiele
^^^^^^^^^

Man kann die Texte für andere Sprachen so überschreiben::

  [globalVar = GP:L = 1]
  plugin.tx_fpnewsletter_pi1.settings.company = Your company
  [end]


Man kann auch eine Default-E-Mail-Adresse aus den Parametern auslesen und übernehmen. Wenn man z.B. im Footer ein Formular mit einem E-Mail-Feld hat,
welches nlemail heißt, kann man den abgesendeten Wert wie folgt auslesen lassen::

  plugin.tx_fpnewsletter.settings.parameters.email = nlemail


Man kann die Anmeldung auch über ein externes Formular durchführen lassen. Wenn man z.B. einen Shop hat, wo man sich zum Schluß
bei einer Bestellung auch zum Newsletter anmelden können soll, dann muss man diese Extension unter die Shop-Extension einfügen und das Template
"Anmeldung über externe Extension" auswählen. Zudem muss man die POST-Parameter angeben, die ausgewertet werden sollen::

  plugin.tx_fpnewsletter.settings.parameters.active = tx_myshop_pi1|newBestellung|newsletter
  plugin.tx_fpnewsletter.settings.parameters.email = tx_myshop_pi1|newBestellung|email

Es können an dieser Stelle nur Parameter von anderen Extensions mit dem selben Format ausgewertet werden.
Wenn beide Parameter gesetzt sind, wird zur Action create weitergeleitet.
