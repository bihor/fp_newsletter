<?php
/**
 * Register plugins, flexform and remove unused fields
 */
foreach (['new', 'form', 'subscribeext', 'verify', 'editemail', 'edit', 'unsubscribe', 'unsubscribedm', 'unsubscribelux', 'verifyunsubscribe', 'resend', 'list'] as $plugin) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'FpNewsletter',
        ucfirst($plugin),
        'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.' . $plugin,
        'EXT:fp_newsletter/Resources/Public/Icons/fp_newsletter-plugin.png',
        'fp_newsletter'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['fpnewsletter_' . $plugin] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'fpnewsletter_' . $plugin,
        'FILE:EXT:fp_newsletter/Configuration/FlexForms/flexform_pi1.xml'
    );

    // $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['plainfaq_' . $plugin] = 'layout,select_key,pages,recursive';
}