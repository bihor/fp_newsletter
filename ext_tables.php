<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Fixpunkt.FpNewsletter',
            'Pi1',
            'Newsletter managment'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'Newsletter managment');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_fpnewsletter_domain_model_log', 'EXT:fp_newsletter/Resources/Private/Language/locallang_csh_tx_fpnewsletter_domain_model_log.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_fpnewsletter_domain_model_log');

    },
    $_EXTKEY
);
