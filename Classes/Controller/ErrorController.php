<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Thomas "Thasmo" Deinhamer <thasmo@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Error controller
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_HypeError_Controller_ErrorController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * Initializes the view before invoking an action method.
	 *
	 * @param Tx_Extbase_View_ViewInterface $view The view to be initialized.
	 * @return void
	 */
	public function initializeView(Tx_Extbase_MVC_View_ViewInterface $view) {
		$view->assign('settings', $this->settings);
	}

	/**
	 * Index action for this controller.
	 *
	 * @return void
	 */
	public function indexAction() {

		# Send 404 header if called directly
		if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'TYPO3') {
			header($GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader']);
		}

		# Determine the requested address
		$requestedAddress = ($referrer = t3lib_div::getIndpEnv('HTTP_REFERER'))
			? $referrer
			: t3lib_div::getIndpEnv('REQUEST_URI');

		# Set requested address
		$this->view->assign('requestedAddress', $requestedAddress);
	}
}
?>