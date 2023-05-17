<?php

declare(strict_types = 1);

namespace Fixpunkt\FpNewsletter\Widgets;

use Fixpunkt\FpNewsletter\Widgets\Provider\LogDataProvider;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class RecentLogEntriesWidget implements WidgetInterface
{
    /**
     * @var WidgetConfigurationInterface
     */
    private $configuration;

    /**
     * @var StandaloneView
     */
    private $view;

    /**
     * @var array
     */
    private $options;

    /**
     * @var LogDataProvider
     */
    private $dataProvider;

    public function __construct(
        WidgetConfigurationInterface $configuration,
        StandaloneView               $view,
        LogDataProvider              $dataProvider,
        array                        $options = []
    ) {
        $this->configuration = $configuration;
        $this->view = $view;
        $this->dataProvider = $dataProvider;
        $this->options = array_merge(
            [
                'showErrors' => true,
                'showWarnings' => false
            ],
            $options
        );
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate('Widget/RecentLogEntries');
        $this->view->assignMultiple([
            'configuration' => $this->configuration,
            'logs' => $this->dataProvider->getRecentLogEntries()
        ]);
        return $this->view->render();
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
