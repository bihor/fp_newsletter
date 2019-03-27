plugin.tx_fpnewsletter {
  view {
    templateRootPaths.0 = EXT:fp_newsletter/Resources/Private/Templates/
    templateRootPaths.1 = {$plugin.tx_fpnewsletter.view.templateRootPath}
    partialRootPaths.0 = EXT:fp_newsletter/Resources/Private/Partials/
    partialRootPaths.1 = {$plugin.tx_fpnewsletter.view.partialRootPath}
    layoutRootPaths.0 = EXT:fp_newsletter/Resources/Private/Layouts/
    layoutRootPaths.1 = {$plugin.tx_fpnewsletter.view.layoutRootPath}
  }
  persistence {
    storagePid = {$plugin.tx_fpnewsletter.persistence.storagePid}
    #recursive = 1
  }
  features {
    skipDefaultArguments = 1
    requireCHashArgumentForActionArguments = 0
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }
  settings {
	table = tt_address
	optionalFields = gender,firstname,lastname
	doubleOptOut = 0
	enableUnsubscribeForm = 0
	enableUnsubscribeGdprAsHidden = 0
	subscribeUid = 1
	subscribeMessageUid =
	subscribeVerifyUid =
	subscribeVerifyMessageUid =
	unsubscribeUid = 1
	unsubscribeMessageUid =
	unsubscribeVerifyUid =
	unsubscribeVerifyMessageUid =
	gdprUid = 1
	daysExpire = 2
	deleteMode = 1
	module_sys_dmail_html = 1
	company = Ihre Firma
	gender {
	  please = Bitte auswählen
	  mr = Herr
	  mrs = Frau
	}
	parameters {
	  active =
	  email =
	}
	email {
		senderMail = beispiel@test.de
		senderName = Absender-Name
		subscribeVerifySubject = Bitte verifizieren Sie Ihre E-Mail-Adresse
		unsubscribeVerifySubject = Bitte verifizieren Sie Ihre E-Mail-Adresse
		adminMail =
		adminName = Admin
		adminSubscribeSubject = Neue Newsletter-Anmeldung
		adminUnsubscribeSubject = Neue Newsletter-Abmeldung
	}
	overrideFlexformSettingsIfEmpty = subscribeUid,subscribeVerifyUid,unsubscribeUid,unsubscribeVerifyUid,gdprUid
  }
}

[globalVar = GP:L = 1]
plugin.tx_fpnewsletter_pi1.settings.company = Your company
plugin.tx_fpnewsletter_pi1.settings.gender.please = Please select your gender
plugin.tx_fpnewsletter_pi1.settings.gender.mr = Mr.
plugin.tx_fpnewsletter_pi1.settings.gender.mrs = Mrs.
plugin.tx_fpnewsletter_pi1.settings.email.senderMail = example@test.com
plugin.tx_fpnewsletter_pi1.settings.email.senderName = Sender-name
plugin.tx_fpnewsletter_pi1.settings.email.subscribeVerifySubject = Please verify your email
plugin.tx_fpnewsletter_pi1.settings.email.unsubscribeVerifySubject = Please verify your email
plugin.tx_fpnewsletter_pi1.settings.email.adminSubscribeSubject = New newsletter-subscription
plugin.tx_fpnewsletter_pi1.settings.email.adminUnsubscribeSubject = New newsletter-unsubscription
[end]