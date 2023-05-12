<?php

declare(strict_types=1);
namespace Fixpunkt\FpNewsletter\ViewHelpers;

use Fixpunkt\FpNewsletter\Domain\Model\Log;
use Throwable;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Exception as ExceptionExbaseObject;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

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
        $this->registerArgument('params', 'array', 'fpnewsletter_pi1 params', false);
        $this->registerArgument('uid', 'string', 'uid', false);
        $this->registerArgument('hash', 'string', 'hash', true);
    }

    /**
     * @return string
     * @throws MisconfigurationException
     */
    public function render(): string
    {
        $siteService = GeneralUtility::makeInstance(SiteService::class);
        try {
            return $siteService->getPageUrlFromParameter(
                $this->getPidUnsubscribe(),
                [
                    'tx_luxletter_fe' => [
                        'user' => $this->getUserIdentifier(),
                        'newsletter' => $this->getNewsletterIdentifier(),
                        'hash' => $this->getHash(),
                    ],
                ]
            );
        } catch (Throwable $exception) {
            throw new MisconfigurationException(
                'Could not build a valid URL to unsubscribe page with PID "' . $this->getPidUnsubscribe() . '"',
                1646380245
            );
        }
    }

    /**
     * @return int
     * @throws MisconfigurationException
     */
    protected function getPidUnsubscribe(): int
    {
        $site = $this->arguments['site'];
        $unsubscribePid = (int)$site->getConfiguration()['luxletterUnsubscribePid'];
        if ($unsubscribePid === 0) {
            throw new MisconfigurationException(
                'No unsubscribe page identifier found in site configuration',
                1622811392
            );
        }
        return $unsubscribePid;
    }

    /**
     * @return int
     */
    protected function getUserIdentifier(): int
    {
        /** @var User $user */
        $user = $this->arguments['user'];
        if ($user !== null && $user->getUid() > 0) {
            return $user->getUid();
        }
        return 0;
    }

    /**
     * @return int
     */
    protected function getNewsletterIdentifier(): int
    {
        /** @var Newsletter $newsletter */
        $newsletter = $this->arguments['newsletter'];
        if ($newsletter !== null && $newsletter->getUid() > 0) {
            return $newsletter->getUid();
        }
        return 0;
    }

    /**
     * @return string
     * @throws MisconfigurationException
     * @throws UserValuesAreMissingException
     * @throws ExceptionExbaseObject
     */
    protected function getHash(): string
    {
        /** @var User $user */
        $user = $this->arguments['user'];
        if ($user !== null && $user->getUid() > 0) {
            return $user->getUnsubscribeHash();
        }
        return '';
    }
}
