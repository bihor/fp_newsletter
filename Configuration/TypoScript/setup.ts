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
  }
  settings {
	table = tt_address
	newsletterExtension =
	optionalFields = gender,firstname,lastname
	optionalFieldsRequired =
    additionalTtAddressFields =
	doubleOptOut = 1
	disableErrorMsg = 0
	enableUnsubscribeForm = 0
	enableUnsubscribeGdprAsHidden = 0
	enableEditForm = 0
	subscribeUid = 1
	subscribeMessageUid =
	subscribeVerifyUid =
	subscribeVerifyMessageUid =
	unsubscribeUid = 1
	unsubscribeMessageUid =
	unsubscribeVerifyUid =
	unsubscribeVerifyMessageUid =
	resendVerificationUid =
	editUid =
	gdprUid = 1
	daysExpire = 2
	unsubscribeMode = 0
	searchPidMode = 0
	deleteMode = 1
	languageMode = 0
    categoryMode = 1
	categoryOrderBy = title
	categoryOrGroup =
	html = 1
	password = random
	authCodeFields =
	reCAPTCHA_site_key =
	reCAPTCHA_secret_key =
	mathCAPTCHA = 0
	honeypot = 0
    checkForRequiredExtensions = 1
	preferXlfFile = 0
	company = Ihre Firma
	gender {
	  please = Bitte auswählen
	  mr = Herr
	  mrs = Frau
	  divers = Divers
	}
	parameters {
	  active =
	  email =
	  authcode =
	}
	email {
		senderMail = info@example.org
		senderName = Absender-Name
		adminMail =
		adminName = Admin
		adminMailBeforeVerification = 0
		enableConfirmationMails = 0
		dontAppendL = 1
		adminSubscribeSubject = Neue Newsletter-Anmeldung
		adminUnsubscribeSubject = Neue Newsletter-Abmeldung
		subscribeVerifySubject = Bitte verifizieren Sie Ihre E-Mail-Adresse
		unsubscribeVerifySubject = Bitte verifizieren Sie Ihre E-Mail-Adresse
		subscribedSubject = Bestätigung Newsletter-Anmeldung
		unsubscribedSubject = Bestätigung Newsletter-Abmeldung
		editSubject = Ändern Sie Ihr Newsletter-Abonnement
	}
	overrideFlexformSettingsIfEmpty = subscribeUid,subscribeVerifyUid,unsubscribeUid,unsubscribeVerifyUid,editUid,gdprUid,parameters.active,parameters.email,categoryOrGroup
	debug = 0
  }
}

module.tx_dashboard.view {
	layoutRootPaths {
		43 = EXT:fp_newsletter/Resources/Private/Layouts/
	}
	templateRootPaths {
		43 = EXT:fp_newsletter/Resources/Private/Templates/
	}
}
