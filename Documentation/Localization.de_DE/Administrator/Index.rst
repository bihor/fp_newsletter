.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _admin-manual:

Administrator-Handbuch
======================

Man braucht nicht unbedingt die Extension tt_address und mail, um diese Extension benutzen zu können.
Es geht auch ohne, aber mehr Sinn macht es schon, wenn man die Tabellen tt_address oder fe_users benutzt.
Die Tabelle fe_users kann man zusammen mit der Extension Luxletter oder Mail benutzen.
Benutzer können sich dann zum Newsletter anmelden, wenn die Tabelle tt_address oder fe_users in der Newsletter-Extension
benutzt wird.


.. _admin-templates:

Templates
---------

Man findet 4 Ordner mit Templates: Backend, Widget, Email und Log. Backend und Widget wird nur im Backend verwendet.
Im Log-Ordner findet man die Templates für die Formulare.
Wenn ein Benutzer solch ein Formular absendet, landen die Daten in der Tabelle tx_fpnewsletter_domain_model_log.
Erst nachdem ein Benutzer seine E-Mail-Adresse verifiziert hat, werden die Daten in die Tabelle tt_address oder fe_users kopiert.

Im Email-Ordner findet man die Templates, die per E-Mail verschickt werden.
Es gibt E-Mail-Templates für die Verifizierung der E-Mail-Adresse und für den Admin.
UserToAdmin wird vor der Verifikation benutzt und SubscribeToAdmin nach der Verifikation der E-Mail-Adresse.
Zum ändern der Templates muss man sie z.B. nach fileadmin kopieren und den Link dazu angeben::

  plugin.tx_fpnewsletter.view.templateRootPaths.1 = fileadmin/bsdist/theme/tmpl/fp_newsletter/Templates/

Es gibt eine Text- und eine HTML-Version für die E-Mails.
Da kann man die Variable {sys_language_uid} in den E-Mail-Templates verwenden.
Man kann also mit Hilfe von <f:if condition="{sys_language_uid} == 1"> mehrere Sprachen in einem Template verwenden.

Man kann folgende keys in den E-Mail-Templates benutzen:
email.dear-gender-first-and-last-name, email.dear-first-and-last-name, email.dear-first-name, email.dear,
email.gender-first-and-last-name, email.first-and-last-name und email.first-name.

Beachte
~~~~~~~

Standardmässig wird neben der E-Mail-Adresse auch Anrede und Name in den E-Mails verwendet.
Es wird empfohlen, diese zu highlighten, um Spam/Pishing-Emails vorzubeugen.

Wichtig
~~~~~~~

Seit Version 5.x wird nicht nur ein Plugin-Name verwendet. In manchen Fällen muss man deshalb leider die Templates
anpassen und entweder den pi-Parameter hinzufügen oder entfernen! Beispielsweise bei der Abmeldeseite ohne
Verifizierung-Seite.


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


.. _admin-genders:

Verwendung von anderen Anreden in den E-Mails
---------------------------------------------

Wenn man die verwendeten Anreden nicht mag, kann man sie via TypoScript ändern.
Siehe Kapitel Konfiguration/TypoScript Referenz.
Wenn man jedoch für Herr und Frau unterschiedliche Anreden will, muss man die Datei
Partials/Email/Salutation.html ändern. Beispiel::

  <f:if condition="{gender_id}==1 && {lastname}">
    <f:then>
      <f:translate key="email.dear-mrs-last-name" arguments="{0: lastname}" extensionName="FpNewsletter" languageKey="{language_code}" />
    </f:then>
  </f:if>
  <f:if condition="{gender_id}==2 && {lastname}">
    <f:then>
      <f:translate key="email.dear-mr-last-name" arguments="{0: lastname}" extensionName="FpNewsletter" languageKey="{language_code}" />
    </f:then>
  </f:if>

Nun kann man neues TypoScript-Settings hinzufügen. TypoScript-Beispiel für das obige Partial::

  plugin.tx_fpnewsletter._LOCAL_LANG.de.email.dear-mr-last-name = Sehr geehrter Herr %s,
  plugin.tx_fpnewsletter._LOCAL_LANG.de.email.dear-mrs-last-name = Sehr geehrte Frau %s,


.. _admin-categories:

Verwendung eigener Kategorien/Gruppen
-------------------------------------

