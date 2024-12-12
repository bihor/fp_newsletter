<?php
declare(strict_types=1);

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Dashboard\Widgets\BarChartWidget;
use Fixpunkt\FpNewsletter\Widgets\RecentLogEntriesWidget;

return function (ContainerConfigurator $configurator, ContainerBuilder $containerBuilder) {
    if ($containerBuilder->hasDefinition(BarChartWidget::class)) {
        $services = $configurator->services();

        $services->set('dashboard.widget.fixpunktRecentLogEntries')
            ->class(RecentLogEntriesWidget::class)
            ->arg('$view', new Reference('dashboard.views.widget'))
            ->arg('$dataProvider', new Reference(\Fixpunkt\FpNewsletter\Widgets\Provider\LogDataProvider::class))
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

        $configuration = $services->set('dashboard.widget.fixpunktLogStatus')
            ->class(BarChartWidget::class)
            ->tag(
                'dashboard.widget',
                [
                    'identifier' => 'fixpunktLogStatus',
                    'groupNames' => 'fixpunkt',
                    'title' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:dashboard.widget.fixpunktLogStatus.title',
                    'description' => 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:dashboard.widget.fixpunktLogStatus.description',
                    'iconIdentifier' => 'content-widget-chart-bar',
                    'height' => 'medium',
                    'width' => 'medium'
                ]
            )
            ->arg('$dataProvider', new Reference(\Fixpunkt\FpNewsletter\Widgets\Provider\StatusDataProvider::class));
            // TYPO3 12
            $configuration->arg('$backendViewFactory', new Reference(BackendViewFactory::class));
    }
};