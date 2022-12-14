<?php

namespace Fixpunkt\FpNewsletter\Utility;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * General helpers for the FE
 *
 * @package fp_newsletter
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class HelpersUtility
{

    /**
     * Prüft, ob ein angegebenes math. Captcha OK ist
     *
     * @param int $result
     * @return int
     */
    public function checkMathCaptcha(int $result): int
    {
        $error = 0;
        if($GLOBALS['TSFE']->fe_user->getKey('ses', 'mcaptcha1') !== NULL && $GLOBALS['TSFE']->fe_user->getKey('ses', 'mcaptcha2') !== NULL && $GLOBALS['TSFE']->fe_user->getKey('ses', 'mcaptchaop') !== NULL) {
            //$result = intval($log->getMathcaptcha());
            $no1 = intval($GLOBALS['TSFE']->fe_user->getKey('ses', 'mcaptcha1'));
            $no2 = intval($GLOBALS['TSFE']->fe_user->getKey('ses', 'mcaptcha2'));
            $operator = intval($GLOBALS['TSFE']->fe_user->getKey('ses', 'mcaptchaop'));
            if ($operator == 1) {
                $real_result = $no1 + $no2;
            } else {
                $real_result = $no1 - $no2;
            }
            if ($result != $real_result) {
                $error = 9;
            } else {
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptcha1', NULL);
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptcha2', NULL);
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptchaop', NULL);
            }
        } else {
            $error = 9;
        }
        return $error;
    }

    /**
     * Get TYPO3 encryption key
     *
     * @return string
     */
    private function getEncryptionKey(): string
    {
        $configurationManager = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ConfigurationManager::class);
        return $configurationManager->getLocalConfigurationValueByPath('SYS/encryptionKey');
    }

    /**
     * Checks a Luxletter hash
     *
     * @param array $user fe_users Array
     * @param string $hash hash to check
     * @return boolean
     */
    public function checkLuxletterHash(array $user, string $hash): bool
    {
        $arguments = [$user['uid'], $user['crdate'], $this->getEncryptionKey()];
        $hashCalculated = hash('sha256', implode('/', $arguments));
        // zum testen: var_dump($user .'#'. $userArray['crdate'] .'#'. $this->helpersUtility->getEncryptionKey() .'#'. $hashCalculated);
        return ($hashCalculated == $hash);
    }

    /**
     * Checks a Direct-mail auth Code
     *
     * @param array $user tt_address Array
     * @param string $authCode auth code to check
     * @return boolean
     */
    public function checkDirectmailAuthCode(array $user, string $authCode): bool
    {
        return (preg_match('/^[0-9a-f]{8}$/', $authCode) &&
            ($authCode == GeneralUtility::stdAuthCode($user, 'uid')));
    }

    /**
     * Returns an array with genders
     *
     * @param bool $useXlf xlf-file oder settings-array?
     * @param array $settings Gender-Array from settings
     * @param bool $please with please select?
     * @return array
     */
    public function getGenders(bool $useXlf, array $settings, bool $please = true): array
    {
        if ($useXlf) {
            $pleaseText = ($please) ? LocalizationUtility::translate('tx_fpnewsletter_domain_model_log.gender.please', 'fp_newsletter') : '';
            return [
                "0" => $pleaseText,
                "1" => LocalizationUtility::translate('tx_fpnewsletter_domain_model_log.gender.mrs', 'fp_newsletter'),
                "2" => LocalizationUtility::translate('tx_fpnewsletter_domain_model_log.gender.mr', 'fp_newsletter'),
                "3" => LocalizationUtility::translate('tx_fpnewsletter_domain_model_log.gender.divers', 'fp_newsletter')
            ];
        } else {
            $pleaseText = ($please) ? $settings['please'] : '';
            return [
                "0" => $pleaseText,
                "1" => $settings['mrs'],
                "2" => $settings['mr'],
                "3" => $settings['divers'],
            ];
        }
    }

    /**
     * Prepare Array for emails and trigger sending of emails
     *
     * @param \Fixpunkt\FpNewsletter\Domain\Model\Log $log
     * @param array   $settings       settings
     * @param array   $view           view
     * @param boolean $isSubscribe    Subscription or unsubscription?
     * @param boolean $isConfirmation verify or confirmation?
     * @param boolean $toUser         email to user?
     * @param boolean $toAdmin        email to admin?
     * @param string  $hash           hash
     * @param integer $verifyUid      UID of the verification page
     */
    public function prepareEmail(
        \Fixpunkt\FpNewsletter\Domain\Model\Log &$log,
        array $settings,
        array $view,
        bool $isSubscribe = true,
        bool $isConfirmation = false,
        bool $toUser = false,
        bool $toAdmin = false,
        string $hash = '',
        int $verifyUid = 0)
    {
        $genders = $this->getGenders($settings['preferXlfFile'], $settings['gender'], false);
        $email = $log->getEmail();
        $from = trim($log->getFirstname() . ' ' . $log->getLastname());
        if (! $from) {
            $from = 'Subscriber';
        }
        $dataArray = [];
        $dataArray['uid'] = $log->getUid();
        $dataArray['sys_language_uid'] = $log->get_languageUid();
        $dataArray['gender_id'] = $log->getGender();
        $dataArray['gender'] = $genders[$log->getGender()];
        $dataArray['title'] = $log->getTitle();
        $dataArray['firstname'] = $log->getFirstname();
        $dataArray['lastname'] = $log->getLastname();
        $dataArray['email'] = $email;
        $dataArray['address'] = $log->getAddress();
        $dataArray['zip'] = $log->getZip();
        $dataArray['city'] = $log->getCity();
        $dataArray['region'] = $log->getRegion();
        $dataArray['country'] = $log->getCountry();
        $dataArray['phone'] = $log->getPhone();
        $dataArray['mobile'] = $log->getMobile();
        $dataArray['fax'] = $log->getFax();
        $dataArray['www'] = $log->getWww();
        $dataArray['position'] = $log->getPosition();
        $dataArray['company'] = $log->getCompany();
        $dataArray['hash'] = $hash;
        if ($verifyUid) {
            if ($isSubscribe) {
                $dataArray['subscribeVerifyUid'] = $verifyUid;
            } else {
                $dataArray['unsubscribeVerifyUid'] = $verifyUid;
            }
        }
        $dataArray['settings'] = $settings;
        if ($toUser) {
            if ($isSubscribe) {
                if ($isConfirmation) {
                    $subject = (($settings['preferXlfFile']) ?
                        LocalizationUtility::translate('email.subscribedSubject', 'fp_newsletter') :
                        $settings['email']['subscribedSubject']);
                    $template = 'Subscribed';
                } else {
                    $subject = (($settings['preferXlfFile']) ?
                        LocalizationUtility::translate('email.subscribeVerifySubject', 'fp_newsletter') :
                        $settings['email']['subscribeVerifySubject']);
                    $template = 'SubscribeVerify';
                }
            } else {
                if ($isConfirmation) {
                    $subject = (($settings['preferXlfFile']) ?
                        LocalizationUtility::translate('email.unsubscribedSubject', 'fp_newsletter') :
                        $settings['email']['unsubscribedSubject']);
                    $template = 'Unsubscribed';
                } else {
                    $subject = (($settings['preferXlfFile']) ?
                        LocalizationUtility::translate('email.unsubscribeVerifySubject', 'fp_newsletter') :
                        $settings['email']['unsubscribeVerifySubject']);
                    $template = 'UnsubscribeVerify';
                }
            }
            $this->sendTemplateEmail(
                $view,
                array($email => $from),
                array($settings['email']['senderMail'] => $settings['email']['senderName']),
                $subject,
                $template,
                $dataArray,
                false);
        }
        if ($toAdmin) {
            if ($isSubscribe) {
                $subject = (($settings['preferXlfFile']) ?
                    LocalizationUtility::translate('email.adminSubscribeSubject', 'fp_newsletter') :
                    $settings['email']['adminSubscribeSubject']);
                if ($isConfirmation) {
                    $template = 'SubscribeToAdmin';
                } else {
                    $template = 'UserToAdmin';
                }
            } else {
                $subject = (($settings['preferXlfFile']) ?
                    LocalizationUtility::translate('email.adminUnsubscribeSubject', 'fp_newsletter') :
                    $settings['email']['adminUnsubscribeSubject']);
                if ($isConfirmation) {
                    $template = 'UnsubscribeToAdmin';
                } else {
                    $template = 'UserToAdmin';
                }
            }
            $this->sendTemplateEmail(
                $view,
                array($settings['email']['adminMail'] => $settings['email']['adminName']),
                array($settings['email']['senderMail'] => $settings['email']['senderName']),
                $subject,
                $template,
                $dataArray,
                true);
        }
    }

    /**
     * Send an email
     *
     * @param array $view
     * @param array $recipient
     *            recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * @param array $sender
     *            sender of the email in the format array('sender@domain.tld' => 'Sender Name')
     * @param string $subject
     *            subject of the email
     * @param string $templateName
     *            template name (UpperCamelCase)
     * @param array $variables
     *            variables to be passed to the Fluid view
     * @param boolean $toAdmin
     *            email to the admin?
     * @return boolean TRUE on success, otherwise false
     */
    protected function sendTemplateEmail(
        array $view,
        array $recipient,
        array $sender,
        string $subject,
        string $templateName,
        array $variables = array(),
        bool $toAdmin = false)
    {
        // Das hier ist von hier: https://wiki.typo3.org/How_to_use_the_Fluid_Standalone_view_to_render_template_based_emails
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        if (!$toAdmin && !$variables['settings']['email']['dontAppendL']) {
            $templateName .= $sys_language_uid;
        }
        $extensionName = 'FpNewsletter'; // $this->request->getControllerExtensionName();

        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
        $emailViewHtml = GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $emailViewHtml->getRequest()->setControllerExtensionName($extensionName); // make sure f:translate() knows where to find the LLL file
        $emailViewHtml->setTemplateRootPaths($view['templateRootPaths']);
        $emailViewHtml->setLayoutRootPaths($view['layoutRootPaths']);
        $emailViewHtml->setPartialRootPaths($view['partialRootPaths']);
        $emailViewHtml->setTemplate('Email/' . $templateName . '.html');
        $emailViewHtml->setFormat('html');
        $emailViewHtml->assignMultiple($variables);
        $emailBodyHtml = $emailViewHtml->render();

        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
        $emailViewText = GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
        $emailViewText->getRequest()->setControllerExtensionName($extensionName); // make sure f:translate() knows where to find the LLL file
        $emailViewText->setTemplateRootPaths($view['templateRootPaths']);
        $emailViewText->setLayoutRootPaths($view['layoutRootPaths']);
        $emailViewText->setPartialRootPaths($view['partialRootPaths']);
        $emailViewText->setTemplate('Email/' . $templateName . '.txt');
        $emailViewText->setFormat('txt');
        $emailViewText->assignMultiple($variables);
        $emailBodyText = $emailViewText->render();
        if ($variables['settings']['debug']) {
            echo "###" . $emailBodyText . '###';
            echo "###" . $emailBodyHtml . '###';
            return;
        }

        /** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
        $message = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
        foreach ($recipient as $key => $value) {
            $email = $key;
            $name = $value;
        }
        $message->to(new \Symfony\Component\Mime\Address($email, $name));
        foreach ($sender as $key => $value) {
            $email = $key;
            $name = $value;
        }
        $message->from(new \Symfony\Component\Mime\Address($email, $name));
        $message->subject($subject);
        $message->text($emailBodyText);
        $message->html($emailBodyHtml);
        $message->send();
        return $message->isSent();
    }
}