Ab Version 8.2.0 kann man eigene Kategorien oder Gruppen im Template verwenden.
Zuerst mnuss man dazu die Einstellung settings.categoryOrGroup z.B. auf 1 setzen auf der Anmeldeseite::

  plugin.tx_fpnewsletter.settings.categoryOrGroup = 1

Zweitens muss man die Einstellung settings.categoryOrGroup leeren auf der Verifikationsseite::

  plugin.tx_fpnewsletter.settings.categoryOrGroup =

Drittens fügt man seine eigenen Kategorien oder Gruppen im Template Partials/Log/FormFields.html hinzu.
Dazu ersetzt man diese Zeile::

  <f:form.hidden property="categories" value="{settings.categoryOrGroup}" id="fp_categories" />

durch soetwas::

  <div class="form-group">
    <label class="form-label" class="form-label">Choose a group:</label>
    <div class="form-check">
        <label for="fp_category_1" class="form-check-label">
            <f:form.radio class="form-check-input radiobox categories" id="fp_category_1" property="categories" value="3" /> Greenhorn
        </label>
    </div>
    <div class="form-check">
        <label for="fp_category_2" class="form-check-label">
            <f:form.radio class="form-check-input radiobox categories" id="fp_category_2" property="categories" value="4" /> Kunde
        </label>
    </div>
    <div class="form-check">
        <label for="fp_category_3" class="form-check-label">
            <f:form.radio class="form-check-input radiobox categories" id="fp_category_3" property="categories" value="5" /> Mitarbeiter
        </label>
    </div>
  </div>

Nun werden die gespeicherten Werte übernommen statt die von settings.categoryOrGroup.

Achtung: auf der Abmeldeseite muss settings.categoryOrGroup auch z.B. auf 1 gesetzt sein.

Achtung: das ganze wurde nicht mit der Bearbeiten-Seite getestet.


.. _admin-note-mail:

Anmerkung für die Mail-Extension
--------------------------------

Wenn man die Mail-Extension benutzt, kann man die Tabellen tt_address oder fe_users benutzen.
Wenn man tt_address benutzt, werden diese zusätzlichen Felder befüllt: mail_html, mail_salutation und mail_active.
Wenn man fe_users benutzt, werden diese zusätzlichen Felder befüllt:  mail_html, mail_salutation, mail_active und
categories von categoryOrGroup. Es wird keine Gruppe zugewiesen!

.. _admin-note-luxletter:

Anmerkung für die Luxletter-Extension
-------------------------------------

Wenn man die Luxletter-Extension benutzt, kann man nur die Tabelle fe_users verwenden.
Diese zusätzlichen Felder werden befüllt: Gruppe von categoryOrGroup und wenn die Setting newsletterExtension=luxletter
gesetzt ist: luxletter_language.

.. _admin-mail:

Abmelden via Mail-Extension
---------------------------

Wenn du einen Newsletter verschickst, soll darin sicherlich auch ein Abmeldelink drin stehen. Das kann man so machen,
wenn man mail benutzt::

  Newsletter abbestellen:
  https://www.domain.de/newsletter/abmelden.html?email=###USER_email###&authcode=###MAIL_AUTHCODE###

Ersetze den Link durch deine Abmeldeseite und füge ihn in dein Newsletter-Template oder den Newsletter-Inhalt ein.
###USER_email### und ###MAIL_AUTHCODE### wird von mail automatisch ersetzt.
Der email- und authCodeFields-Parameter kann so geändert werden via TypoScript::

  plugin.tx_fpnewsletter.settings.parameters.email = email
  plugin.tx_fpnewsletter.settings.parameters.authcode = authcode

Die Extension fp_newsletter wird die Parameter lesen und die E-Mail als Default-E-Mail-Adresse setzen
oder den Abonnenten gleich abmelden.
Achtung: bei der Zielseite muss dabei das Plugin "Newsletter: Abmeldung via Formular" oder
"Newsletter: Abmeldung via mail-Link" ausgewählt sein. In letzterem Fall muss auch settings.authCodeFields gesetzt werden.
Nachteil: man kann sich mit fp_newsletter nicht nur von einem speziellen Newsletter abmelden.
Man wird von allen abonnierten Newslettern eines Ordners abgemeldet.


.. _admin-luxletter:

