.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration:

Konfiguration/TypoScript Referenz
=================================

Hier sieht man, welche TypoScript-Einstellungen (settings) vorgenommen werden können. Es gibt mehr
TypoScript-Einstellungen als FlexForm-Einstellungen, was bedeutet, dass man nicht alles per FlexForms einstellen kann.

***Wichtig***: für fp_newsletter-Versionen < 6 sollte man hier nachsehen:
https://docs.typo3.org/p/fixpunkt/fp-newsletter/5.2/de-de/Configuration/Index.html


Settings-Einstellungen
^^^^^^^^^^^^^^^^^^^^^^

.. container:: ts-properties

================================= =========== ========================================================================= ================================
Feld                              Typ         Beschreibung                                                              Standard-Wert
================================= =========== ========================================================================= ================================
table                             string      tt_address, fe_users oder keine Tabelle (leerer Wert) möglich             tt_address
newsletterExtension               string      luxletter (default für fe_users) oder mail (default für tt_address)       leer = luxletter
optionalFields                    string      Optionale Werte: siehe weiter unten                                       gender,firstname,lastname
optionalFieldsRequired            string      Optionale erforderliche* Werte: siehe weiter unten
doubleOptOut                      boolean     Double opt out Abmeldung einschalten?                                     1
disableErrorMsg                   boolean     Manche Fehlermeldungen ignorieren (z.B. bereits/nicht angemeldet)?        0
enableUnsubscribeForm             boolean     Abmeldeformular auf der Anmeldeseite mit ausgeben?**                      0
enableUnsubscribeGdprAsHidden     boolean     DSGVO-Checkbox beim Abmeldeformular verbergen?                            0
enableEditForm                    boolean     Bearbeiten-Formular auf der Anmeldeseite mit ausgeben?**                  0
subscribeUid                      integer     Seite für die Anmeldung                                                   1
subscribeMessageUid               integer     Optionale Seite für einen Redirect nach der Anmeldung
subscribeVerifyUid                integer     Seite für die Anmelde-Verifikation
subscribeVerifyMessageUid         integer     Optionale Seite für den Redirect nach der Anmelde-Verifikation
unsubscribeUid                    integer     Seite für die Abmeldung                                                   1
unsubscribeMessageUid             integer     Optionale Seite für den Redirect nach der Abmeldung
unsubscribeVerifyUid              integer     Seite für die Abmelde-Verifikation (demnächst)
unsubscribeVerifyMessageUid       integer     Optionale Seite für den Redirect nach der Abmelde-Verifikation***
resendVerificationUid             integer     Seite, auf der man die Verifizierungsemail erneut anfordern kann
editUid                           integer     Seite, auf der man seit Abonnement bearbeiten kann
gdprUid                           integer     Seite mit den DSGVO-Texten                                                1
daysExpire                        integer     Der Verifikations-Link wird ungültig nach X Tagen                         2
searchPidMode                     integer     Suche in Ordnern: 0: nur im 1. Ordner; 1: in allen Ordners°               0
deleteMode                        integer     1: delete-Flag; 2: lösche endgültig; 3: lösche nur Kat./Gruppe; 4: hidden 1
languageMode                      integer     0: setzt -1 wenn L>0; 1: benutzte die sys_language_uid von pages          0
categoryMode                      integer     0: nur angegebene Kategorien bei Edit erlauben; 1: alle                   1
unsubscribeMode                   integer     0: Sofort-Abmeldung durch Link aus Luxletter; 1: zeige Abmeldeform        0
categoryOrGroup                   string      Liste von Kategorien (uid) aus sys_category oder fe_groups°°
html                              integer     0: nur TEXT; 1: TEXT und HTML; -1: ignoriere Felder der mail-Extens.      1
password                          string      Passwort für die fe_users Tabelle. random erzeugt ein zufälliges Pw.      random
authCodeFields                    string      Kopiere "Fields ... of authentication codes" von mail hierhin
reCAPTCHA_site_key                string      Websiteschlüssel für Google reCaptcha v3.
reCAPTCHA_secret_key              string      Geheimer Schlüssel für Google reCaptcha v3
mathCAPTCHA                       integer     Zeige ein mathematisches Captcha? 0: nein; 1, 2: ja, mit 1-2 Ziffern      0
honeypot                          boolean     Einen Honigtopf (honeypot) gegen Spam einschalten?                        0
debug                             boolean     Sendet keine E-Mails wenn debug=1                                         0
checkForRequiredExtensions        boolean     Prüfen, ob benötigte Extensions installiert sind? 0: nein; 1: ja.         1
company                           string      Name der Firma                                                            Ihre Firma
gender.please                     string      Text für die Anrede-Auswahl                                               Bitte auswählen
gender.mr                         string      Text für Herr                                                             Herr
gender.mrs                        string      Text für Frau                                                             Frau
parameters.active                 string      Parameter für Anmeldung aus externer Extension (POST-Parameter)
parameters.email                  string      Parameter für die E-Mail externer Herkunft (GET/POST-Parameter)
email.senderMail                  string      E-Mail-Adresse des Absenders                                              beispiel@test.de
email.senderName                  string      Absender-Name                                                             Absender-Name
email.subscribeVerifySubject      string      Betreff der Verifikations-E-Mail (Anmeldung)                              Bitte verifizieren ...
email.unsubscribeVerifySubject    string      Betreff der Verifikations-E-Mail (Abmeldung)                              Bitte verifizieren ...
email.adminMail                   string      Admin E-Mail-Adresse - wenn nicht leer: der Admin wird informiert
email.adminName                   string      Admin-Name                                                                Admin
email.adminSubscribeSubject       string      Betreff der Admin-E-Mail (Anmeldung)                                      Neue Newsletter-Anmeldung
email.adminUnsubscribeSubject     string      Betreff der Admin-E-Mail (Abmeldung)                                      Neue Newsletter-Abmeldung
email.adminMailBeforeVerification boolean     0: sende die E-Mail nach der Verifikation; 1: vor der Verifikation        0
email.subscribedSubject           string      Betreff der Bestätigungsmail (Anmeldung)                                  Bestätigung Newsletter-Anmeldung
email.unsubscribedSubject         string      Betreff der Bestätigungsmail (Abmeldung)                                  Bestätigung Newsletter-Abmeldung
email.editSubject                 string      Betreff der Bearbeiten-Email                                              Ändern Sie Ihr Newsletter-Abo...
email.enableConfirmationMails     boolean     Sende eine Bestätigungs-E-Mail an den Benutzer? 0: nein; 1: ja            0
email.dontAppendL                 boolean     Hänge die Sprach-UID an Templates an (wenn L>0)? 0: ja; 1: nein°°°        1
overrideFlexformSettingsIfEmpty   string      Leere Flexforms sollen durch TypoScript überschrieben werden              alle uid-Variablen
================================= =========== ========================================================================= ================================

