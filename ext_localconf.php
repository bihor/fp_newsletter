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
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'editEmail, edit, update'
            ],
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'editEmail, edit, update'
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
