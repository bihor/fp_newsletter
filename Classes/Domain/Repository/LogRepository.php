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
	 * @param	string $email
	 * @param	integer	$pid
	 */
	function getFromTTAddress($email, $pid)
	{
		$dbuid = 0;
		/*$pids = $this->getStoragePids();
		$pid = intval($pids[0]);*/
		$where = "email='" . $GLOBALS['TYPO3_DB']->quoteStr($email, 'tt_address') . "' AND pid=" . intval($pid);
		$where .= $GLOBALS['TSFE']->sys_page->enableFields('tt_address');
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid',
				'tt_address',
				$where);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($result) > 0) {
			$tempData = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
			$dbuid = $tempData['uid'];
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		return $dbuid;
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