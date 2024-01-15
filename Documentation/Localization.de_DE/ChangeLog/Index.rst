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

Version 0.12.0: double opt out wird jetzt auch unterstützt. Mehr FlexForms.

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
Address-Objekt in Verify-Templates jetzt verfügbar.
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

Version 1.2.0: veraltete Methoden ersetzt.

Version 2.0.0: über die Einstellung languageMode kann man nun die Sprache der Einträge bestimmen.
Das Verhalten bei Sprachen>0 ist nun anders. Zudem ist die Einstellung email.dontAppendL neu.
Bestätigungsmails können nun gesendet werden, wenn man sie per settings.email.enableConfirmationMails aktiviert.
Der Viewhelper f:translate kann nun auch in den E-Mail-Templates benutzt werden.
Anrede und Name werden nun ggf. in den E-Mail-Templates benutzt.
Mehr FlexForms.

Version 2.1.0: Einstellung searchPidMode und disableErrorMsg hinzugefügt.
extension-key zu composer.json hinzugefügt.

Version 2.2.1: Mehr translate keys für E-Mail-Templates hinzugefügt. Siehe Kapitel Administration.
Jetzt für TYPO3 10 und 11.

Version 2.3.2: Ein Widget für das Dashboard hinzugefügt. Die Extension Dashboard wird in TYPO3 11 benötigt.
Setting checkForRequiredExtensions hinzugefügt (funktioniert aber nicht für Dashboard in TYPO3 11).
Es kann nun auch die Tabelle fe_users benutzt werden!
Ein Formular mit Button zu den Verifizierung-E-Mails hinzugefügt.
no-cache Parameter entfernt.

Version 2.4.0: Setting dmUnsubscribeMode hinzugefügt. Flexform für "Abmeldung via Link" muss neu gespeichert werden.
Die Extension Dashboard wird nicht mehr zwingend benötigt in TYPO3 11.
Neues Feature: sende Verifizierungs-E-Mail erneut.
Französisch hinzugefügt (Dank an lucmuller).
StopActionException beim create, wenn kein Parameter vorhanden ist.

Version 3.0.0: Achtung: Default-Wert von email.dontAppendL von 0 auf 1 geändert.
Die E-mail-Templates ohne Zahlen-Endung enthalten nun übersetzte Texte.
Wenn email.dontAppendL=0 wird nun auch bei L=0 0 and den Template-Namen angehangen.
Französische E-Mails nun möglich (Dank an lucmuller).
Bugfix: Formular durch normalen Button in E-Mails ersetzt.

Version 3.1.0: Die Anrede in den E-Mails wurde in ein Partial verschoben.
Das Geschlecht divers wird in den E-Mails nicht mehr bei der Anrede berücksichtigt.
Der Name ist nun auch in der E-Mail an den Admin bei der Abmeldung verfügbar.
Neues Dashboard-Widget: Status-Diagramm.
Bugfix: die Spalte retoken war zu klein.

Version 3.2.0: module_sys_dmail_category ist nun auch per FlexForms einstellbar.
Wichtig: Layout angepasst an Bootstrap 4.
IDs im Abmeldeformular geändert.
Backend: Vorschau hinzugefügt.

Version 3.2.5:
- Switch von cURL zu RequestFactory.
- Bugfix: no categories added in tt_address.

Version 3.2.6:
- PHP-E-Mail-Validierung hinzugefügt.
- Security fix: das Standard-Passwort für fe_users durch ein Zufallspasswort ersetzt.
- Security fix: mathematical-captcha-check erweitert (man konnte bisher mogeln).
- Security fix: settings.doubleOptOut von 0 auf 1 gesetzt. Kann man auf 0 setzen, wenn man kein double opt out beim abmelden haben will.
- Security fix: einen weiteren Check zur Abmelde-Funktion hinzugefügt (man konnte bisher alle Empfänger abmelden).
- Security fix: "Information Disclosure" in der new- und unsubscribe-action.

Version 4.0.0/1:
- Achtung: das default TypoScript für die Sprache sys_language_uid 1 wurde entfernt!
- Neue Actions: Abmeldung via Luxletter und cachebares Formular für die Anmeldung.
- Neuer Task: importiere Newsletter-Abonnenten von tt_address nach fe_users.
- Setting preferXlfFile hinzugefügt. Anreden und E-Mail-Betreff kommen aus der xlf-Datei anstatt aus den Settings, wenn 1.

Version 4.0.3:
- Compatibility to direct_mail 11 added.
- Bugfix: PHP 8 und delete-action.

Version 4.1.0:
- Edit/Update-Action hinzugefügt.
- Neue TypoScript-Variablen: enableEditForm, editUid, categoryMode und editSubject.

Version 5.0:
- Überarbeitet für TYPO3 12 LTS.
- Breaking: die Email-Templates und -Partials müssen angepasst werden! extensionName="FpNewsletter" muss zu jedem f:translate hinzugefügt werden.
- Breaking: in Template-Formularen muss das Argument pluginName angegeben werden! Man findet den nötigen Wert in den Templates der Extension.
- Breaking: alle Plugins müssen via ein Update-Skript (im Install-Tool) geändert werden!

Version 5.1:
- "Custom validator" hinzugefügt, welcher für andere Captcha-Lösungen benutzt werden kann.
- Bugfix für TYPO3 12.

Version 5.2:
- Es wird nun automatisch der passende PluginName in Templates gesetzt.
- PHP-Bugfix.

Version 6.0.0:
- Breaking: der Support für direct_mail wurde entfernt. Stattdessen wird nun die Extension "mail" unterstützt.
- Breaking: die TypoScript-settings dmUnsubscribeMode, module_sys_dmail_html, module_sys_dmail_category wurden umbenannt zu
  unsubscribeMode, html, categoryOrGroup. Siehe Kapitel Administrator / Updaten auf version 6.x.

Version 6.1.0:
- Kompatibilität zu TYPO3 11.5 erneut hinzugefügt.
- Bugfix: Sprache zu den Links in den E-Mails hinzugefügt.

Version 6.2.0:
- fe_users kann nun mit der Extension Luxletter oder Mail benutzt werden.
- deleteMode 3 hinzugefügt: lösche nur Kategorien (Mail) oder Benutzergruppe (Luxletter).
- Neue Einstellungsmöglichkeit: newsletterExtension (mail oder luxletter).

Version 6.3.0:
- Neue action: direkte Abmeldung via Mail-Link und neue setting: authCodeFields.

Version 6.3.1:
- Bugfix: Luxletter ist nun die Standard Newsletter-Extension - um eine PHP-Warnung zu verhindern.

Version 6.3.2:
- Bugfix: reCAPTCHA repariert.