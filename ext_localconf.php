<?php

if(!defined('TYPO3_MODE'))
	die('Access denied.');



# PLUGINS

# Error
Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Error',
	array('Error' => 'index')
);



# ERROR HANDLING

# 40x
$GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling'] = 'USER_FUNCTION:EXT:' . $_EXTKEY . '/Classes/Hook/ErrorHook.php:user_HypeError_Hook_ErrorHook->process';

# 50x
$GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_handling'] = 'USER_FUNCTION:EXT:' . $_EXTKEY . '/Classes/Hook/ErrorHook.php:user_HypeError_Hook_ErrorHook->process';

?>