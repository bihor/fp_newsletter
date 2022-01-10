<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Fixpunkt\FpNewsletter\Widgets\Provider;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class LogDataProvider implements ChartDataProviderInterface
{
    /**
     * Number of days to gather information for.
     *
     * @var int
     */
    protected $days = 31;

    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(int $days = 31)
    {
        $this->days = $days;
    }

    /**
     * @inheritDoc
     */
    public function getChartData(): array
    {
        return [
            'labels' => $this->labels,
            'datasets' => [
                [
                    'label' => 'im Moment kein Chart',
                    'backgroundColor' => WidgetApi::getDefaultChartColors()[0],
                    'border' => 0,
                    'data' => $this->data,
                ],
            ],
        ];
    }

    public function getRecentLogEntries(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_fpnewsletter_domain_model_log');
        return $queryBuilder
            ->select('uid','tstamp','email', 'status', 'firstname', 'lastname')
            ->from('tx_fpnewsletter_domain_model_log')
            ->orderBy('tstamp', 'DESC')
            ->setMaxResults(7)
            ->execute()
            ->fetchAll();
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}