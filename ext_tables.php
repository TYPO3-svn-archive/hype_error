<?php

if(!defined('TYPO3_MODE'))
	die('Access denied.');



# TYPOSCRIPT

# Extension
// t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Hype Error');



# PLUGINS

# Error
Tx_Extbase_Utility_Extension::registerPlugin($_EXTKEY, 'Error', 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_hypeerror.plugin.error');



# TABLES

# Error
t3lib_extMgm::allowTableOnStandardPages('tx_hypeerror_errorlog');
t3lib_extMgm::addToInsertRecords('tx_hypeerror_errorlog');
$TCA['tx_hypeerror_errorlog'] = array(
	'ctrl' => array(
		'title'	 => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog',
		'label'	 => 'path',
		'label_alt' => 'count',
		'label_alt_force' => TRUE,
		'tstamp'	=> 'tstamp',
		'crdate'	=> 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'crdate DESC',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'fe_group' => 'fe_group',
		),
		'editlock' => 'editlock',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/tca.php',
		'iconfile'		=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Configuration/TCA/Icons/error.png',
		'dividers2tabs' => TRUE,
		'adminOnly' => TRUE,
		//'readOnly' => TRUE,
	),
);

# SysDomain
$columns = array(
	'tx_hypeerror_error_page' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:sys_domain.tx_hypeerror_error_page',
		'config' => array(
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => 'pages',
			'size' => 1,
			'maxitems' => 1,
			'wizards' => array(
				'suggest' => array(
					'type' => 'suggest',
					'default' => array(
						'searchWholePhrase' => 1,
					),
				),
			),
		),
	),
);

t3lib_div::loadTCA('sys_domain');
t3lib_extMgm::addTCAcolumns('sys_domain', $columns, 1);
t3lib_extMgm::addToAllTCAtypes('sys_domain', '--div--;404,tx_hypeerror_error_page');

?>