Achtung*: die optional erforderlichen Werte werden nur per Browser geprüft.

Achtung**: man braucht eine eigene Seite für die Abmeldung/Bearbeitung. unsubscribeUid/editUid muss also angegebenen werden.

Achtung***: diese Seite wird auch dann benutzt, wenn doubleOptOut=0. unsubscribeMessageUid wird dann nicht benutzt.

Achtung°: dies funktioniert nur bei der Abmeldung.

Achtung°°: Kommaseparierte Liste. Beispiel: 1,3. Also ohne Leerzeichen dazwischen. Erforderlich!

Achtung°°°: der Default-Wert wurde von 0 auf 1 geändert in Version 3.0.0 und selbst wenn L=0 wird ab Version 3.0.0
0 an den E-Mail-Template-Namen angehangen wenn email.dontAppendL=0.

Beispiele
---------

Sprachen
^^^^^^^^

Man kann die Texte für andere Sprachen so überschreiben (falls preferXlfFile=0, sonst siehe letzte Zeile)::

  [siteLanguage("languageId") == "1"]
  plugin.tx_fpnewsletter.settings.company = Your company
  plugin.tx_fpnewsletter.settings.gender.please = Please select your gender
  plugin.tx_fpnewsletter.settings.gender.mr = Mr.
  plugin.tx_fpnewsletter.settings.gender.mrs = Mrs.
  plugin.tx_fpnewsletter.settings.email.senderMail = example@test.com
  plugin.tx_fpnewsletter.settings.email.senderName = Sender-name
  plugin.tx_fpnewsletter.settings.email.subscribeVerifySubject = Please verify your email
  plugin.tx_fpnewsletter.settings.email.unsubscribeVerifySubject = Please verify your email
  plugin.tx_fpnewsletter.settings.email.adminSubscribeSubject = New newsletter-subscription
  plugin.tx_fpnewsletter.settings.email.adminUnsubscribeSubject = New newsletter-unsubscription
  plugin.tx_fpnewsletter.settings.email.subscribedSubject = Newsletter-subscription confirmation
  plugin.tx_fpnewsletter.settings.email.unsubscribedSubject = Newsletter-unsubscription confirmation
  [END]
  plugin.tx_fpnewsletter._LOCAL_LANG.de.email.pleaseVerify = Bitte verifiziere deine E-Mail-Adresse durch Klick auf diesen Link:

