<?php
namespace Fixpunkt\FpNewsletter\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Fixpunkt\FpNewsletter\Domain\Model\Log;
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
class LogRepository extends Repository
{

    /**
     * getByEmailAndPid: find user entry
     * @param   string $email: email
     * @param	array $pids: PIDs
     * @param	int $sys_language_uid: language
     * @param	int $maxDate: x days ago
     * @return array|QueryResultInterface
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
            'crdate' => QueryInterface::ORDER_DESCENDING
        ]);
        return $query->execute()->getFirst();
    }

    /**
	 * getAllFields: find additional fields
	 * @param	int $uid UID
	 * @return  array
	 */
	function getAllFields($uid)
	{
        $table = 'tx_fpnewsletter_domain_model_log';
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
		$statement = $queryBuilder
					->select('*')
					->from($table)
					->where(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
					)
					->executeQuery();
		while ($row = $statement->fetchAssociative()) {
			return $row;
		}
        return [];
	}

    /**
     * getUidFromExternal: find user ID
     * @param	string $email die E-Mail-Adresse wurde schon vorher geprüft!
     * @param	mixed	$pid PID oder Liste mit PIDs
     * @param   string  $table tt_address oder fe_users
     * @return  integer
     */
    function getUidFromExternal($email, mixed $pid, $table)
    {
        $dbuid = 0;
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        if (is_numeric($pid)) {
            $where = $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pid, Connection::PARAM_INT));
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
			$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
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
     * @param integer $uid user-uid
     * @param string $table tt_address or fe_users
     * @return	array
     */
    function getOwnCats($uid, $table = 'tt_address')
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category_record_mm');
        $statement = $queryBuilder
            ->select('uid_local')
            ->from('sys_category_record_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter($table))
            )
            ->executeQuery();
        return $statement->fetchAllAssociative();
    }

    /**
     * getAllGroups: find all fe_groups for Luxletter
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
     * @param   string  $table    tt_content or fe_users
     */
    function insertIntoMm($tableUid, $dmCatArr = [], $table = 'tt_address')
    {
        if (is_array($dmCatArr) && count($dmCatArr)>0) {
            $count = 0;
            foreach ($dmCatArr as $uid) {
                if (is_numeric(trim((string) $uid))) {
                    // set the categories to the mm table of sys_category
                    $count++;
                    $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category_record_mm');
                    $queryBuilder
                        ->insert('sys_category_record_mm')
                        ->values([
                            'uid_foreign' => intval($tableUid),
                            'uid_local' => intval($uid),
                            'tablenames' => $table,
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
     * @param string $table tt_content or fe_users
     */
    function deleteInMm($tableUid, $table = 'tt_address')
    {
        // alle Kategorie-Relationen löschen
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category_record_mm');
        $queryBuilder
            ->delete('sys_category_record_mm')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($tableUid, Connection::PARAM_INT)),
                $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter($table))
            )
            ->executeStatement();
    }

    /**
  * insertInTTAddress: insert user
  * @param Log $address User
  * @param integer	$mode 		HTML-mode
  * @param array	$dmCatArr	categories
  * @param string  $salutation Anrede
  * @param string $additionalFields weitere extern zugefügte Felder
  */
 function insertInTtAddress($address, $mode, $dmCatArr = [], $salutation = '', $additionalFields = '')
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
        if ($additionalFields) {
            // um zusätzliche Felder befüllen zu können, wird ein Array statt ein Objekt gebraucht
            $addressArray = $this->getAllFields($address->getUid());
            $additionalArray = explode(',', $additionalFields);
            foreach ($additionalArray as $customField) {
                $insert[trim($customField)] = $addressArray[trim($customField)];
            }
        }
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_address');
		$queryBuilder
			->insert('tt_address')
			->values($insert)
			->executeStatement();
		$tableUid = $queryBuilder->getConnection()->lastInsertId();
        if ($tableUid) {
            $this->insertIntoMm($tableUid, $dmCatArr, 'tt_address');
        }
		return $tableUid;
	}

    /**
     * updateInTTAddress: update user in tt_address
     * @param Log $address User
     * @param integer $mode HTML-mode
     * @param int     $tableUid   externe uid
     * @param string $salutation Anrede
     * @param string $additionalFields weitere extern zugefügte Felder
     */
    function updateInTtAddress($address, $mode, $tableUid, $salutation = '', $additionalFields = '')
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
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($tableUid, Connection::PARAM_INT))
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
        if ($additionalFields) {
            // um zusätzliche Felder befüllen zu können, wird ein Array statt ein Objekt gebraucht
            $addressArray = $this->getAllFields($address->getUid());
            $additionalArray = explode(',', $additionalFields);
            foreach ($additionalArray as $customField) {
                $queryBuilder->set(trim($customField), $addressArray[trim($customField)]);
            }
        }
        $queryBuilder->executeStatement();
        $this->deleteInMm($tableUid, 'tt_address');
        $this->insertIntoMm($tableUid, $dmCatArr, 'tt_address');
        return $tableUid;
    }

    /**
     * updateInFeUsers: update fe_user
     * @param Log $address User
     * @param   int     $tableUid   externe uid
     * @param   string  $extension  mail or luxletter
     */
    function updateInFeUsers($address, $tableUid, $extension = 'luxletter')
    {
        $timestamp = time();
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $queryBuilder
            ->update('fe_users')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($tableUid, Connection::PARAM_INT))
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
        if ($extension == 'mail') {
            if ($address->getCategories()) {
                // Priorität haben die Kategorien aus dem Formular/Log-Eintrag
                $dmCatArr = explode(',', $address->getCategories());
            } else {
                $dmCatArr = [];
            }
            $this->deleteInMm($tableUid, 'fe_users');
            $this->insertIntoMm($tableUid, $dmCatArr, 'fe_users');
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
            $queryBuilder
                ->update('fe_users')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($tableUid, Connection::PARAM_INT))
                )
                ->set('categories', count($dmCatArr))
                ->executeStatement();
        } else {
            // Luxletter
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
            $queryBuilder
                ->update('fe_users')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($tableUid, Connection::PARAM_INT))
                )
                ->set('usergroup', $address->getCategories())
                ->executeStatement();
        }
        return $tableUid;
    }

    /**
     * deleteExternalUser: delete user
     * @param	integer	$uid		tt_address oder fe_users uid
     * @param	integer	$mode		Löschen-Modus: 1: update, 2: löschen, 3: nur Kategorien/Gruppen entfernen
     * @param	array	$dmCatArr	sys_category categories
     * @param   string  $table      tt_address or fe_users
     * @param   string  $extension  luxletter or mail
     */
    function deleteExternalUser($uid, $mode, $dmCatArr = [], $table = 'tt_address', $extension = 'luxletter')
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        if ($mode == 2) {
            $queryBuilder
                ->delete($table)
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
                )
                ->executeStatement();
        } elseif ($mode == 4) {
            if ($table == 'fe_users') {
                $flag = 'disable';
            } else {
                $flag = 'hidden';
            }
            $queryBuilder
                ->update($table)
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
                )
                ->set($flag, 1)
                ->set('tstamp', time())
                ->executeStatement();
        } elseif ($mode != 3) {
            $queryBuilder
                ->update($table)
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
                )
                ->set('deleted', 1)
                ->set('tstamp', time())
                ->executeStatement();
        } elseif ($mode == 3 && $table == 'fe_users') {
            if ($extension == 'luxletter') {
                $queryBuilder
                    ->update($table)
                    ->where(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
                    )
                    ->set('usergroup', '')
                    ->set('tstamp', time())
                    ->executeStatement();
            } elseif ($extension == 'mail') {
                $queryBuilder
                    ->update($table)
                    ->where(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT))
                    )
                    ->set('mail_active', 0)
                    ->set('tstamp', time())
                    ->executeStatement();
            }
        }
        if (is_array($dmCatArr) && count($dmCatArr)>0) {
            $this->deleteInMm($uid, $table);
        }
    }

    /**
  * Find an entry with sys_language_uid > 0
  * https://forge.typo3.org/issues/86405
  *
  * @param	integer	$uid
  * @param	integer	$sys_language_uid
  * @return array|QueryResultInterface
  */
 public function findAnotherByUid($uid, $sys_language_uid)
    {
	    $query = $this->createQuery();
	    $query->getQuerySettings()->setRespectSysLanguage(false);
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