Abmelden via Luxletter-Extension
--------------------------------

Luxletter bietet auch einen Abmeldelink an. Zusätzlich bietet Luxletter ein Plugin, mit dem man sich aus dem
Newsletter austragen kann. Wenn man das Plugin benutzt, wird kein Log-Eintrag von fp_newsletter geändert. Der Status
ändert sich also nicht. Zudem wird nur die fe_groups Kategorie beim Abonnenten gelöscht.

Einen ganz anderen Weg beschreitet fp_newsletter, wenn man auf der Zielseite "Newsletter: Abmeldung via luxletter-Link"
benutzt. In diesem Fall wird der Log-Eintrag aktuell gehalten und weiterhin wird nicht nur eine Kategorie beim
Abonnenten entfernt, sondern der ganze fe_users-Eintrag wird gleich gelöscht. Nachteil: man kann sich mit fp_newsletter
nicht nur von einem speziellen Newsletter abmelden. Man wird von allen abonnierten Newslettern eines Ordners abgemeldet.

Beispiel für einen Abmelde-Link::

  <f:link.external uri="{luxletter:mail.getUnsubscribeUrl(newsletter:newsletter,user:user,site:site)}" additionalAttributes="{data-luxletter-parselink:'false'}" target="_blank" style="font-family:'FiraSans-Light', 'Helvetica Neue', Arial, sans-serif;">
    Newsletter abbestellen
  </f:link.external>

Setze plugin.tx_fpnewsletter.settings.unsubscribeMode = 1 wenn stattdessen erst das Abmeldeformular gezeigt werden soll.


.. _admin-captchas:

Captchas
--------

Man kann 3 verschiedene Captcha-Methoden benutzen. 2 davon kann man via TypoScript-Einstellungen konfigurieren.
Siehe Kapitel "Configuration". Die 3. Methode ist eine spezielle Lösung, die PHP-Kenntnisse voraussetzt, weil man
noch 1-2 weitere Extensions dafür benötigt.

Diese Extension stellt ein Validate-Event zur Verfügung, welches im New.html Template dieser Extension wie folgt
benutzt werden kann::

  <html xmlns:fp="https://typo3.org/ns/YourVendor/YourExtension/ViewHelpers" xmlns:f="https://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers" data-namespace-typo3-fluid="true">
    ...
    <f:form action="create" name="log" pluginName="new" object="{log}">
		<f:render partial="Log/FormFields" arguments="{_all}" />
		<fp:form.friendlyCaptcha name="captcha_solution">
			<div class="frc-captcha" data-sitekey="{settings.site_key}" data-solution-field-name="{name}" data-start="focus"></div>
		</fp:form.friendlyCaptcha>
		<div class="text-right">
			<f:form.submit value="{f:translate(key: 'subscribe', default: 'subscribe')}" class="btn btn-primary" />
		</div>
	</f:form>
    ...
  </html>

  Füge xmlns:fp="https://typo3.org/ns/YourVendor/YourExtension/ViewHelpers" hinzu und ersetzte YourVendor und YourExtension.
  Füge <fp:form.friendlyCaptcha name="captcha_solution">...</fp:form.friendlyCaptcha>
  hinzu und passe es an deine Extension an. Füge die TypoScript settings "site_key" hinzu.
  Bemerkung: diese Zeilen zeigen nur ein Beispiel für eine "friendly captcha" Lösung.

  Weiterhin braucht man einen Event-Listener in der eigenen Captcha-Extension. Er sollte so in etwa aussehen::

    use YourVendor\YourExtension\Services\CaptchaService;
    use Fixpunkt\FpNewsletter\Events\ValidateEvent;
    use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
    use Psr\Http\Message\ServerRequestInterface;

    class NewsletterValidationListener
    {

        /** @var CaptchaService  */
        protected CaptchaService $captchaService;

        /**
         * @param CaptchaService $captchaService
         */
        public function __construct(CaptchaService $captchaService) {
            $this -> captchaService = $captchaService;
        }

        /**
         * Checks if the captcha was solved correctly.
         * @param ValidateEvent $event
         * @return void
         */
        public function __invoke(ValidateEvent $event) : void {
            /** @var ServerRequestInterface $request */
            $request = $GLOBALS['TYPO3_REQUEST'];

            $pluginName = "tx_fpnewsletter_pi1";

            // see if data was provided
            if(!key_exists($pluginName, $request -> getParsedBody()) || !is_array($request -> getParsedBody()[$pluginName])) {
                $event -> setValid(false);
                return;
            }

            [...]

            // validate solution
            $solution = $request -> getParsedBody()[$pluginName]["captcha_solution"];
            $valid = $this -> captchaService -> validate($solution);
            if(!$valid["verified"]) {
                $event -> setValid(false);
                $event -> setMessage("Captcha not valid");
            }
        }
    }


