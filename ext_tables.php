<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TCA']['sys_file_metadata']['columns']['thumbnail'] = array(
	'exclude' => 1,
	'label' => 'LLL:EXT:extbase_fal/Resources/Private/Language/locallang_db.xml:sys_file.thumbnail',
	'config' => array (
		'type' => 'group',
		'internal_type' => 'db',
		'allowed' => 'sys_file',
		'foreign_table' => 'sys_file', // needed for extbase
		'size' => 1,
		'minitems' => 0,
		'maxitems' => 1,
		'softref' => 'media',
		'show_thumbs' => '1',
		'appearance' => array (
			'elementBrowserType' => 'file',
			'elementBrowserAllowed' => 'jpg,jpeg,png,bmp'
		),
		'items' => array (
			array('', '')
		),
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_metadata', 'thumbnail', '', 'after:title');