Achtung: wenn man den Text der Standardsprache überschreiben will, entfernt man die beiden Zeilen:
[siteLanguage("languageId") == "1"] und [END].

Falls man die Setting preferXlfFile=1 setzt, kann man die Texte (Betreff und Anrede) so überschreiben::

  plugin.tx_fpnewsletter._LOCAL_LANG.en.email.subscribedSubject = Your newsletter subscription is now confirmed
  plugin.tx_fpnewsletter._LOCAL_LANG.de.email.subscribedSubject = Deine Newsletter-Anmeldung ist nun bestätigt

Achtung: wenn man andere Sprachen in den Emails verwenden will, sollte man das Kapitel "Administrator-Handbuch" lesen.
Bei settings.email.dontAppendL=0  ist die Standardsprache deutsch. Diese Templates enden ab Version 3.0.0 mit 0.html.
Ab Version 3.0.0 werden in den E-Mail-Templates ohne Zahl-Endung übersetzte Texte verwendet.

*Deprecation*: dontAppendL wird in Version 8.0.0 entfernt. Auch alle Templates mit der Endung 0 und 1.

Externe Felder
^^^^^^^^^^^^^^

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

Achtung:

Wenn man bei den Einstellungen "[FE][cacheHash][enforceValidation] = 1" gesetzt hat, muss man diesen Parameter unter
"[FE][cacheHash][excludedParameters]" mit hinzufügen!

Captchas
^^^^^^^^

Man kann 3 verschiedene Captcha-Typen benutzen.
Wenn man das Google reCaptcha v3 benutzen will, muss man nur den website key und den secret key angeben.
Wenn man stattdessen ein mathematisches Captcha benutzen will, kann man 1 oder 2 Ziffern verwenden. Der maximale Wert bei 2 Ziffern ist 19. Beispiel::

  plugin.tx_fpnewsletter.settings.mathCAPTCHA = 2

Als 3. Option kann man auch ein ganz anderes Captcha benutzen, z.B. Friendly Captcha. Eine Anleitung dazu findet man
im Kapitel "Administration".

Optionale und erforderliche Felder
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Nur email und gdpr sind erforderliche Felder im Modell. Wenn man mehr erforderliche Felder haben will, kann man sie nur via TypoScript und Templates als
erforderlich markieren. Folgende optionalen Felder sind möglich/stehen zur Verfügung:
gender, title, firstname, lastname, address, zip, city, region, country, phone, mobile, fax, www, position, company.
Man kann alle diese Felder auch als erforderlich markieren. Hier ein Beispiel für das Anmeldeformular via TypoScript Setup::

  plugin.tx_fpnewsletter.settings.optionalFields = gender,title,firstname,lastname,www,position,company
  plugin.tx_fpnewsletter.settings.optionalFieldsRequired = firstname,lastname,company

Benutzung von Kategorien
^^^^^^^^^^^^^^^^^^^^^^^^

Die Tabelle sys_category enthält Kategorien für mail. So benutzt man sie::

  plugin.tx_fpnewsletter.settings.categoryOrGroup = 1,3

Es werden dann 2 Einträge in sys_category_record_mm gemacht und in tt_address wird categories gesetzt (nach der Verifikation).

Die Kategorien werden als hidden-Feld ins Template eingefügt. Wenn man eine flexiblere Lösung will, könnte man z.B. Checkboxes per jQuery auswerten und
die angeklickten Kategorien ins hidden-Feld kopieren.

Genau so kann man auch Gruppen für fe_users angeben. Das gilt nur für die Extension Luxletter.
Wenn newsletterExtension=mail gesetzt ist, werden keine Benutzer-Gruppen beachtet. Dann werden nur Kategorien benutzt.

Beachte: wenn deleteMode=3 gesetzt ist, werden nur die Kategorien entfernt, die unter categoryOrGroup angegeben sind.
Und das Feld mail_active wird auf 0 gesetzt, wenn newsletterExtension=mail gesetzt ist.

Ändern der Labels
^^^^^^^^^^^^^^^^^

