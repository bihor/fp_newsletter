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

class StatusDataProvider implements ChartDataProviderInterface
{
    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @inheritDoc
     */
    public function getChartData(): array
    {
        $this->calculateDataForChart();
        return [
            'labels' => $this->labels,
            'datasets' => [
                [
                    'label' => $this->getLanguageService()->sL('LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:dashboard.widget.fixpunktLogStatus.label'),
                    'backgroundColor' => WidgetApi::getDefaultChartColors()[0],
                    'border' => 0,
                    'data' => $this->data,
                ],
            ],
        ];
    }

    protected function calculateDataForChart(): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_fpnewsletter_domain_model_log');
        $entries = $queryBuilder
            ->selectLiteral('count(status) AS num')
            ->addSelect('status')
            ->from('tx_fpnewsletter_domain_model_log')
            ->orderBy('status', 'ASC')
            ->groupBy('status')
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($entries as $entry) {
            $this->labels[] = $this->getLanguageService()->sL('LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_db.xlf:tx_fpnewsletter_domain_model_log.status.'.$entry['status']);
            $this->data[] = $entry['num'];
        }
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}