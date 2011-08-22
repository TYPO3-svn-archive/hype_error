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
class Tx_HypeError_Hook_ErrorHook {

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @var array
	 */
	protected $domain = array();

	/**
	 * @var array
	 */
	protected $request;

	/**
	 * Constructor
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

		# set request data
		$url = parse_url(t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'));

		$this->request = array(
			'host' => t3lib_div::getIndpEnv('HTTP_HOST'),
			'url' => t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'),
			'path' => $url['path'],
			'referrer' => t3lib_div::getIndpEnv('HTTP_REFERER'),
			'user_agent' => t3lib_div::getIndpEnv('HTTP_USER_AGENT'),
			'ip_address' => t3lib_div::getIndpEnv('REMOTE_ADDR'),

			'domain' => ($this->domain['uid']) ? $this->domain['uid'] : 0,
			'count' => 1,
			'crdate' => time(),
			'tstamp' => time(),
			'cruser_id' => 0,
		);
	}

	/**
	 * Process action for this controller.
	 *
	 * @param $parameters
	 * @param $frontend
	 * @return void
	 */
	public function process($parameters, $frontend) {

		# add request data
		$this->request['address'] = $parameters['currentUrl'];
		$this->request['reason'] = $parameters['reasonText'];

		# determine storage page
		$this->request['pid'] = ($this->domain['pid'] && $this->settings['useDomainPid'])
			? $this->domain['pid']
			: 0;

		# log error
		if(
			($this->settings['logErrors'] && !$this->settings['logWithReferrerOnly']) ||
			($this->settings['logErrors'] && $this->settings['logWithReferrerOnly'] && $this->request['referrer']))
		{
			# find existing log entry
			$where = 'host = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($this->request['host'], 'tx_hypeerror_errorlog') . ' AND deleted = 0';
			$where .= ($this->settings['groupByPathOnly'])
				? ' AND path = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($this->request['path'], 'tx_hypeerror_errorlog')
				: ' AND address = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($this->request['address'], 'tx_hypeerror_errorlog');

			$record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tx_hypeerror_errorlog', $where);

			# update record
			if($record && !$record['hidden']) {

				# merge new values
				$this->request = array_merge(
					$this->request,
					array(
						'referrer' => ($this->request['referrer']) ? $this->request['referrer'] : $record['referrer'],
						'count' => ++$record['count'],
						'crdate' => $record['crdate']
					)
				);

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_hypeerror_errorlog', $where, $this->request);

			# insert record
			} else if(!$record) {
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_hypeerror_errorlog', $this->request);
				$record = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tx_hypeerror_errorlog', $where);
				unset($where);
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
					$this->sendNotification();
				}
			}
		}

		# override header
		if($this->settings['overrideHeader']) {
			header($GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader']);
		}

		# default fallback
		if(!$this->domain || !$this->domain['tx_hypeerror_error_page']) {
			return $frontend->pageErrorHandler(1, $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader'], $parameters['reason']);
		}

		# get page uid
		$addressPageUid = urlencode((int)$this->domain['tx_hypeerror_error_page']);

		# get language uid
		$addressLanguageUid = 0;
		if($_GET['tx_hypeerror_language_uid']) {
			$addressLanguageUid = urlencode((int)$_GET['tx_hypeerror_language_uid']);
		} else if($_GET['L']) {
			$addressLanguageUid = urlencode((int)$_GET['L']);
		}

		# build error page url
		$address = t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST') . '/?id=' . $addressPageUid . '&L=' . $addressLanguageUid;

		# set request headers
		$headers = array(
			'User-Agent: ' . $this->request['user_agent'],
			'Referer: ' . $this->request['referrer'],
			'X-Requested-From: ' . t3lib_div::getIndpEnv('REQUEST_URI'),
			'X-Requested-With: TYPO3/' . TYPO3_version
		);

		# get page contents
		$output = t3lib_div::getUrl($address, 0, $headers);

		# fallback content
		if(!$output) {
			$output = $frontend->pageErrorHandler(1, $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader'], $parameters['reason']);
		}

		# return page
		return $output;
	}

	/**
	 * Sends notifications on errors
	 */
	public function sendNotification() {

		# create message
		$mail = t3lib_div::makeInstance('t3lib_mail_Message');

		# set from
		$mail->setFrom(array($GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] => $GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName']));

		# set recipient
		$mail->setTo($this->settings['notificationEmailAddress']);
		$mail->setSubject('Error 404: ' . $this->request['host']);

		# set message
		$message = array();
		$message[] = 'Address: ' . $this->request['address'];
		$message[] = 'Path: ' . $this->request['path'];
		$message[] = 'Host: ' . $this->request['host'];
		$message[] = 'Referrer: ' . (($this->request['referrer']) ? $this->request['referrer'] : '(empty)');
		$message[] = 'User Agent: ' . (($this->request['user_agent']) ? $this->request['user_agent'] : '(empty)');
		$message[] = 'IP Address: ' . $this->request['ip_address'];
		$message[] = 'Count: ' . $this->request['count'];

		$mail->setBody(implode(chr(10), $message));

		# send the message
		$mail->send();
	}
}

class user_HypeError_Hook_ErrorHook extends Tx_HypeError_Hook_ErrorHook{};

?>