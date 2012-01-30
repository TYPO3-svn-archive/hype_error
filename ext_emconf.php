<?php

########################################################################
# Extension Manager/Repository config file for ext "hype_error".
#
# Auto generated 30-01-2012 22:52
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Hype Error',
	'description' => 'Provides the functionality to define a 404 error page per domain with error logging and customizable e-mail notifications.',
	'category' => 'fe',
	'shy' => 0,
	'version' => '1.0.0',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Thomas "Thasmo" Deinhamer',
	'author_email' => 'thasmo@gmail.com',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.5.0-4.6.99',
			'extbase' => '1.3.0-1.4.99',
			'fluid' => '1.3.0-1.4.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:19:{s:21:"ext_conf_template.txt";s:4:"2438";s:12:"ext_icon.gif";s:4:"0e14";s:17:"ext_localconf.php";s:4:"9873";s:14:"ext_tables.php";s:4:"dd26";s:14:"ext_tables.sql";s:4:"118b";s:10:"readme.txt";s:4:"a455";s:38:"Classes/Controller/ErrorController.php";s:4:"0883";s:26:"Classes/Hook/ErrorHook.php";s:4:"4787";s:38:"Classes/XClass/class.ux_tx_realurl.php";s:4:"79ee";s:42:"Configuration/FlexForms/error.flexform.xml";s:4:"4199";s:25:"Configuration/TCA/tca.php";s:4:"f5c4";s:33:"Configuration/TCA/Icons/error.png";s:4:"3545";s:40:"Resources/Private/Language/locallang.xml";s:4:"189d";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"017b";s:36:"Resources/Private/Layouts/Error.html";s:4:"3f69";s:44:"Resources/Private/Templates/Error/Index.html";s:4:"ae3e";s:14:"doc/manual.pdf";s:4:"b4b5";s:14:"doc/manual.sxw";s:4:"b61a";s:14:"doc/manual.txt";s:4:"cd69";}',
	'suggests' => array(
	),
);

?>