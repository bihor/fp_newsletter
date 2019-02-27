<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Fixpunkt.FpNewsletter',
            'Pi1',
            [
                'Log' => 'new, create, subscribeExt, unsubscribe, unsubscribeDM, delete, verify, verifyUnsubscribe, list'
            ],
            // non-cacheable actions
            [
                'Log' => 'new, create, subscribeExt, unsubscribe, unsubscribeDM, delete, verify, verifyUnsubscribe'
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
    },
    $_EXTKEY
);
