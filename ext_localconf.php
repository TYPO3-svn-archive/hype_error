<?php

if(!defined('TYPO3_MODE'))
	die('Access denied.');



# PLUGINS

# Error
Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Error',
	array('Error' => 'index'),
	array('Error' => 'index')
);



# HOOKS

# Frontend
if(TYPO3_MODE == 'FE') {

	# Error 40x
	$GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling'] = 'USER_FUNCTION:EXT:' . $_EXTKEY . '/Classes/Hook/ErrorHook.php:user_HypeError_Hook_ErrorHook->process';

	# Error 50x
	$GLOBALS['TYPO3_CONF_VARS']['FE']['pageUnavailable_handling'] = 'USER_FUNCTION:EXT:' . $_EXTKEY . '/Classes/Hook/ErrorHook.php:user_HypeError_Hook_ErrorHook->process';
}



# XCLASS

# Frontend
if(TYPO3_MODE == 'FE') {

	# RealURL
	if(t3lib_extMgm::isLoaded('realurl')) {
		$GLOBALS['TYPO3_CONF_VARS']['FE']['XCLASS']['ext/realurl/class.tx_realurl.php'] = t3lib_extMgm::extPath('hype_error', 'Classes/XClass/class.ux_tx_realurl.php');
	}
}

?>