.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _changelog:

Änderungen
==========

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

Version 1.0.4: Bugfix: Anmeldung via externem Formular.

Version 1.1.0: honeypot hinzugefügt.
Bugfix: Fehlermeldung verhindern beim abmelden, wenn ein Captcha aktiviert ist.

Version 1.2.0: veralterte Methoden ersetzt.

Version 2.0.0: über die Einstellung languageMode kann man nun die Sprache der Einträge bestimmen.
Das Verhalten bei Sprachen>0 ist nun anders. Zudem ist die Einstellung email.dontAppendL neu.
Bestätigungsmails können nun gesendet werden, wenn man sie per settings.email.enableConfirmationMails aktiviert.
Der Viewhelper f:translate kann nun auch in den E-Mail-Templates benutzt werden.
Anrede und Name werden nun ggf. in den E-Mail-Templates benutzt.
Mehr FlexForms.

Version 2.1.0: Einstellung searchPidMode und disableErrorMsg hinzugefügt.
extension-key zu composer.json hinzugefügt.

Version 2.1.1: Mehr translate keys für E-Mail-Templates hinzugefügt. Siehe Kapitel Administration.

Version 2.1.2:
Security fix: mathematical-captcha-check erweitert (man konnte bisher mogeln).
Security fix: settings.doubleOptOut von 0 auf 1 gesetzt. Kann man auf 0 setzen, wenn man kein double opt out beim abmelden haben will.
Security fix: einen weiteren Check zur Abmelde-Funktion hinzugefügt (man konnte bisher alle Empfänger abmelden).
Security fix: "Information Disclosure" in der  new- und unsubscribe-action.