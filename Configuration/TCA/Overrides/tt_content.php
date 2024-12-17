<?php
/**
 * Register plugins, flexform and remove unused fields
 */
foreach (['new', 'form', 'subscribeext', 'verify', 'editemail', 'edit', 'unsubscribe', 'unsubscribelux', 'unsubscribemail', 'verifyunsubscribe', 'resend', 'list'] as $plugin) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'FpNewsletter',
        ucfirst($plugin),
        'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:template.' . $plugin,
        'fp_newsletter-plugin-pi1',
        'fp_newsletter',
        'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fp_newsletter_domain_model_pi1.description'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['fpnewsletter_' . $plugin] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'fpnewsletter_' . $plugin,
        'FILE:EXT:fp_newsletter/Configuration/FlexForms/flexform_pi1.xml'
    );

    // $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['plainfaq_' . $plugin] = 'layout,select_key,pages,recursive';
}
