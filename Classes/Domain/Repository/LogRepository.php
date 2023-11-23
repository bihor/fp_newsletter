<?php
namespace Fixpunkt\FpNewsletter\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***
 *
 * This file is part of the "Newsletter management" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Kurt Gusbeth <k.gusbeth@fixpunkt.com>, fixpunkt für digitales GmbH
 *
 ***/

/**
 * The repository for Logs
 */
class LogRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * getByEmailAndPid: find user entry
     * @param   string $email: email
     * @param	array $pids: PIDs
     * @param	int $sys_language_uid: language
     * @param	int $maxDate: x days ago
     * @return	array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    function getByEmailAndPid(string $email, array $pids, int $sys_language_uid, int $maxDate)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->in('pid', $pids);
        $constraints[] = $query->equals('email', $email);
        $constraints[] = $query->equals('status', 1);
        $constraints[] = $query->greaterThan('crdate', $maxDate);
        if ($sys_language_uid > 0) {
            $query->getQuerySettings()->setRespectSysLanguage(false);
            //$query->getQuerySettings()->setSysLanguageUid($sys_language_uid);
            $constraints[] = $query->equals("sys_language_uid", $sys_language_uid);
        }
        $query->matching($query->logicalAnd(...$constraints));
        $query->setOrderings([
            'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
        ]);
        return $query->execute()->getFirst();
    }

    /**
	 * getUidFromExternal: find user ID
	 * @param	string $email die Email-Adresse wurde schon vorher geprüft!
	 * @param	mixed	$pid PID oder Liste mit PIDs
     * @param   string  $table tt_address oder fe_users
	 * @return  integer
	 */
	function getUidFromExternal($email, $pid, $table)
	{
		$dbuid = 0;
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        if (is_numeric($pid)) {
            $where = $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT));
        } else {
            $where = $queryBuilder->expr()->in('pid', $queryBuilder->createNamedParameter($pid, Connection::PARAM_INT_ARRAY));
        }
		$statement = $queryBuilder
					->select('uid')
					->from($table)
					->where(
						$where
					)
					->andWhere(
						$queryBuilder->expr()->eq('email', $queryBuilder->createNamedParameter($email))
					)
					->executeQuery();
		while ($row = $statement->fetchAssociative()) {
			$dbuid = intval($row['uid']);
            break;
		}		
		return $dbuid;
	}

    /**
     * getExternalUid: found user in tt_address or fe_users
     * @param	string $dbemail E-Mail
     * @param   int $pid PID
     * @param   string $table tt_address or fe_users
     * @param   int $searchPidMode setting
     * @return	integer
     */
    public function getExternalUid($dbemail, $pid, $table, $searchPidMode)
    {
        $dbuidext = 0;
        if ($table == 'tt_address' || $table == 'fe_users') {
            if ($searchPidMode == 1) {
                $dbuidext = $this->getUidFromExternal($dbemail, $this->getStoragePids(), $table);
            } else {
                $dbuidext = $this->getUidFromExternal($dbemail, $pid, $table);
            }
        }
        return $dbuidext;
    }

    /**
	 * getUserFromExternal: found user array
	 * @param	integer $uid UID of the user
     * @param   string $table tt_address or fe_users
	 * @return	array
	 */
	function getUserFromExternal($uid, $table)
	{
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
		$statement = $queryBuilder
		->select('*')
		->from($table)
		->where(
			$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
		)
		->executeQuery();
		//echo $queryBuilder->getSQL();
		return $statement->fetchAssociative();
	}

    /**
     * getAllCats: find all mail categories
     * @param   string $catOrderBy category order by
     * @return	array
     */
    function getAllCats($catOrderBy)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
        $statement = $queryBuilder
            ->select('uid', 'title')
            ->from('sys_category')
            ->orderBy($catOrderBy)
            ->executeQuery();
        return $statement->fetchAllAssociative();
    }

    /**
     * getOwnCats: find own mail categories
     * @return	array
     */
    function getOwnCats($uid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category_record_mm');
        $statement = $queryBuilder
            ->select('uid_local')
            ->from('sys_category_record_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter('tt_address'))
            )
            ->executeQuery();
        return $statement->fetchAllAssociative();
    }

    /**
     * getAllGroups: find all fe_groups
     * @param   string $groupsOrderBy groups order by
     * @return	array
     */
    function getAllGroups($groupsOrderBy)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_groups');
        $statement = $queryBuilder
            ->select('uid', 'title')
            ->from('fe_groups')
            ->where(
                $queryBuilder->expr()->eq('luxletter_receiver', 1)
            )
            ->orderBy($groupsOrderBy)
            ->executeQuery();
        return $statement->fetchAllAssociative();
    }

    /**
     * insertIntoMm: insert relations into sys_category_record_mm
     * @param	integer	$tableUid user-uid
     * @param	array	$dmCatArr sys_category UIDs
     */
    protected function insertIntoMm($tableUid, $dmCatArr = [])
    {
        if (is_array($dmCatArr) && count($dmCatArr)>0) {
            $count = 0;
            foreach ($dmCatArr as $uid) {
                if (is_numeric(trim($uid))) {
                    // set the categories to the mm table of sys_category
                    $count++;
                    $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category_record_mm');
                    $queryBuilder
                        ->insert('sys_category_record_mm')
                        ->values([
                            'uid_foreign' => intval($tableUid),
                            'uid_local' => intval($uid),
                            'tablenames' => 'tt_address',
                            'fieldname' => 'categories',
                            'sorting_foreign' => $count
                        ])
                        ->executeStatement();
                }
            }
        }
    }

    /**
     * deleteInMm: delete relations into sys_category_record_mm
     * @param	integer	$tableUid user-uid
     */
    protected function deleteInMm($tableUid)
    {
        // alle Kategorie-Relationen löschen
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category_record_mm');
        $queryBuilder
            ->delete('sys_category_record_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($tableUid, \PDO::PARAM_INT)),
                $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter('tt_address'))
            )
            ->executeStatement();
    }

    /**
	 * insertInTTAddress: insert user
	 * @param \Fixpunkt\FpNewsletter\Domain\Model\Log	$address User
	 * @param integer	$mode 		HTML-mode
	 * @param array	$dmCatArr	categories
     * @param string  $salutation Anrede
	 */
	function insertInTtAddress($address, $mode, $dmCatArr = [], $salutation = '')
    {
		$timestamp = time();
		if ($address->getGender() == 1) $gender = 'f';
		elseif ($address->getGender() == 2) $gender = 'm';
		elseif ($address->getGender() == 3) $gender = 'v';
		else $gender = '';
		// PS: crdate fehlt in älteren Versionen!
		// Die Sprache übernehmen wir ab sofort 1:1
		$sys_language_uid = $address->getSysLanguageUid();
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
            'www' => $address->getWww(),
			'position' => $address->getPosition(),
			'company' => $address->getCompany(),
			'email' => $address->getEmail()];
		if ($mode != -1) {
			$insert['mail_html'] = $mode;
            $insert['mail_salutation'] = $salutation;
            $insert['mail_active'] = 1;
		}
		if ($address->getCategories()) {
			// Priorität haben die Kategorien aus dem Formular/Log-Eintrag
			$dmCatArr = explode(',', $address->getCategories());
		}
        if (is_array($dmCatArr) && count($dmCatArr)>0) {
            $insert['categories'] = count($dmCatArr);
        }
		if ($gender) {
			$insert['gender'] = $gender;
		}
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
		$queryBuilder
			->insert('tt_address')
			->values($insert)
			->executeStatement();
		$tableUid = $queryBuilder->getConnection()->lastInsertId();
        if ($tableUid) {
            $this->insertIntoMm($tableUid, $dmCatArr);
        }
		return $tableUid;
	}

    /**
     * updateInTTAddress: update user in tt_address
     * @param \Fixpunkt\FpNewsletter\Domain\Model\Log	$address User
     * @param integer $mode HTML-mode
     * @param int     $tableUid   externe uid
     * @param string $salutation Anrede
     */
    function updateInTtAddress($address, $mode, $tableUid, $salutation = '')
    {
        $timestamp = time();
        if ($address->getGender() == 1) $gender = 'f';
        elseif ($address->getGender() == 2) $gender = 'm';
        elseif ($address->getGender() == 3) $gender = 'v';
        else $gender = '';
        if ($address->getCategories()) {
            // Priorität haben die Kategorien aus dem Formular/Log-Eintrag
            $dmCatArr = explode(',', $address->getCategories());
        } else {
            $dmCatArr = [];
        }
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
        $queryBuilder
            ->update('tt_address')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($tableUid, \PDO::PARAM_INT))
            )
            ->set('tstamp', $timestamp)
            ->set('title', $address->getTitle())
            ->set('first_name', $address->getFirstname())
            ->set('last_name', $address->getLastname())
            ->set('name', trim($address->getFirstname() . ' ' . $address->getLastname()))
            ->set('address', $address->getAddress())
            ->set('zip', $address->getZip())
            ->set('city', $address->getCity())
            ->set('region', $address->getRegion())
            ->set('country', $address->getCountry())
            ->set('phone', $address->getPhone())
            ->set('mobile', $address->getMobile())
            ->set('fax', $address->getFax())
            ->set('www', $address->getWww())
            ->set('position', $address->getPosition())
            ->set('company', $address->getCompany())
            ->set('gender', $gender)
            ->set('categories', count($dmCatArr));
        if ($mode != -1) {
            $queryBuilder
            ->set('mail_html', $mode)
            ->set('mail_salutation', $salutation)
            ->set('mail_active', 1);
        }
        $queryBuilder->executeStatement();
        $this->deleteInMm($tableUid);
        $this->insertIntoMm($tableUid, $dmCatArr);
        return $tableUid;
    }

    /**
     * updateInFeUsers: update fe_user
     * @param	\Fixpunkt\FpNewsletter\Domain\Model\Log	$address User
     * @param   int     $tableUid   externe uid
     */
    function updateInFeUsers($address, $tableUid)
    {
        $timestamp = time();
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $queryBuilder
            ->update('fe_users')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($tableUid, \PDO::PARAM_INT))
            )
            ->set('tstamp', $timestamp)
            ->set('title', $address->getTitle())
            ->set('first_name', $address->getFirstname())
            ->set('last_name', $address->getLastname())
            ->set('name', trim($address->getFirstname() . ' ' . $address->getLastname()))
            ->set('address', $address->getAddress())
            ->set('zip', $address->getZip())
            ->set('city', $address->getCity())
            ->set('country', $address->getCountry())
            ->set('telephone', $address->getPhone())
            ->set('fax', $address->getFax())
            ->set('company', $address->getCompany())
            ->set('usergroup', $address->getCategories())
            ->executeStatement();
        return $tableUid;
    }

    /**
     * deleteExternalUser: delete user
     * @param	integer	$uid		tt_address oder fe_users uid
     * @param	integer	$mode		Löschen-Modus: 1: update, 2: löschen
     * @param	array	$dmCatArr	sys_category categories
     * @param   string  $table      tt_address or fe_users
     */
    function deleteExternalUser($uid, $mode, $dmCatArr = [], $table = 'tt_address')
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        if ($mode == 2) {
            $queryBuilder
                ->delete($table)
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
                )
                ->executeStatement();
        } else {
            $queryBuilder
                ->update($table)
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
                )
                ->set('deleted', '1')
                ->set('tstamp', time())
                ->executeStatement();
        }
        if (($table == 'tt_address') && is_array($dmCatArr) && count($dmCatArr)>0) {
            $this->deleteInMm($uid);
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
	public function findAnotherByUid($uid, $sys_language_uid)
    {
	    $query = $this->createQuery();
	    $query->getQuerySettings()->setRespectSysLanguage(false);
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
	public function getStoragePids()
    {
		$query = $this->createQuery();
		return $query->getQuerySettings()->getStoragePageIds();
	}
}