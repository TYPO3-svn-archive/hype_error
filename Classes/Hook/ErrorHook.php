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
 * Error hook
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_HypeError_Hook_ErrorHook extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var array
	 */
	protected $domain = array();

	/**
	 *
	 */
	public function __construct() {

		# load settings
		$this->settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['hype_error']);

		# load domain
		$domain = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
			'*',
			'sys_domain',
			'domainName=\'' . t3lib_div::getIndpEnv('HTTP_HOST') . '\' AND redirectTo = \'\' AND hidden = 0'
		);

		if($domain) {
			$this->domain = $domain;
		}
	}

	/**
	 * Process action for this controller.
	 *
	 * @return void
	 */
	public function process($parameters, $frontend) {

		# override header
		if($this->settings['overrideHeader']) {
			header($GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader']);
		}

		# determine storage page
		$storagePageUid = ($this->domain['pid'] && $this->settings['useDomainPid'])
			? $this->domain['pid']
			: 0;

		# logging data
		$fields = array(
			'domain' => ($this->domain['uid']) ? $this->domain['uid'] : 0,
			'path' => $parameters['currentUrl'],
			'host' => t3lib_div::getIndpEnv('HTTP_HOST'),
			'url' => t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'),
			'referrer' => t3lib_div::getIndpEnv('HTTP_REFERER'),
			'user_agent' => t3lib_div::getIndpEnv('HTTP_USER_AGENT'),
			'ip_address' => t3lib_div::getIndpEnv('REMOTE_ADDR'),
			'reason' => $parameters['reasonText'],
			'count' => 1,
			'crdate' => time(),
			'tstamp' => time(),
			'cruser_id' => 0,
			'pid' => $storagePageUid,
		);

		# log error
		if(
			($this->settings['logErrors'] && !$this->settings['logWithReferrerOnly']) ||
			($this->settings['logErrors'] && $this->settings['logWithReferrerOnly'] && $fields['referrer']))
		{
			# find existing log entry
			$where = 'host = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($fields['host'], 'tx_hypeerror_errorlog') . ' AND path = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($fields['path'], 'tx_hypeerror_errorlog');
			$record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tx_hypeerror_errorlog', $where);

			# update record
			if($record) {

				# merge new values
				$fields = array_merge(
					$fields,
					array(
						'referrer' => ($fields['referrer']) ? $fields['referrer'] : $record['referrer'],
						'count' => ++$record['count'],
						'crdate' => $record['crdate']
					)
				);

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_hypeerror_errorlog', $where, $fields);

			# insert record
			} else {
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_hypeerror_errorlog', $fields);
			}

			# send notification
			if($this->settings['sendNotification'] && $record) {
				if(
					# send on threshold
					($record['count'] == $this->settings['notificationThreshold']) ||

					# send on every interval
					($record['count'] >= $this->settings['notificationThreshold'] &&
					($record['count'] + $this->settings['notificationThreshold']) % $this->settings['notificationInterval'] == 0)
				) {
					$message = '';
					foreach($fields as $key => $value) {
						$message .= $key . ': ' . $value . chr(10);
					}

					t3lib_div::sysLog($message, 'hype_error', $this->settings['severityLevel']);
				}
			}
		}

		# default fallback
		if(!$this->domain || !$this->domain['tx_hypeerror_error_page']) {
			return $frontend->pageErrorHandler(1, $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader'], $parameters['reason']);
		}

		# build request address
		$address = t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST') . '/?id=' . urlencode((int)$this->domain['tx_hypeerror_error_page']);

		# set request headers
		$headers = array(
			'User-Agent: ' . t3lib_div::getIndpEnv('HTTP_USER_AGENT'),
			'Referer: ' . t3lib_div::getIndpEnv('REQUEST_URI'),
			'X-Requested-With: TYPO3'
		);

		# page contents
		$output = t3lib_div::getUrl($address, 0, $headers);

		# fallback content
		if(!$output) {
			$output = $frontend->pageErrorHandler(1, $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader'], $parameters['reason']);
		}

		# return page
		return $output;
	}
}

class user_HypeError_Hook_ErrorHook extends Tx_HypeError_Hook_ErrorHook{};

?>