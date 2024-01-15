.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator manual
====================

You will not need the extension tt_address and mail or luxletter for this extension, but it is recommended.
Users can subscribe to your newsletter, if you use addresses from tt_address with the extension mail
or if you use addresses from fe_users with the extension luxletter or mail.


.. _admin-templates:

Templates
---------

You will find 2 folders in the templates-folders: Email and Log. In the Log-folder are the form-templates.
If a user submits the form, the entries lands in the table tx_fpnewsletter_domain_model_log.
Only if a user verifies his email-address, his entry will be copied to the table tt_address.

In the Email-folder you find the templates for the email to use user. Its the email for verifying the email-address.
And there are email-templates for the admin: UserToAdmin is sent before verification and SubscribeToAdmin is sent after the email verification.
If you want to change the text of the email, copy the templates e.g. to fileadmin and set the new path via TypoScript setup::

  plugin.tx_fpnewsletter.view.templateRootPaths.1 = fileadmin/bsdist/theme/tmpl/fp_newsletter/Templates/

There is a text and a HTML version for email-templates. And there is an (additional) english and a german version of this template (but not for the admin-templates).
From version 3.0.0 the normal templates contains localized texts.
The default template is in german till version 3.0.0. From version 3.0.0 the german templates have the ending 0.html.
E.g. SubscribeVerify1.html contains the english text. You can use this email-templates like this::

  SubscribeVerify<LANGUID>.html and SubscribeVerify<LANGUID>.txt

Only for the language 0 you must remove the number until version 3.0.0. For the language 1 SubscribeVerify1.html is used automatically.
This is the behavior when email.dontAppendL = 0. From version 3.0.0 email.dontAppendL is by default 1.

You can switch off this behavior with the setting email.dontAppendL = 1!
In this case you can use the variable {sys_language_uid} in the email templates.
You could use <f:if condition="{sys_language_uid} == 1"> to use more than one language in one template.

You can use this translate keys in the email templates:
email.dear-gender-first-and-last-name, email.dear-first-and-last-name, email.dear-first-name, email.dear,
email.gender-first-and-last-name, email.first-and-last-name and email.first-name.

Note
~~~~

By default the gender and name is used in emails too and thereby the values of the first- and lastname field should be
highlighted as user input in email templates to prevent potential spam-/phishing emails.

Important
~~~~~~~~~

Since version 5.x there is not only one plugin name (pi1). In some cases therefore you need to change the template
and add or delete the pi-parameter at a f:link.external. E.g. at the unsubscribe-page without a verify unsubscribe page.


.. _admin-subscription_form:

Subscription form on every page
-------------------------------

You want to insert a newsletter subscription form to your fluid page template? E.g. in the footer of every page?
Then you have 2 possibilities.

First way: insert a static form in your template. This extension can read this variables if you provide the used form elements.
Read the chapter "Configuration -> External fields" for more information about this way.

Second way: you can load the plugin via f:cObject typoscriptObjectPath in your page template like this::

  <f:cObject typoscriptObjectPath="lib.nlsubscriptionContent" />

Therefore you need to define lib.nlsubscriptionContent like this::

  lib.nlsubscriptionContent = CONTENT
  lib.nlsubscriptionContent {
    table = tt_content
    wrap = |
    select {
      pidInList = 22
      where = colPos = 0
    }
  }

Replace the 0 and 22 with your used colPos and page-uid at the page with your subscription form.
If you use the plugin from fp_newsletter for a subscription form, you should select the cacheable subscription form.
In this case you must define a page for the subscription too. The form will have that page as target.

Note: you can not use a mathematical captcha on the cacheable subscription form.

Note: you must remove a non-static form on pages that uses the fp_newsletter plugin. E.g. use this on pages that have
a verification or delete plugin::

  lib.nlsubscriptionContent >
  lib.nlsubscriptionContent = TEXT
  lib.nlsubscriptionContent.value =


.. _admin-note-mail:

Note for the Mail-extension
---------------------------

If you are using the Mail-Extension, you can use tt_address or fe_users.
If you are using tt_address, this additional fields will be filled: mail_html, mail_salutation and mail_active.
If you use the table fe_users, this additional fields will be set:  mail_html, mail_salutation, mail_active and
categories from categoryOrGroup. New users will not have a group set!

.. _admin-note-luxletter:

Note for the Luxletter-extension
--------------------------------

If you are using the Luxletter-Extension, you can use only the table fe_users.
This additional fields will be filled: user group with categoryOrGroup and if the setting newsletterExtension=luxletter
is set: luxletter_language.

.. _admin-mail:

Unsubscription via Mail-extension
---------------------------------

When you send a newsletter you want to add an unsubscription link to your newsletter. If you are using mail, you can do that this way::

  Unsubscribe from the newsletter:
  https://www.domain.com/newsletter/unsubscribe.html?email=###USER_email###&authcode=###MAIL_AUTHCODE###

Replace the link with the link to your unsubscribe page and put it in the newsletter-template or use it as email-content.
###USER_email### and ###MAIL_AUTHCODE### will be replaced by the mail-extension. The parameters can be changed. It must be set via
TypoScript::

  plugin.tx_fpnewsletter.settings.parameters.email = email
  plugin.tx_fpnewsletter.settings.parameters.authcode = authcode

The extension fp_newsletter will read those parameters and use the email as default email-address or it will make a
direct unsubscription.
Note: at the target page you need to select the plugin "Newsletter: unsubscribe via form" or
"Newsletter: unsubscribe via mail-link" from this extension.
In the last case, settings.authCodeFields must be set too.
Disadvantage: it is not possible to unsubscribe only from a specific newsletter in a folder. The whole tt_address entry
will be deleted!


