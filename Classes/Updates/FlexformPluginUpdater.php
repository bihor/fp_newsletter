<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "plain_faq" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Fixpunkt\FpNewsletter\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

class FlexformPluginUpdater implements UpgradeWizardInterface
{
    private const MIGRATION_SETTINGS = [
        [
            'sourceListType' => 'fpnewsletter_new',
        ],
        [
            'sourceListType' => 'fpnewsletter_form',
        ],
        [
            'sourceListType' => 'fpnewsletter_subscribeext',
        ],
        [
            'sourceListType' => 'fpnewsletter_verify',
        ],
        [
            'sourceListType' => 'fpnewsletter_editemail',
        ],
        [
            'sourceListType' => 'fpnewsletter_edit',
        ],
        [
            'sourceListType' => 'fpnewsletter_unsubscribe',
        ],
        [
            'sourceListType' => 'fpnewsletter_unsubscribelux',
        ],
        [
            'sourceListType' => 'fpnewsletter_unsubscribe',
        ],
        [
            'sourceListType' => 'fpnewsletter_unsubscribe',
        ],
        [
            'sourceListType' => 'fpnewsletter_verifyunsubscribe',
        ],
        [
            'sourceListType' => 'fpnewsletter_resend',
        ],
        [
            'sourceListType' => 'fpnewsletter_list',
        ]
    ];

    public function getIdentifier(): string
    {
        return 'flexformPluginUpdaterFpNl';
    }

    public function getTitle(): string
    {
        return 'Migrates direct_mail settings of fp_newsletter in the FlexForms';
    }

    public function getDescription(): string
    {
        $description = 'In version 6 of fp_newsletter some FlexForm settings changed the name. ';
        $description .= 'This update wizard migrates the old names to the new names.';
        return $description;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function updateNecessary(): bool
    {
        return $this->checkIfWizardIsRequired();
    }

    public function executeUpdate(): bool
    {
        return $this->performMigration();
    }

    public function checkIfWizardIsRequired(): bool
    {
        return count($this->getMigrationRecords()) > 0;
    }

    public function performMigration(): bool
    {
        $records = $this->getMigrationRecords();
        $search  = ['settings.module_sys_dmail_category', 'settings.module_sys_dmail_html', 'settings.dmUnsubscribeMode'];
        $replace = ['settings.categoryOrGroup', 'settings.html', 'settings.unsubscribeMode'];
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
        foreach ($records as $record) {
            $flexFormData = $record['pi_flexform'];
            $flexFormData = str_replace($search, $replace, (string) $flexFormData);
            $queryBuilder
                ->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($record['uid'], Connection::PARAM_INT))
                )
                ->set('pi_flexform', $flexFormData)
                ->execute();
        }

        return true;
    }

    protected function getMigrationRecords(): array
    {
        $checkListTypes = array_unique(array_column(self::MIGRATION_SETTINGS, 'sourceListType'));

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        return $queryBuilder
            ->select('uid', 'list_type', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->in(
                    'list_type',
                    $queryBuilder->createNamedParameter($checkListTypes, Connection::PARAM_STR_ARRAY)
                ),
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->like(
                        'pi_flexform',
                        $queryBuilder->createNamedParameter('%settings.module_sys_dmail_category%')
                    ),
                    $queryBuilder->expr()->like(
                        'pi_flexform',
                        $queryBuilder->createNamedParameter('%settings.module_sys_dmail_html%')
                    ),
                    $queryBuilder->expr()->like(
                        'pi_flexform',
                        $queryBuilder->createNamedParameter('%settings.dmUnsubscribeMode%')
                    )
                )
            )
            ->execute()
            ->fetchAll();
    }
}