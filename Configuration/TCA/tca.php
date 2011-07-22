<?php

if(!defined('TYPO3_MODE'))
	die('Access denied.');


# Error
$TCA['tx_hypeerror_errorlog'] = array(
	'ctrl' => $TCA['tx_hypeerror_errorlog']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'domain, url, path, host, referrer, user_agent, ip_address, reason, fe_group, editlock'
	),
	'feInterface' => $TCA['tx_hypeerror_errorlog']['feInterface'],
	'columns' => array(
		'pid' => array(
			'type' => 'passthrough'
		),
		'deleted' => array(
			'type' => 'passthrough'
		),
		'tstamp' => array(
			'type' => 'passthrough'
		),
		'crdate' => array(
			'type' => 'passthrough'
		),
		'cruser_id' => array(
			'type' => 'select',
			'foreign_table' => 'be_users',
		),

		'domain' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.domain',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'prepend_tname' => FALSE,
				'allowed' => 'sys_domain',
				'foreign_table' => 'sys_domain',
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
		'url' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.url',
			'config' => array(
				'type' => 'input',
			),
		),
		'path' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.path',
			'config' => array(
				'type' => 'input',
			),
		),
		'host' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.host',
			'config' => array(
				'type' => 'input',
			),
		),
		'referrer' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.referrer',
			'config' => array(
				'type' => 'input',
			),
		),
		'user_agent' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.user_agent',
			'config' => array(
				'type' => 'input',
			),
		),
		'ip_address' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.ip_address',
			'config' => array(
				'type' => 'input',
			),
		),
		'reason' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.reason',
			'config' => array(
				'type' => 'text',
			),
		),
		'count' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror_errorlog.count',
			'config' => array(
				'type' => 'input',
				'size' => 5,
			),
		),
		'fe_group' => array(
			'exclude' => 1,
			'label'	=> 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'	=> array(
				'type'	=> 'select',
				'size' => 5,
				'maxitems' => 99,
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--'),
				),
				'exclusiveKeys' => '-1,-2',
				'foreign_table' => 'fe_groups'
			),
		),
		'editlock' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_tca.xml:editlock',
			'config' => array(
				'type' => 'check'
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => '
			domain, path, host, url, referrer,
			--palette--;LLL:EXT:hype_error/Resources/Private/Language/locallang_db.xml:tx_hypeerror.palette.client;client,
			reason, count'),
	),
	'palettes' => array(
		'client' => array(
			'showitem' => 'user_agent, --linebreak--, ip_address',
			'canNotCollapse' => TRUE,
		),
	),
);
?>