<?php
declare(strict_types=1);

namespace Fixpunkt\FpNewsletter\Backend\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

final class PreviewEventListener
{
    /**
     * Extension key
     *
     * @var string
     */
    public const KEY = 'fpnewsletter';

    /**
     * Path to the locallang file
     *
     * @var string
     */
    public const LLPATH = 'LLL:EXT:fp_newsletter/Resources/Private/Language/locallang_be.xlf:';

    /**
     * Max shown settings
     */
    public const SETTINGS_IN_PREVIEW = 10;

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

    /**
     * pi-Dinger
     *
     * @var array
     */
    protected $pis = ['fpnewsletter_new', 'fpnewsletter_form', 'fpnewsletter_subscribeext', 'fpnewsletter_verify', 'fpnewsletter_editemail', 'fpnewsletter_edit', 'fpnewsletter_unsubscribe', 'fpnewsletter_unsubscribelux', 'fpnewsletter_verifyunsubscribe', 'fpnewsletter_resend', 'fpnewsletter_list'];

    /**
     * Table information
     *
     * @var array
     */
    protected $tableData = [];

    /**
     * Flexform information
     *
     * @var array
     */
    protected $flexformData = [];

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    public function __construct(private readonly BackendViewFactory $backendViewFactory)
    {
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
    }

    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content') {
            return;
        }

        if ($event->getRecord()['CType'] === 'list' && in_array($event->getRecord()['list_type'], $this->pis)) {
            $this->tableData = [];
            $pi = substr((string) $event->getRecord()['list_type'], strpos((string) $event->getRecord()['list_type'], '_')+1);
            $header = '<strong>' . htmlspecialchars((string) $this->getLanguageService()->sL(self::LLPATH . 'template.' . $pi)) . '</strong>';
            $this->flexformData = GeneralUtility::xml2array($event->getRecord()['pi_flexform']);

            $this->getStartingPoint($event->getRecord()['pages']);

            if (is_array($this->flexformData)) {
                foreach ($this->recordMapping as $fieldName => $fieldConfiguration) {
                    $value = $this->getFieldFromFlexform('settings.' . $fieldName);
                    if (isset($value) && $value) {
                        $content = $this->getRecordData($value, $fieldConfiguration['table']);
                        $this->tableData[] = [
                            $this->getLanguageService()->sL(self::LLPATH . $fieldName),
                            $content
                        ];
                    }
                }
            }
            $event->setPreviewContent($this->renderSettingsAsTable($header, $event->getRecord()['uid']));
        }
    }


    /**
     * Get the rendered page title including onclick menu
     *
     * @param int $id
     * @param string $table
     * @return string
     */
    public function getRecordData($id, $table = 'pages')
    {
        $record = BackendUtilityCore::getRecord($table, $id);

        if (is_array($record)) {
            $data = '<span data-toggle="tooltip" data-placement="top" data-title="id=' . $record['uid'] . '">'
                . $this->iconFactory->getIconForRecord($table, $record, Icon::SIZE_SMALL)->render()
                . '</span> &nbsp;';
            $content = BackendUtilityCore::wrapClickMenuOnIcon($data, $table, $record['uid'], true, $record);
            $content .= htmlspecialchars(BackendUtilityCore::getRecordTitle($table, $record));
        } else {
            $text = sprintf($this->getLanguageService()->sL(self::LLPATH . 'pagemodule.pageNotAvailable'),
                $id);
            $content = $this->generateCallout($text);
        }

        return $content;
    }

    /**
     * Get the startingpoint
     *
     * @param string $pids
     * @return void
     */
    public function getStartingPoint($pids)
    {
        if (!empty($pids)) {
            $pageIds = GeneralUtility::intExplode(',', $pids, true);
            $pagesOut = [];

            foreach ($pageIds as $id) {
                $pagesOut[] = $this->getRecordData($id, 'pages');
            }

            $recursiveLevel = (int)$this->getFieldFromFlexform('settings.recursive');
            $recursiveLevelText = '';
            if ($recursiveLevel === 250) {
                $recursiveLevelText = $this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.5');
            } elseif ($recursiveLevel > 0) {
                $recursiveLevelText = $this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.' . $recursiveLevel);
            }

            if (!empty($recursiveLevelText)) {
                $recursiveLevelText = '<br />' .
                    $this->getLanguageService()->sL(self::LLPATH . 'recursive') . ' ' .  $recursiveLevelText;
            }

            $this->tableData[] = [
                $this->getLanguageService()->sL(self::LLPATH . 'startingPoint'),
                implode(', ', $pagesOut) . $recursiveLevelText
            ];
        }
    }

    /**
     * Render an alert box
     *
     * @param string $text
     * @return string
     */
    protected function generateCallout($text)
    {
        return '<div class="alert alert-warning">' . htmlspecialchars($text) . '</div>';
    }

    /**
     * Render the settings as table for Web>Page module
     * System settings are displayed in mono font
     *
     * @param string $header
     * @param int $recordUid
     * @return string
     */
    protected function renderSettingsAsTable($header = '', $recordUid = 0)
    {
        $view = $this->backendViewFactory->create($GLOBALS['TYPO3_REQUEST'], ['fixpunkt/fp-newsletter']);
        $view->assignMultiple([
            'header' => $header,
            'rows' => [
                'above' => array_slice($this->tableData, 0, self::SETTINGS_IN_PREVIEW),
                'below' => array_slice($this->tableData, self::SETTINGS_IN_PREVIEW)
            ],
            'id' => $recordUid
        ]);
        return $view->render('Backend/PluginPreview');
    }

    /**
     * Get field value from flexform configuration,
     * including checks if flexform configuration is available
     *
     * @param string $key name of the key
     * @param string $sheet name of the sheet
     * @return string|NULL if nothing found, value if found
     */
    public function getFieldFromFlexform($key, $sheet = 'sDEF')
    {
        $flexform = $this->flexformData;
        if (isset($flexform['data'])) {
            $flexform = $flexform['data'];
            if (isset($flexform) && isset($flexform[$sheet]) && isset($flexform[$sheet]['lDEF'])
                && isset($flexform[$sheet]['lDEF'][$key]) && isset($flexform[$sheet]['lDEF'][$key]['vDEF'])
            ) {
                return $flexform[$sheet]['lDEF'][$key]['vDEF'];
            }
        }

        return null;
    }

    /**
     * Return language service instance
     *
     * @return LanguageService
     */
    public function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