.. _admin-additional-fields:

Weitere Felder zu tt_address hinzufügen
---------------------------------------

Wenn du weitere Felder zu tt_address hinzufügen möchtest, dann müssen diese Felder sowohl in der Log-Tabelle
(tx_fpnewsletter_domain_model_log) als auch in der tt_address-Tabelle vorhanden sein.
Wenn sie noch nicht da sind, müssen sie in einer Extension in der Datei ext_tables.sql hinzugefügt werden.
Beispiel: du willst das Feld "gdpr" nach tt_address kopieren.
Dieses Feld ist in der Log-Tabelle bereits vorhanden und deshalb muss es nur noch zur tt_address-Tabelle von dir
hinzugefügt werden. Danach muss man noch per TypoScript angeben, welche zusätzlichen Felder mit kopiert werden soll::

  plugin.tx_fpnewsletter.settings.additionalTtAddressFields = gdpr

Das ist alles.

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


.. _version_6:

Updaten auf Version 6.x
-----------------------

Weil der Support für die Extension direct_mail in Version 6.0.0 entfernt wurde, wurden auch manche TypoScript-Variablen
umbenannt! Leider gibt es nur ein Update-Skript, welches die alten Variablen in FlexForms umbenennt.
Du musst nun also selber im TypoScript-Setup und in HTML-Templates Anpassungen vornehmen. Das heißt, du
musst gesetzte TypoScript-Variablen selber umbenennen und in eigenen HTML-Dateien
(FormFields.html und FormFieldsEdit.html) Felder umbenennen.
Betroffen sind diese 3 TypoScript-Settings:

1. "dmUnsubscribeMode" wurde umbenannt zu "unsubscribeMode".

2. "module_sys_dmail_html" wurde umbenannt zu "html".

3. "module_sys_dmail_category" wurde umbenannt zu "categoryOrGroup".


.. _admin-faq:

FAQ
---

- Es läuft nicht richtig. Was kann ich tun?

  Möglicherweise muss man die storagePID doppelt angeben: via Plugin und via TypoScript.
  Beachte, dass man für die Abmeldung eine eigene Seite braucht!

- Ein Link funktioniert nicht so wie er sollte. Was ist falsch?

  Seit Version 5.x gibt es mehr als nur ein Plugin (pi1). Vielleicht ist ein falsches Plugin im Link?
  Siehe Kapitel "Wichtig" weiter oben.

- Die Domain fehlt im Link in der E-Mail. Wieso?

  TYPO3 9 ignoriert anscheinend den Parameter absolute="1"? Oder du hast keine Domain im Backend angegeben?
  Füge die Domain dann selber hinzu.

- Was ist der username wenn ich die Tabelle fe_users verwende?

  Als username wird die E-Mail-Adresse verwendet. Das Standard-Passwort ist joh316. Die Gruppe setzt man mittels categoryOrGroup.

- Ich benutzte die fe_users Tabelle, aber es passiert nichts.

  Hast du auch settings.categoryOrGroup gesetzt?

- Ich benutzte die tt_address Tabelle, aber nicht die mail-Extension und es passiert nichts.

  Hast du auch settings.html=-1 gesetzt? Für das HTML-Feld wird nämlich direct_mail/mail benötigt.

- Ich brauche / will keine Log-Einträge. Kann man das ausschalten?

  Nicht ganz. Man kann nur alte Log-Einträge automatisch löschen lassen. Dazu fügt man einen Task
  "Tabellen-Müllsammlung" hinzu und wählt da die Tabelle tx_fpnewsletter_domain_model_log aus.
  Dann kann man angeben, nach wie vielen Tagen ein Log-Eintrag gelöscht werden soll.
  Wenn der ConJob läuft, werden alte Log-Einträge dann automatisch gelöscht.
