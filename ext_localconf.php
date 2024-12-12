<?php
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

call_user_func(
    function()
	{
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'New',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'new, create, verify'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'new, create, verify'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Form',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'form'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => ''
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Subscribeext',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'subscribeExt, create, verify'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'subscribeExt, create, verify'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Verify',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'verify'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'verify'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Editemail',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'editEmail'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'editEmail'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Edit',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'edit, update, editEmail'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'edit, update, editEmail'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Unsubscribe',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'unsubscribe, delete, verifyUnsubscribe'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'unsubscribe, delete, verifyUnsubscribe'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Unsubscribelux',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'unsubscribeLux, unsubscribe, delete, verifyUnsubscribe'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'unsubscribeLux, unsubscribe, delete, verifyUnsubscribe'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Unsubscribemail',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'unsubscribeMail, unsubscribe, delete, verifyUnsubscribe'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'unsubscribeMail, unsubscribe, delete, verifyUnsubscribe'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Verifyunsubscribe',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'verifyUnsubscribe'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'verifyUnsubscribe'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Resend',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'resend, verify'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'resend, verify'
            ]
        );
        ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'List',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'list'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => ''
            ]
        );

        // wizards
        if ((new Typo3Version())->getMajorVersion() < 13) {
            // @extensionScannerIgnoreLine
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
'mod {
    wizards.newContentElement.wizardItems.fpnl {
        header = fp_newsletter
        elements {
            fpnewsletter_new {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.new
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_new
                }
            }
            fpnewsletter_form {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.form
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_form
                }
            }
            fpnewsletter_subscribeext {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.subscribeext
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_subscribeext
                }
            }
            fpnewsletter_verify {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.verify
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_verify
                }
            }
            fpnewsletter_editemail {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.editemail
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_editemail
                }
            }
            fpnewsletter_edit {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.edit
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_edit
                }
            }
            fpnewsletter_unsubscribe {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.unsubscribe
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_unsubscribe
                }
            }
            fpnewsletter_unsubscribelux {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.unsubscribelux
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_unsubscribelux
                }
            }
            fpnewsletter_unsubscribemail {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.unsubscribemail
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_unsubscribemail
                }
            }
            fpnewsletter_verifyunsubscribe {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.verifyunsubscribe
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_verifyunsubscribe
                }
            }
            fpnewsletter_resend {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.resend
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_resend
                }
            }
            fpnewsletter_list {
                iconIdentifier = fp_newsletter-plugin-pi1
                title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.list
                description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
                tt_content_defValues {
                    CType = list
                    list_type = fpnewsletter_list
                }
            }
        }
        show = *
    }
}'
            );
        }

    	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    	$iconRegistry->registerIcon(
    	    'fp_newsletter-plugin-pi1',
    	    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    	    ['source' => 'EXT:fp_newsletter/Resources/Public/Icons/fp_newsletter-plugin.png']
    	);

        /**
         * Fluid Namespace
         */
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['fpnl'][] = 'Fixpunkt\FpNewsletter\ViewHelpers';
    }
);

if (empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask::class]['options']['tables']['tx_fpnewsletter_domain_model_log'])) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask::class]['options']['tables']['tx_fpnewsletter_domain_model_log'] = [
		'dateField' => 'tstamp',
		'expirePeriod' => '180'
	];
}