Wie in jeder Extension auch, kann man die Labels via TypoScript ändern. Hier 2 Beispiele::

  plugin.tx_fpnewsletter._LOCAL_LANG.de.tx_fpnewsletter_domain_model_log.email = Email
  plugin.tx_fpnewsletter._LOCAL_LANG.de.tx_fpnewsletter_domain_model_log.gdpr_desc2 = Ich bin damit einverstanden, dass die von mir angegebenen Daten elektronisch erhoben und gespeichert werden.

Man findet die Bezeichnungen in den Templates bei f:translate key.

Benötigte Extensions
^^^^^^^^^^^^^^^^^^^^

Standardmäßig überprüft die Extension in der Action new (Anmeldeformular), ob die benötigten Extensions installiert sind.
settings.table kann leer, tt_address oder fe_users sein. Bei tt_address wird auch mail benötigt, wenn man entweder
settings.html oder settings.categoryOrGroup verwendet. Die Überprüfung kann man ausschalten::

  plugin.tx_fpnewslettersettings.checkForRequiredExtensions = 0

Komplettes Beispiel
^^^^^^^^^^^^^^^^^^^

Hier ein komplettes Beispiel für Luxletter und 2 Sprachen::

    plugin.tx_fpnewsletter.view.templateRootPaths.10 = EXT:example/Resources/Private/Ext/fp_newsletter/Templates/
    plugin.tx_fpnewsletter.view.partialRootPaths.10 = EXT:example/Resources/Private/Ext/fp_newsletter/Partials/
    plugin.tx_fpnewsletter.settings {
        table = fe_users
        optionalFields =
        doubleOptOut = 0
        enableUnsubscribeGdprAsHidden = 1
        honeypot = 1
        preferXlfFile = 1
        gdprUid = 1138
        subscribeUid = 1167
        unsubscribeUid = 1002
        subscribeVerifyUid = 1001
        categoryOrGroup = 19
        company = Ihre Online-Redaktion von „Test“
    }
    plugin.tx_fpnewsletter._LOCAL_LANG.de {
        subscribe = Absenden
        tx_fpnewsletter_domain_model_log.email = E-Mail-Adresse
        tx_fpnewsletter_domain_model_log.gdpr_desc1 = Ich habe die
        tx_fpnewsletter_domain_model_log.gdpr_link_text = Datenschutzerklärung
        tx_fpnewsletter_domain_model_log.gdpr_desc2 = zur Kenntnis genommen und bin damit einverstanden, dass meine Daten unter Beachtung der gesetzlichen Bestimmungen satzungsgemäß verwendet und automatisiert verarbeitet werden.
        unsubscribe_it = Newsletter abbestellen
        email_send1 = Vielen Dank für Ihr Interesse.<br>Eine Bestätigungs-E-Mail wurde Ihnen zugesandt.
        email_verified = Ihre E-Mail-Adresse wurde erfolgreich aufgenommen.
        email_removed = Sie haben sich erfolgreich von unserem Newsletter abgemeldet.
        email.pleaseVerify = Sie haben sich für unseren Newsletter angemeldet.
        email.pleaseVerify2 = Um die Anmeldung zu bestätigen, klicken Sie bitte auf folgenden Link:
        email.subscribeVerifySubject = Anmeldung zum Newsletter bei www.test.de
    }
    plugin.tx_fpnewsletter._LOCAL_LANG.en {
        subscribe = Send
        required = required
        tx_fpnewsletter_domain_model_log.email = E-mail address
        tx_fpnewsletter_domain_model_log.gdpr_desc1 = I have noted the
        tx_fpnewsletter_domain_model_log.gdpr_link_text = privacy policy
        tx_fpnewsletter_domain_model_log.gdpr_desc2 = and I agree that my data will be used in accordance with the statutory provisions and processed automatically.
        unsubscribe_it = Unsubscribe newsletter
        email_send1 = Thank you for your interest.<br>A confirmation email has been sent to you.
        email_verified = Your email address has been successfully added.
        email_removed = You have successfully unsubscribed from our newsletter.
        email.pleaseVerify = You've signed up for our newsletter.
        email.pleaseVerify2 = To confirm the registration, please click on the following link:
        email.subscribeVerifySubject = Registration for the newsletter at www.test.com
    }
    [siteLanguage("languageId") == 1]
        plugin.tx_fpnewsletter.settings.company = Your online editors of “Test”
    [END]

Wie man sehen kann, kann man auch eigene Variablen definieren und verwenden. Hier z.B.: unsubscribe_it.