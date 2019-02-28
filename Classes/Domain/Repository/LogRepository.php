<?php
namespace Fixpunkt\FpNewsletter\Domain\Repository;

/***
 *
 * This file is part of the "Newsletter managment" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Kurt Gusbeth <k.gusbeth@fixpunkt.com>, fixpunkt werbeagentur gmbh
 *
 ***/

/**
 * The repository for Logs
 */
class LogRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

	/**
	 * getFromTTAddress: find user
	 * @param	string $email: die Email-Adresse wurde schon vorher geprüft!
	 * @param	integer	$pid
	 */
	function getFromTTAddress($email, $pid)
	{
		$dbuid = 0;
		/*$pids = $this->getStoragePids();
		$pid = intval($pids[0]);*/
		$where = "email='" . $email . "' AND pid=" . intval($pid);
		$where .= $GLOBALS['TSFE']->sys_page->enableFields('tt_address');
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid', 'tt_address', $where);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) > 0) {
			$tempData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
			$dbuid = $tempData['uid'];
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		return $dbuid;
	}
	
	/**
	 * insertInTTAddress: insert user
	 * @param	\Fixpunkt\FpNewsletter\Domain\Model\Log	$address User
	 * @param	integer	$mode HTML-mode
	 */
	function insertInTtAddress($address, $mode) {
		$timestamp = time();
		if ($address->getGender() == 1) $gender = 'f';
		elseif ($address->getGender() == 2) $gender = 'm';
		else $gender = '';
		// crdate fehlt in älteren Versionen!
		$insert = ['pid' => intval($address->getPid()),
			'tstamp' => $timestamp,
			'crdate' => $timestamp,
			'title' => $address->getTitle(),
			'first_name' => $address->getFirstname(),
			'last_name' => $address->getLastname(),
			'name' => trim($address->getFirstname() . ' ' . $address->getLastname()),
			'email' => $address->getEmail()];
		if ($mode != -1) {
			$insert['module_sys_dmail_html'] = $mode;
		}
		if ($gender) {
			$insert['gender'] = $gender;
		}
		return $GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_address', $insert);
	}
	
	/**
	 * deleteInTTAddress: delete user
	 * @param	integer	$uid
	 * @param	integer	$mode
	 */
	function deleteInTtAddress($uid, $mode) {
	    if ($mode == 2) {
	        $GLOBALS['TYPO3_DB']->exec_DELETEquery('tt_address', 'uid=' . $uid);
	    } else {
	        $update = array('deleted' => 1, 'tstamp' => time());
	        $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_address', 'uid=' . $uid, $update);
	    }
	}

	/**
	 * Get the PIDs
	 *
	 * @return array
	 */
	public function getStoragePids() {
		$query = $this->createQuery();
		return $query->getQuerySettings()->getStoragePageIds();
	}
}