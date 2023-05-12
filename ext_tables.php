<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function()
    {
        $versionInformation = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Information\Typo3Version::class);
        if ($versionInformation->getMajorVersion() < 12) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_fpnewsletter_domain_model_log');
        }
    }
);
