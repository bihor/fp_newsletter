<?php

declare(strict_types = 1);

namespace Fixpunkt\FpNewsletter\Widgets;

use Fixpunkt\FpNewsletter\Widgets\Provider\LogDataProvider;
use TYPO3\CMS\Dashboard\Widgets\RequestAwareWidgetInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use Psr\Http\Message\ServerRequestInterface;

class RecentLogEntriesWidget implements WidgetInterface, RequestAwareWidgetInterface
{
    /**
     * @var array
     */
    private $options;
    private ?ServerRequestInterface $request = null;

    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly BackendViewFactory $backendViewFactory,
        private readonly ParticipantsDataProvider     $dataProvider,
        array            $options = []
    )
    {
        $this->options = array_merge(
            [
                'showErrors' => true,
                'showWarnings' => false
            ],
            $options
        );
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function renderWidgetContent(): string
    {
        $view = $this->backendViewFactory->create($this->request, ['typo3/cms-dashboard', 'fixpunkt/fp-newsletter']);
        $view->assignMultiple([
            'configuration' => $this->configuration,
            'logs' => $this->dataProvider->getRecentLogEntries()
        ]);
        return $view->render('Widget/RecentLogEntries');
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
