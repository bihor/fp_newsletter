<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'FpNewsletter',
            'Pi1',
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'new, create, resend, subscribeExt, unsubscribe, unsubscribeDM, delete, verify, verifyUnsubscribe, list'
            ],
            // non-cacheable actions
            [
                \Fixpunkt\FpNewsletter\Controller\LogController::class => 'new, create, resend, subscribeExt, unsubscribe, unsubscribeDM, delete, verify, verifyUnsubscribe'
            ]
        );

    	// wizards
    	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    		'mod {
    			wizards.newContentElement.wizardItems.plugins {
    				elements {
    					fpnl {
    						iconIdentifier = fp_newsletter-plugin-pi1
    						title = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1
    						description = LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description
    						tt_content_defValues {
    							CType = list
    							list_type = fpnewsletter_pi1
    						}
    					}
    				}
    				show = *
    			}
    	   }'
    	);
    	
    	$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    	
    	$iconRegistry->registerIcon(
    	    'fp_newsletter-plugin-pi1',
    	    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    	    ['source' => 'EXT:fp_newsletter/Resources/Public/Icons/fp_newsletter-plugin.png']
    	);
    }
);

if (empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\\CMS\\Scheduler\\Task\\TableGarbageCollectionTask']['options']['tables']['tx_fpnewsletter_domain_model_log'])) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\\CMS\\Scheduler\\Task\\TableGarbageCollectionTask']['options']['tables']['tx_fpnewsletter_domain_model_log'] = array(
		'dateField' => 'tstamp',
		'expirePeriod' => '180'
	);
}
