<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'TYPO3.' . $_EXTKEY,
    'Customnewstagcloud',
    array(
        'NewsCategory' => 'list',

    ),
    // non-cacheable actions
    array(
        'NewsCategory' => '',
    )
);

?>