<?php
// Einbindung Flexform 
$pluginSignature = 'fpnewsletter_pi1';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginSignature, 'FILE:EXT:fp_newsletter/Configuration/FlexForms/flexform_pi1.xml' );
?>