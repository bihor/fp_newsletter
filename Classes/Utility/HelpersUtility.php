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
     * @return int
     */
    public function checkMathCaptcha(int $result, \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication $frontendUser): int
    {
        $error = 0;
        //echo "ses: ".$frontendUser->getKey('ses', 'mcaptcha1').'---'.$frontendUser->getKey('ses', 'mcaptcha2');
        if($frontendUser->getKey('ses', 'mcaptcha1') !== NULL && $frontendUser->getKey('ses', 'mcaptcha2') !== NULL && $frontendUser->getKey('ses', 'mcaptchaop') !== NULL) {
            //$result = intval($log->getMathcaptcha());
            $no1 = intval($frontendUser->getKey('ses', 'mcaptcha1'));
            $no2 = intval($frontendUser->getKey('ses', 'mcaptcha2'));
            $operator = intval($frontendUser->getKey('ses', 'mcaptchaop'));
            if ($operator == 1) {
                $real_result = $no1 + $no2;
            } else {
                $real_result = $no1 - $no2;
            }
            if ($result != $real_result) {
                $error = 9;
            } else {
                $frontendUser->setKey('ses', 'mcaptcha1', NULL);
                $frontendUser->setKey('ses', 'mcaptcha2', NULL);
                $frontendUser->setKey('ses', 'mcaptchaop', NULL);
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
     * Checks a Mail hash
     *
     * @param array $record fe_users or tt_address Array
     * @param string $hash hash to check
     * @param string $fields Fields used in the computation of authentication codes
     * @param int $codeLength length of returned authentication code
     * @return boolean
     */
    public function checkMailHash(array $record, string $hash, string $fields, int $codeLength = 8): bool
    {
        $prefixFields = [];
        if ($fields) {
            $fieldArray = GeneralUtility::trimExplode(',', $fields, true);
            foreach ($fieldArray as $key => $value) {
                $prefixFields[$key] = $record[$value];
            }
        } else {
            $prefixFields = $record;
        }
        $prefix = implode('|', $prefixFields);
        $authCode = $prefix . '||' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'];
        $hashCalculated = substr(md5($authCode), 0, $codeLength);
        // var_dump($hashCalculated);
        return ($hashCalculated == $hash);
    }

    /**
     * Set a hash and the language to a new log-entry
     *
     * @return string
     */
    public function setHashAndLanguage(
        \Fixpunkt\FpNewsletter\Domain\Model\Log &$log,
        int $languageMode): string
    {
        $hash = md5(uniqid($log->getEmail(), true));
        $log->setSecurityhash($hash);
        // Sprachsetzung sollte eigentlich automatisch passieren, tut es wohl aber nicht.
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        if ($sys_language_uid > 0 && ! $languageMode) {
            $log->setSysLanguageUid(-1);
        } else {
            $log->setSysLanguageUid($sys_language_uid);
        }
        return $hash;
    }

    /**
     * Check if a given log entry is valid
     *
     * @return int
     */
    public function checkIfValid(
        \Fixpunkt\FpNewsletter\Domain\Model\Log &$log,
        string $hash, string $daysExpire): int
    {
        $dbhash = $log->getSecurityhash();
        if ($hash != $dbhash) {
           return 3;
        } else {
            $now = new \DateTime();
            $diff = $now->diff($log->getTstamp())->days;
            if ($diff > $daysExpire) {
                return 4;
            } elseif (!GeneralUtility::validEmail($log->getEmail())) {
                return 8;
            }
        }
        return 0;
    }

    public function getSalutation(int $gender, array $settings): string
    {
        $salutation = '';
        if ($gender == 1) $salutation = $settings['mrs'];
        elseif ($gender == 2) $salutation = $settings['mr'];
        elseif ($gender == 3) $salutation = $settings['divers'];
        return $salutation;
    }

    /**
     * Returns an array with genders
     *
     * @param bool|string $useXlf xlf-file oder settings-array?
     * @param array $settings Gender-Array from settings
     * @param bool $please with please select?
     * @return array
     */
    public function getGenders(bool|string $useXlf, array $settings, bool $please = true): array
    {
        if ($useXlf) {
            $pleaseText = ($please) ? LocalizationUtility::translate('tx_fpnewsletter_domain_model_log.gender.please', 'FpNewsletter') : '';
            return [
                "0" => $pleaseText,
                "1" => LocalizationUtility::translate('tx_fpnewsletter_domain_model_log.gender.mrs', 'FpNewsletter'),
                "2" => LocalizationUtility::translate('tx_fpnewsletter_domain_model_log.gender.mr', 'FpNewsletter'),
                "3" => LocalizationUtility::translate('tx_fpnewsletter_domain_model_log.gender.divers', 'FpNewsletter')
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
     * Send an email
     *
     * param array $recipient
     *            recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
     * param array $sender
     *            sender of the email in the format array('sender@domain.tld' => 'Sender Name')
     * param string $subject
     *            subject of the email
     * param string $templateName
     *            template name (UpperCamelCase)
     * param array $variables
     *            variables to be passed to the Fluid view
     * param boolean $toAdmin
     *            email to the admin?
     * @return boolean TRUE on success, otherwise false
     */
    public function sendTemplateEmail(
        array $recipient,
        array $sender,
        string $subject,
        string $emailBodyHtml,
        string $emailBodyText): bool
    {
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
