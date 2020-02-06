.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _changelog:

Änderungen
==========

Version 0.9.8: erste Version für TER.

Version 0.9.9: Abmeldelink zur Doku hinzugefügt.

Version 0.9.11: Status 6 hinzugefügt.

Version 0.10.0: Wichtige Änderung: plugin.tx_fpnewsletter_pi1 umbenannt nach plugin.tx_fpnewsletter, weil sonst leere TypoScript-Werte vorhandene FlexForm-Werte überschreiben.
Neue action: subscribeExt für eine Newsletter-Anmeldung über externe Extensions (z.B. Shops). Bugfix: partial-Pfad.

Version 0.10.2: Links in den E-Mail-Templates verändert (noCache statt noCacheHash).
Bugfix 2: die Texte-E-Mails wurden bisher überschrieben.

Version 0.11.0: Die Links in den E-Mail-Templates funktionieren nun endlich auch mit TYPO3 8.
Leere FlexForms werden nun durch die TypoScript-Einstellungen überschrieben.

Version 0.12.0: double opt out wird jetzt auch unterstüzt. Mehr FlexForms.

Version 0.13.0: italienische Übersetzung hinzugefügt.
Erste Version für TYPO3 9 (läuft nur, wenn auch typo3db_legacy installiert ist).

Version 0.14.0: composer-Datei hinzugefügt.
E-Mail an einen Admin nun möglich.
Einen Bug gefixt: email-check.

Version 0.15.0: Geschlecht Divers hinzugefügt.
Switch zum QueryBuilder (statt $GLOBALS['TYPO3_DB']).
reCaptcha v3 eingebaut (optional).

Version 0.16.0: f:format.raw zu Text-Links hinzugefügt.
Option module_sys_dmail_category hinzugefügt.
Adress-Objekt in Verify-Templates jetzt verfügbar.
TS optionalFieldsRequired hinzugefügt. required-Attribut hinzugefügt.

Version 0.17.0: neue TypoScript-Einstellung: email.adminMailBeforeVerification
E-Mail an den Admin nun vor oder nach der Verifizierung. Standardverhalten geändert!
E-Mail an den Admin nun nur noch in einer Sprache.
Noch ein letztes $GLOBALS['TYPO3_DB'] ersetzt.

Version 0.18.0: optionales mathematisches Captcha hinzugefügt.
Setze sys_language_uid=-1 wenn l>0.
Die Kategorien werden nun auch im Log-Eintrag gespeichert.
Mehr optionale Felder: address, zip, city, region, country, phone, mobile, fax, www, position, company.

Version 1.0.0: Möglichkeit hinzugefügt, alte Log-Einträge automatisch via Task zu löschen.
Wichtige Änderung: bei Email-Format- oder Captcha-Fehlern wird zurück zur new- oder unsubscribe-Action geleitet.
Bugfix: man kann jetzt reCAPTCHA und mathCAPTCHA zusammen benutzen.