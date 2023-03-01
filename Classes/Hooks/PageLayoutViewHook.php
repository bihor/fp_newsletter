<?php
declare(strict_types=1);

namespace Fixpunkt\FpNewsletter\Hooks;

use Doctrine\DBAL\Connection;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageLayoutViewHook implements PageLayoutViewDrawItemHookInterface
{
    protected $recordMapping = [
        'subscribeUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'subscribeMessageUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'subscribeVerifyUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'subscribeVerifyMessageUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'unsubscribeUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'unsubscribeMessageUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'unsubscribeVerifyUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'unsubscribeVerifyMessageUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'resendVerificationUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'editUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
        'gdprUid' => [
            'table' => 'pages',
            'multiValue' => false,
        ],
    ];

    public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row)
    {
        if ($row['list_type'] === 'fpnewsletter_pi1' && $row['CType'] === 'list') {
            $row = $this->enrichRow($row);
        }
    }

    protected function enrichRow(array $row): array
    {
        $settings = $this->getFlexFormData($row['pi_flexform'] ?? '');
        if ($settings) {
            if (isset($settings['switchableControllerActions'])) {
                $row['_computed']['displayMode'] = $settings['switchableControllerActions'];
            }
        }
        if ($row['pages']) {
            $pagesOut = $this->getRecords('pages', $row['pages']);
            $row['_computed']['startingPoint'] = $pagesOut[0] ?: [];
        }
        foreach ($this->recordMapping as $fieldName => $fieldConfiguration) {
            if (isset($settings['settings'][$fieldName])) {
                $records = $this->getRecords($fieldConfiguration['table'], $settings['settings'][$fieldName]);

                if ($fieldConfiguration['multiValue']) {
                    $row['_computed'][$fieldName] = $records;
                } elseif (isset($records[0])) {
                    $row['_computed'][$fieldName] = $records[0];
                } else {
                    $row['_computed'][$fieldName] = [];
                }
            }
        }
        $row['_computed']['lll'] = 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:';
        return $row;
    }

    protected function getRecords(string $table, string $idList)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        $rows = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->in(
                    'uid',
                    $queryBuilder->createNamedParameter(GeneralUtility::intExplode(',', $idList, true), Connection::PARAM_INT_ARRAY)
                )
            )
            ->execute()
            ->fetchAll();

        foreach ($rows as &$row) {
            $row['_computed']['title'] = BackendUtility::getRecordTitle($table, $row);
        }
        return $rows;
    }

    protected function getFlexFormData(string $flexforms): array
    {
        $settings = [];
        if (!empty($flexforms)) {
            $settings = GeneralUtility::makeInstance(FlexFormService::class)->convertFlexFormContentToArray($flexforms);
        }
        return $settings;
    }
}