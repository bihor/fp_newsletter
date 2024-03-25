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
     * @var array
     */
    private $options;

    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly StandaloneView               $view,
        private readonly LogDataProvider              $dataProvider,
        array                        $options = []
    ) {
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
