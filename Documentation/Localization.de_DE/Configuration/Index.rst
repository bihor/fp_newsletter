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

	============================ =========== ===================================================================== ==========================
	Feld                         Typ         Beschreibung                                                          Standard-Wert
	============================ =========== ===================================================================== ==========================
	table                        string      Bisher nur tt_address möglich                                         tt_address
	optionalFields               string      gender,title,firstname,lastname sind die optionalen Werte             gender,firstname,lastname
	doubleOptOut                 boolean     Bisher nur 0 möglich!!!                                               0
	enableUnsubscribeForm        boolean     Abmeldeformular auf der Anmeldeseite mit ausgeben?                    0
	subscribeUid                 integer     Seite für die Anmeldung                                               1
	subscribeMessageUid          integer     Optionale Seite für einen Redirect nach der Anmeldung
	subscribeVerifyUid           integer     Seite für die Anmelde-Verifikation
	subscribeVerifyMessageUid    integer     Optionale Seite für den Redirect nach der Anmelde-Verifikation
	unsubscribeUid               integer     Seite für die Abmeldung                                               1
	unsubscribeMessageUid        integer     Optionale Seite für den Redirect nach der Abmeldung
	unsubscribeVerifyUid         integer     Seite für die Abmelde-Verifikation (demnächst)
	unsubscribeVerifyMessageUid  integer     Optionale Seite für den Redirect nach der Abmelde-Verifikation
	gdprUid                      integer     Seite mit den DSGVO-Texten                                            1
	daysExpire                   intger      Der Verifikations-Link wird ungültig nach X Tagen                     2
	deleteMode                   integer     1: setze delete-Flag; 2: lösche endgültig                             1
	module_sys_dmail_html        boolean     0: nur TEXT; 1: TEXT und HTML                                         1
	company                      string      Name der Firma                                                        Ihre Firma
	gender.please                string      Text für die Anrede-Auswahl                                           Bitte auswählen
	gender.mr                    string      Text für Herr                                                         Herr
	gender.mrs                   string      Text für Frau                                                         Frau
	email.senderMail             string      E-Mail-Adresse des Absenders                                          beispiel@test.de
	email.senderName             string      Absender-Name                                                         Absender-Name
	email.subscribeVerifySubject string      Betreff der Verifikations-E-Mail                                      Bitte verifizieren ...
	============================ =========== ===================================================================== ==========================


Beispiele
^^^^^^^^^

Man kann die Texte für andere Sprachen so überschreiben::

  [globalVar = GP:L = 1]
  plugin.tx_fpnewsletter_pi1.settings.company = Your company
  [end]
