<?php

declare(strict_types=1);
namespace Fixpunkt\FpNewsletter\ViewHelpers;

use Throwable;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Controller\ErrorPageController;

/**
 * Class GetActionUrlViewHelper
 * @noinspection PhpUnused
 */
class GetActionUrlViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('pageUid', 'string', 'target page uid', true);
        $this->registerArgument('pi', 'string', 'pi', true);
        $this->registerArgument('action', 'string', 'action', true);
        $this->registerArgument('uid', 'string', 'uid', true);
        $this->registerArgument('hash', 'string', 'hash', true);
        $this->registerArgument('languageUid', 'string', 'sys_language_uid', false);
    }

    /**
     * @return string
     * @throws MisconfigurationException
     */
    public function render(): string
    {
        try {
            $language = 0;
            if (isset($this->arguments['languageUid'])) {
                $language = intval($this->arguments['languageUid']);
            }
            $site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId($this->arguments['pageUid']);
            $uri = $site->getRouter()->generateUri($this->arguments['pageUid'], [
                'tx_fpnewsletter_' . $this->arguments['pi'] => [
                    'controller' => 'Log',
                    'action' => $this->arguments['action'],
                    'uid' => $this->arguments['uid'],
                    'hash' => $this->arguments['hash']
                ],
                '_language' => $language
            ]);
            return $uri->__tostring();
        } catch (Throwable) {
            throw new \RuntimeException(
                'Could not build a valid URL to a fp_newsletter page with target page uid "' . $this->arguments['pageUid'] . '"',
                5588995474
            );
        }
    }
}
