<?php
namespace Fixpunkt\FpNewsletter\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***
 *
 * This file is part of the "Newsletter managment" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Kurt Gusbeth <k.gusbeth@fixpunkt.com>, fixpunkt werbeagentur gmbh
 *
 ***/

/**
 * The repository for Logs
 */
class LogRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

	/**
	 * getFromTTAddress: find user ID
	 * @param	string $email: die Email-Adresse wurde schon vorher geprüft!
	 * @param	integer	$pid
	 * @return integer
	 */
	function getFromTTAddress($email, $pid)
	{
		$dbuid = 0;
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
		$statement = $queryBuilder
					->select('uid')
					->from('tt_address')
					->where(
						$queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT))
					)
					->andWhere(
						$queryBuilder->expr()->eq('email', $queryBuilder->createNamedParameter($email))
					)
					->execute();
		while ($row = $statement->fetch()) {
			$dbuid = intval($row['uid']);
		}		
		return $dbuid;
	}

    /**
     * getFromTTAddress: find user ID
     * @param	string $email: die Email-Adresse wurde schon vorher geprüft!
     * @param	array	$pidsArray
     * @return integer
     */
    function getFromTtAddressCheckAllFolders($email, $pidsArray)
    {
        $dbuid = 0;
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
        $statement = $queryBuilder
            ->select('uid')
            ->from('tt_address')
            ->where(
                $queryBuilder->expr()->in('pid', $queryBuilder->createNamedParameter($pidsArray, Connection::PARAM_INT_ARRAY))
            )
            ->andWhere(
                $queryBuilder->expr()->eq('email', $queryBuilder->createNamedParameter($email))
            )
            ->execute();
        while ($row = $statement->fetch()) {
            $dbuid = intval($row['uid']);
            break;
        }
        return $dbuid;
    }

    /**
	 * getUserFromTTAddress: find user array
	 * @param	integer $uid: UID des User
	 * @return	array
	 */
	function getUserFromTTAddress($uid)
	{
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
		$statement = $queryBuilder
		->select('*')
		->from('tt_address')
		->where(
			$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
		)
		->execute();
		//echo $queryBuilder->getSQL();
		return $statement->fetch();
	}
	
	/**
	 * insertInTTAddress: insert user
	 * @param	\Fixpunkt\FpNewsletter\Domain\Model\Log	$address User
	 * @param	integer	$mode 		HTML-mode
	 * @param	array	$dmCatArr	direct_mail categories
	 */
	function insertInTtAddress($address, $mode, $dmCatArr = []) {
		$timestamp = time();
		if ($address->getGender() == 1) $gender = 'f';
		elseif ($address->getGender() == 2) $gender = 'm';
		elseif ($address->getGender() == 3) $gender = 'v';
		else $gender = '';
		// PS: crdate fehlt in älteren Versionen!
		// Die Sprache übernehmen wir ab sofort 1:1
		$sys_language_uid = $address->get_languageUid();
		$insert =  ['pid' => intval($address->getPid()),
			'tstamp' => $timestamp,
			'crdate' => $timestamp,
		    'sys_language_uid' => $sys_language_uid,
			'title' => $address->getTitle(),
			'first_name' => $address->getFirstname(),
			'last_name' => $address->getLastname(),
			'name' => trim($address->getFirstname() . ' ' . $address->getLastname()),
			'address' => $address->getAddress(),
			'zip' => $address->getZip(),
			'city' => $address->getCity(),
			'region' => $address->getRegion(),
			'country' => $address->getCountry(),
			'phone' => $address->getPhone(),
			'mobile' => $address->getMobile(),
			'fax' => $address->getFax(),
			'position' => $address->getPosition(),
			'company' => $address->getCompany(),
			'email' => $address->getEmail()];
		if ($mode != -1) {
			$insert['module_sys_dmail_html'] = $mode;
		}
		if ($address->getCategories()) {
			// Priorität haben die Kategorien aus dem Formular/Log-Eintrag
			$dmCatArr = explode(',', $address->getCategories());	
		}
		if (is_array($dmCatArr) && count($dmCatArr)>0) {
			$insert['module_sys_dmail_category'] = count($dmCatArr);
		}
		if ($gender) {
			$insert['gender'] = $gender;
		}
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
		$queryBuilder
			->insert('tt_address')
			->values($insert)
			->execute();
		$tableUid = $queryBuilder->getConnection()->lastInsertId();
		if (is_array($dmCatArr) && count($dmCatArr)>0) {
			$count = 0;
			foreach ($dmCatArr as $uid) {
				if (is_numeric($uid)) {
					// set the categories to the mm table of direct_mail
					$count++;
					$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_dmail_ttaddress_category_mm');
					$queryBuilder
						->insert('sys_dmail_ttaddress_category_mm')
						->values([
							'uid_local' => intval($tableUid),
							'uid_foreign' => intval($uid),
							'tablenames' => '',		// unklar
							'sorting' => $count
						])
						->execute();
				}
			}
		}
		return $tableUid;
	}
	
	/**
	 * deleteInTTAddress: delete user
	 * @param	integer	$uid		tt_address uid
	 * @param	integer	$mode		Lösch-Modus: 1: update, 2: löschen
	 * @param	array	$dmCatArr	direct_mail categories
	 */
	function deleteInTtAddress($uid, $mode, $dmCatArr = []) {
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
	    if ($mode == 2) {
        	$queryBuilder
        		->delete('tt_address')
	        	->where(
	        		$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
        		)
	       		->execute();
	    } else {
	        $queryBuilder
	       		->update('tt_address')
	       		->where(
	        		$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
	        	)
	        	->set('deleted', '1')
	        	->set('tstamp', time())
	        	->execute();
	    }
	    if (is_array($dmCatArr) && count($dmCatArr)>0) {
	    	// alle Kategorie-Relationen löschen
	    	$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_dmail_ttaddress_category_mm');
	    	$queryBuilder
	    		->delete('sys_dmail_ttaddress_category_mm')
	    		->where(
	    			$queryBuilder->expr()->eq('uid_local', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
	    		)
	    		->execute();
	    }
	}
	
	/**
	 * Find an entry with sys_language_uid > 0
	 * https://forge.typo3.org/issues/86405
	 * 
	 * @param	integer	$uid
	 * @param	integer	$sys_language_uid
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findAnotherByUid($uid, $sys_language_uid) {
	    $query = $this->createQuery();
	    $query->getQuerySettings()->setRespectSysLanguage(FALSE);
	    //$query->getQuerySettings()->setSysLanguageUid($sys_language_uid);
	    $query->matching($query->logicalAnd(
	        $query->equals('uid', intval($uid)),
	        $query->equals("sys_language_uid", intval($sys_language_uid))
	    ));
	    return $query->execute()->getFirst();
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