<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Fixpunkt.FpNewsletter',
            'Pi1',
            [
                'Log' => 'new, create, unsubscribe, unsubscribeDM, delete, verify, list'
            ],
            // non-cacheable actions
            [
                'Log' => 'new, create, unsubscribe, unsubscribeDM, delete, verify'
            ]
        );

	// wizards
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
		'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					fpnl {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($extKey) . 'Resources/Public/Icons/fp_newsletter-plugin.png
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
    },
    $_EXTKEY
);