.. _admin-luxletter:

Unsubscription via Luxletter-extension
--------------------------------------

There is a unsubscribe link in the luxletter template. If you use the Luxletter-plugin at the target page,
it is not possible to change the status of a Log entry. Furthermore Luxletter removes only the fe_groups category
of a fe_users entry. The user remains present.

An completely other ways uses fp_newsletter, if you use "Newsletter: unsubscribe via luxletter-link" of fp_newsletter.
In this case, a fp_newsletter log is created and the subscriber will be removed from fe_users.
Disadvantage: it is not possible to unsubscribe only from a specific newsletter in a folder. The whole fe_users entry
will be deleted!

Example for an unsubscribe-link::

  <f:link.external uri="{luxletter:mail.getUnsubscribeUrl(newsletter:newsletter,user:user,site:site)}" additionalAttributes="{data-luxletter-parselink:'false'}" target="_blank" style="font-family:'FiraSans-Light', 'Helvetica Neue', Arial, sans-serif;">
    Unsubscribe from this newsletter abbestellen
  </f:link.external>

Set plugin.tx_fpnewsletter.settings.unsubscribeMode = 1 if the unsubscription form should be shown instead of the
direct unsubscription.


.. _admin-captchas:

Captchas
--------

You can use 3 different captchas. 2 of them can be configured via TypoScript settings. See chapter "Configuration".
The third method is a custom captcha validator and requires PHP acknowledgment, because you will need a second extension.
And maybe a third one, e.g. a "friendly captcha" extension.

This extension provides a validate event. If you want to use this validator, add some lines to the New.html template of
this extension::

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

  Add xmlns:fp="https://typo3.org/ns/YourVendor/YourExtension/ViewHelpers" and replace YourVendor and YourExtension.
  Add <fp:form.friendlyCaptcha name="captcha_solution">...</fp:form.friendlyCaptcha>
  and adapt it to your custom captcha extension. And set the TypoScript settings "site_key".
  Note: the lines about shows you only an example for a "friendly captcha" solution.

  Furthermore you need an event listener in your custom captcha extension. It may look like this::

    use YourVendor\YourExtension\Services\CaptchaService;
    use Fixpunkt\FpNewsletter\Events\ValidateEvent;
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

Adding additional fields to tt_address
--------------------------------------

If you want to add additional fields to tt_address, they must be already present in the log-table
(tx_fpnewsletter_domain_model_log) and in the tt_address-table.
If they are not present, then you must add the fields to both tables via an own
extension in your ext_tables.sql file. Example: you want to add the field "gdpr" to tt_address.
This field is already part of the log-table. You need to add it only to the tt_address-table by your own.
Then you must specify which additional fields should be copied from the log-table to the tt_address-table via TypoScript::

  plugin.tx_fpnewsletter.settings.additionalTtAddressFields = gdpr

That's all.

.. _admin-security:

Security-notice to version 3.2.6
--------------------------------

If you use older versions, you should know this information about the fixed security issues:

1. It was possible to unsubscribe all users.

2. The TypoScript value for plugin.tx_fpnewsletter.settings.doubleOptOut was set to 1 by default.
   You can set it to 1 too if you want to enable double opt out for the unsubscription.

3. It was possible to cheat at the mathematical captcha check.

4. It was possible to get user data at the new- and unsubscribe-action.

Therefore you should update the extension!


.. _version_6:

Updating to version 6.x
-----------------------

Because the support for the extension direct_mail was removed in version 6.0.0, some TypoScript settings changed
the name. Unfortunately there is only a update-script for migrating this fields to the new name for FlexForms.
You must adapt your TypoScript settings and HTML-Templates by your own. That means you need to edit your
TypoScript-settings and HTML-files (FormFields.html and FormFieldsEdit.html): rename the old names.
This settings are affected:

1. dmUnsubscribeMode was renamed to unsubscribeMode.

2. module_sys_dmail_html was renamed to html.

3. module_sys_dmail_category was renamed to categoryOrGroup.


.. _admin-faq:

FAQ
---

- It does not work correct. What can I do?

  Maybe you need to set the storage PID twice: via plugin and via TypoScript.
  Note, that you need an own page for the newsletter unsubscription.

- A link is not working as expected. Whats wrong?

  Since version 5.x there is more than one plugin. Maybe the plugin is not the right one.
  See chapter "Important" above.

- The domain is missing in the email. Why?

  TYPO3 9 ignores the parameter absolute="1"? Or you have not added a domain in the backend?
  Add the domain by your own in that case.

- What will be the username if I use fe_users?

  The username will be the email-address. The default password is joh316. The category can be set via
  categoryOrGroup and is mandatory!!!

- I use fe_users but nothing happens.

  Have you set the setting categoryOrGroup? You must define a category.

- I use tt_address but not mail and nothing happens.

  Have you set the setting html to -1? For the HTML-option-field direct_mail/mail is required.

- I get the error 'The action "xyz" is not allowed by this plugin.' Whats wrong?

  Maybe you have 2 fp_newsletter plugins on one page. That don´t work. You find a solution for
  "Subscription form on every page" further up.

- I don´t want/need a log entry. Can I avoid that?

  Not at all. You can add a task to your scheduler: select the task Scheduler / Table garbage collection.
  Select there the table tx_fpnewsletter_domain_model_log and set the days after the entries should be deleted.
  If the CronJob is running, the task will delete all old log entries. 