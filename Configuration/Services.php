<?php
declare(strict_types=1);

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Dashboard\Widgets\BarChartWidget;
use Fixpunkt\FpNewsletter\Widgets\Provider\LogDataProvider;
use Fixpunkt\FpNewsletter\Widgets\RecentLogEntriesWidget;

return function (ContainerConfigurator $configurator, ContainerBuilder $containerBuilder) {
    if ($containerBuilder->hasDefinition(BarChartWidget::class)) {
        $services = $configurator->services();

        $services->set('dashboard.widget.fixpunktRecentLogEntries')
            ->class(RecentLogEntriesWidget::class)
            ->arg('$view', new Reference('dashboard.views.widget'))
            ->arg('$dataProvider', new Reference(LogDataProvider::class))
            ->tag(
                'dashboard.widget',
                [
                    'identifier' => 'fixpunktRecentLogEntries',
                    'groupNames' => 'fixpunkt',
                    'title' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:dashboard.widget.fixpunktRecentLogEntries.title',
                    'description' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:dashboard.widget.fixpunktRecentLogEntries.description',
                    'iconIdentifier' => 'content-widget-list',
                    'height' => 'medium',
                    'width' => 'medium'
                ]
            );
    }
};