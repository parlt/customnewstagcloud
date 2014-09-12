<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'Customnewstagcloud',
    'Custom tt_news Tagcloud'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Custom tt_news Tagcloud');


/*
 * \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tt_news_cat', 'EXT:customnewstagcloud/Resources/Private/Language/locallang_csh_tt_news_cat.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tt_news_cat');
$TCA['tt_news_cat'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:customnewstagcloud/Resources/Private/Language/locallang_db.xlf:tt_news_cat',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		//'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'uid,pid,hidden,starttime,title,title_lang_ol,single_pid,parent_category,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/NewsCategory.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tt_news_cat.gif'
	),
);
*/
$pluginSignature = str_replace('_', '', $_EXTKEY) . '_customnewstagcloud';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_customnewstagcloud.xml');

?>