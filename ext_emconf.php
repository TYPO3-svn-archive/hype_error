<?php

########################################################################
# Extension Manager/Repository config file for ext "hype_error".
#
# Auto generated 28-05-2011 01:01
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Hype Error',
	'description' => 'Provides the functionality to define a 404 error page per domain with error logging and customizable e-mail notification.',
	'category' => 'fe',
	'shy' => 0,
	'version' => '0.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'alpha',
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
			'typo3' => '4.5.0-4.5.99',
			'extbase' => '1.3.0-1.3.99',
			'fluid' => '1.3.0-1.3.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:15:{s:21:"ext_conf_template.txt";s:4:"e71c";s:12:"ext_icon.gif";s:4:"d2e6";s:17:"ext_localconf.php";s:4:"67f1";s:14:"ext_tables.php";s:4:"e0e9";s:14:"ext_tables.sql";s:4:"c240";s:10:"readme.txt";s:4:"a455";s:38:"Classes/Controller/ErrorController.php";s:4:"e879";s:26:"Classes/Hook/ErrorHook.php";s:4:"5e1b";s:42:"Configuration/FlexForms/error.flexform.xml";s:4:"1cf0";s:25:"Configuration/TCA/tca.php";s:4:"6872";s:33:"Configuration/TCA/Icons/error.png";s:4:"3545";s:40:"Resources/Private/Language/locallang.xml";s:4:"1bbf";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"312e";s:36:"Resources/Private/Layouts/Error.html";s:4:"3f69";s:44:"Resources/Private/Templates/Error/Index.html";s:4:"7ef1";}',
	'suggests' => array(
	),
);

?>