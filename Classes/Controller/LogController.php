<?php
namespace Fixpunkt\FpNewsletter\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;

/**
 *
 * This file is part of the "Newsletter managment" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Kurt Gusbeth <k.gusbeth@fixpunkt.com>, fixpunkt werbeagentur gmbh
 *
 */

/**
 * LogController
 */
class LogController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * logRepository
     *
     * @var \Fixpunkt\FpNewsletter\Domain\Repository\LogRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected $logRepository = null;

    /**
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * Injects the Configuration Manager and is initializing the framework settings: wird doppelt aufgerufen!
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     *            Instance of the Configuration Manager
     */
    public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;
        $tsSettings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        $tsSettings = $tsSettings['plugin.']['tx_fpnewsletter.']['settings.'];
        $originalSettings = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS);
        // if flexform setting is empty and value is available in TS
        $overrideFlexformFields = GeneralUtility::trimExplode(',', $tsSettings['overrideFlexformSettingsIfEmpty'], true);
        foreach ($overrideFlexformFields as $fieldName) {
            if (strpos($fieldName, '.') > 0) {
                $fieldArray = GeneralUtility::trimExplode('.', $fieldName, true);
                if (! ($originalSettings[$fieldArray[0]][$fieldArray[1]]) && isset($tsSettings[$fieldArray[0] . '.'][$fieldArray[1]])) {
                    $originalSettings[$fieldArray[0]][$fieldArray[1]] = $tsSettings[$fieldArray[0] . '.'][$fieldArray[1]];
                }
            } else {
                if (! ($originalSettings[$fieldName]) && isset($tsSettings[$fieldName])) {
                    $originalSettings[$fieldName] = $tsSettings[$fieldName];
                }
            }
        }
        $this->settings = $originalSettings;
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $logs = $this->logRepository->findAll();
        $this->view->assign('logs', $logs);
    }

    /**
     * action new
     *
     * @param \Fixpunkt\FpNewsletter\Domain\Model\Log $log
     *            Log-Entry
     * @param int $error
     *            Error-Code
     * @return void
     */
    public function newAction(\Fixpunkt\FpNewsletter\Domain\Model\Log $log = null, $error = 0)
    {
        $genders = [
            "0" => $this->settings['gender']['please'],
            "1" => $this->settings['gender']['mrs'],
            "2" => $this->settings['gender']['mr'],
            "3" => $this->settings['gender']['divers']
        ];
        $optional = [];
        $required = [];
        $optionalFields = $this->settings['optionalFields'];
        $requiredFields = $this->settings['optionalFieldsRequired'];
        if ($optionalFields) {
            $tmp = explode(',', $optionalFields);
            foreach ($tmp as $field) {
                $optional[trim($field)] = 1;
                $required[trim($field)] = 0;
            }
        }
        if ($requiredFields) {
            $tmp = explode(',', $requiredFields);
            foreach ($tmp as $field) {
                $required[trim($field)] = 1;
            }
        }
        if (! $log) {
            $log = $this->objectManager->get('Fixpunkt\\FpNewsletter\\Domain\\Model\\Log');
        }
        if ($this->settings['parameters']['email']) {
            $email = isset($_GET[$this->settings['parameters']['email']]) ? $_GET[$this->settings['parameters']['email']] : '';
            if (! $email) {
                $email = isset($_POST[$this->settings['parameters']['email']]) ? $_POST[$this->settings['parameters']['email']] : '';
            }
            if ($email) {
                $log->setEmail($email);
            }
        }
        if ($this->settings['mathCAPTCHA']) {
            $no1 = ($this->settings['mathCAPTCHA'] == 2) ? random_int(10, 19) : random_int(4, 9);
            $no2 = random_int(1, $no1 - 1);
            $operator = random_int(0, 1);
            $log->setMathcaptcha1($no1);
            $log->setMathcaptcha2($no2);
            $log->setMathcaptchaop((($operator == 1) ? true : false));
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptcha1', $no1);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptcha2', $no2);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptchaop', $operator);
            $GLOBALS['TSFE']->fe_user->storeSessionData();
        }
        $this->view->assign('genders', $genders);
        $this->view->assign('optional', $optional);
        $this->view->assign('required', $required);
        $this->view->assign('error', $error);
        $this->view->assign('log', $log);
    }

    /**
     * action subscribeExt
     *
     * @return void
     */
    public function subscribeExtAction()
    {
        if ($this->settings['parameters']['active'] && $this->settings['parameters']['email']) {
            $pactive = explode('|', $this->settings['parameters']['active']);
            $active = isset($_POST[$pactive[0]]) ? $_POST[$pactive[0]] : array();
            if ($active[$pactive[1]][$pactive[2]]) {
                $pemail = explode('|', $this->settings['parameters']['email']);
                $email = isset($_POST[$pemail[0]]) ? $_POST[$pemail[0]] : array();
                $email = $email[$pemail[1]][$pemail[2]];
                if ($email) {
                    $storagePidsArray = $this->logRepository->getStoragePids();
                    $pid = intval($storagePidsArray[0]);
                    $log = $this->objectManager->get('Fixpunkt\\FpNewsletter\\Domain\\Model\\Log');
                    $log->setPid($pid);
                    $log->setEmail($email);
                    $log->setGdpr(true);
                    $this->forward('create', null, null, [
                        'log' => $log
                    ]);
                }
            }
        }
    }

    /**
     * action create
     *
     * @param \Fixpunkt\FpNewsletter\Domain\Model\Log $log
     * @return void
     */
    public function createAction(\Fixpunkt\FpNewsletter\Domain\Model\Log $log)
    {
        $hash = md5(uniqid($log->getEmail(), true));
        $log->setSecurityhash($hash);
        // Sprachsetzung sollte eigentlich automatisch passieren, tut es wohl aber nicht.
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        if ($sys_language_uid > 0 && ! $this->settings['languageMode']) {
            $log->set_languageUid(-1);
        } else {
            $log->set_languageUid($sys_language_uid);
        }
        $log->setStatus(0);
        if ($log->getUid() > 0) {
            $this->logRepository->update($log);
        } else {
            $this->logRepository->add($log);
        }
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();

        $error = 0;
        $subscribeVerifyUid = $this->settings['subscribeVerifyUid'];
        if (! $subscribeVerifyUid) {
            // Fallback
            $subscribeVerifyUid = intval($GLOBALS["TSFE"]->id);
        }
        $email = $log->getEmail();
        $dbuidext = 0;
        if (\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($email)) {
            if ($this->settings['table'] == 'tt_address') {
                $dbuidext = $this->logRepository->getFromTtAddress($email, $log->getPid());
            }
        } else {
            $error = 8;
        }
        if ($this->settings['reCAPTCHA_site_key'] && $this->settings['reCAPTCHA_secret_key']) {
            // Captcha checken
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'secret' => $this->settings['reCAPTCHA_secret_key'],
                'response' => $log->getRetoken()
            ]);
            $output = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if (! $output["success"]) {
                $error = 9;
            }
        }
        if ($this->settings['mathCAPTCHA']) {
            $result = intval($log->getMathcaptcha());
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
            }
        }
        if ($this->settings['honeypot'] && $log->getExtras()) {
            // Der Honigtopf ist gefüllt
            $error = 10;
        }
        if ($dbuidext > 0) {
            $error = 6;
            $log->setStatus(6);
            $this->logRepository->update($log);
            $persistenceManager->persistAll();
        }
        if (! $error) {
            $toAdmin = ($this->settings['email']['adminMail'] && $this->settings['email']['adminMailBeforeVerification']);
            $this->prepareEmail($log, true, false, true, $toAdmin, $hash, $subscribeVerifyUid);
            $log->setStatus(1);
            $this->logRepository->update($log);
            $persistenceManager->persistAll();
        } else if ($error >= 8) {
            $this->redirect('new', null, null, [
                'log' => $log,
                'error' => $error
            ]);
        }
        if ($this->settings['disableErrorMsg'] && ($error == 5 || $error == 6)) {
            $error = 0;
        }

        if (($error == 0) && ($this->settings['subscribeMessageUid'])) {
            $uri = $this->uriBuilder->reset()
                ->setTargetPageUid($this->settings['subscribeMessageUid'])
                ->build();
            $this->redirectToURI($uri); // oder: $this->forward($this->settings['subscribeMessageUid']);
        } else {
            $this->view->assign('error', $error);
        }
    }

    /**
     * action unsubscribe with form
     *
     * @param \Fixpunkt\FpNewsletter\Domain\Model\Log $log
     *            Log-Entry
     * @param int $error
     *            Error-Code
     * @return void
     */
    public function unsubscribeAction(\Fixpunkt\FpNewsletter\Domain\Model\Log $log = null, $error = 0)
    {
        $storagePidsArray = $this->logRepository->getStoragePids();
        $pid = intval($storagePidsArray[0]);
        if (! $log) {
            $log = $this->objectManager->get('Fixpunkt\\FpNewsletter\\Domain\\Model\\Log');
            $log->setPid($pid);
        }
        if ($this->settings['parameters']['email']) {
            $email = $_GET[$this->settings['parameters']['email']];
            if (! $email) {
                $email = $_POST[$this->settings['parameters']['email']];
            }
            if ($email) {
                $log->setEmail($email);
            }
        }
        if ($this->settings['mathCAPTCHA']) {
            $no1 = ($this->settings['mathCAPTCHA'] == 2) ? random_int(10, 19) : random_int(4, 9);
            $no2 = random_int(1, $no1 - 1);
            $operator = random_int(0, 1);
            $log->setMathcaptcha1($no1);
            $log->setMathcaptcha2($no2);
            $log->setMathcaptchaop((($operator == 1) ? true : false));
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptcha1', $no1);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptcha2', $no2);
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'mcaptchaop', $operator);
            $GLOBALS['TSFE']->fe_user->storeSessionData();
        }
        $this->view->assign('log', $log);
        $this->view->assign('error', $error);
    }

    /**
     * action unsubscribe with direct_mail link
     *
     * @return void
     */
    public function unsubscribeDMAction()
    {
        $error = 0;
        $tables = [
            't' => 'tt_address',
            'f' => 'fe_users'
        ];
        $t = $tables[$_GET['t']];
        $u = intval($_GET['u']);
        $a = $_GET['a'];
        if ($t && $t == $this->settings['table'] && $u && $a) {
            $user = $this->logRepository->getUserFromTtAddress($u);
            // zum testen: echo GeneralUtility::stdAuthCode($user, 'uid');
            if ($user) {
                if (preg_match('/^[0-9a-f]{8}$/', $a) && ($a == GeneralUtility::stdAuthCode($user, 'uid'))) {
                    // unsubscribe user now
                    $GLOBALS['TSFE']->fe_user->setKey('ses', 'authDM', $a);
                    $GLOBALS['TSFE']->fe_user->storeSessionData();
                    $this->redirect('delete', null, null, [
                        'user' => $user
                    ]);
                } else {
                    $error = 10;
                }
            } else {
                $error = 11;
            }
        } else {
            $error = 10;
        }
        $this->view->assign('error', $error);
    }

    /**
     * action delete an user from the DB: unsubscribe him from the newsletter
     *
     * @param \Fixpunkt\FpNewsletter\Domain\Model\Log $log
     * @param array $user
     * @return void
     */
    public function deleteAction(\Fixpunkt\FpNewsletter\Domain\Model\Log $log = null, $user = [])
    {
        $error = 0;
        $messageUid = 0;
        $skipCaptchaTest = false;
        $storagePidsArray = $this->logRepository->getStoragePids();
        if (! $log) {
            $log = $this->objectManager->get('Fixpunkt\\FpNewsletter\\Domain\\Model\\Log');
            $log->setEmail($user['email']);
            $log->setPid($user['pid']);
        }
        $email = $log->getEmail();
        $pid = $log->getPid();
        if (! $pid) {
            $pid = intval($storagePidsArray[0]);
        }
        // zum testen: var_dump ($storagePidsArray);
        $hash = md5(uniqid($log->getEmail(), true));
        $log->setSecurityhash($hash);
        $dbuidext = 0;
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        if ($sys_language_uid > 0 && ! $this->settings['languageMode']) {
            $log->set_languageUid(-1);
        } else {
            $log->set_languageUid($sys_language_uid);
        }

        if (\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($email)) {
            if ($this->settings['table'] == 'tt_address') {
                if ($this->settings['searchPidMode'] == 1) {
                    $dbuidext = $this->logRepository->getFromTtAddressCheckAllFolders($email, $storagePidsArray);
                } else {
                    $dbuidext = $this->logRepository->getFromTtAddress($email, $pid);
                }
                // zum testen: echo "uid $dbuidext mit $email, $pid";
            }
        } else {
            $error = 8;
        }
        if ($this->settings['table'] && ($dbuidext == 0)) {
            $error = 7;
        }
        $a = $GLOBALS['TSFE']->fe_user->getKey('ses', 'authDM');
        if ($a) {
            // authCode von unsubscribeDM ist vorhanden!
            $GLOBALS['TSFE']->fe_user->setKey('ses', 'authDM', '');
            $GLOBALS['TSFE']->fe_user->storeSessionData();
            if (preg_match('/^[0-9a-f]{8}$/', $a) && ($a == GeneralUtility::stdAuthCode($user, 'uid'))) {
                $skipCaptchaTest = true;
            }
        }
        if (! $skipCaptchaTest) {
            if ($this->settings['reCAPTCHA_site_key'] && $this->settings['reCAPTCHA_secret_key']) {
                // Captcha checken
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, [
                    'secret' => $this->settings['reCAPTCHA_secret_key'],
                    'response' => $log->getRetoken()
                ]);
                $output = json_decode(curl_exec($ch), true);
                curl_close($ch);
                if (! $output["success"]) {
                    $error = 9;
                }
            }
            if ($this->settings['mathCAPTCHA']) {
                $result = intval($log->getMathcaptcha());
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
                }
            }
        }
        if ($this->settings['honeypot'] && $log->getExtras()) {
            // Der Honigtopf ist gefüllt
            $error = 10;
        }
        $log->setStatus(0);
        if ($log->getUid() > 0) {
            $this->logRepository->update($log);
        } else {
            $this->logRepository->add($log);
        }
        $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
        $persistenceManager->persistAll();

        if (! $error) {
            if ($this->settings['doubleOptOut']) {
                $unsubscribeVerifyUid = $this->settings['unsubscribeVerifyUid'];
                if (! $unsubscribeVerifyUid) {
                    // Fallback
                    $unsubscribeVerifyUid = intval($GLOBALS["TSFE"]->id);
                }
                $toAdmin = ($this->settings['email']['adminMail'] && $this->settings['email']['adminMailBeforeVerification']);
                $this->prepareEmail($log, false, false, true, $toAdmin, $hash, $unsubscribeVerifyUid);
                $messageUid = $this->settings['unsubscribeMessageUid'];
                $log->setStatus(3);
                $this->logRepository->update($log);
                $persistenceManager->persistAll();
            } else {
                if ($this->settings['table'] == 'tt_address') {
                    if ($this->settings['module_sys_dmail_category']) {
                        $dmail_cats = str_replace(' ', '', $this->settings['module_sys_dmail_category']);
                        $dmCatArr = explode(',', $dmail_cats);
                    } else {
                        $dmCatArr = [];
                    }
                    $this->logRepository->deleteInTtAddress($dbuidext, $this->settings['deleteMode'], $dmCatArr);
                }
                if (($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']) || ($this->settings['email']['enableConfirmationMails'])) {
                    $toAdmin = ($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']);
                    $this->prepareEmail($log, false, true, $this->settings['email']['enableConfirmationMails'], $toAdmin, $hash, 0);
                }
                $messageUid = $this->settings['unsubscribeVerifyMessageUid'];
                $log->setStatus(7);
                $this->logRepository->update($log);
                $persistenceManager->persistAll();
            }
        } else if ($error >= 8) {
            $this->redirect('unsubscribe', null, null, [
                'log' => $log,
                'error' => $error
            ]);
        }
        if ($this->settings['disableErrorMsg'] && ($error == 7)) {
            $error = 0;
        }

        if (($error == 0) && ($messageUid)) {
            $uri = $this->uriBuilder->reset()
                ->setTargetPageUid($messageUid)
                ->build();
            $this->redirectToURI($uri); // oder: $this->forward($this->settings['subscribeMessageUid']);
        } else {
            $this->view->assign('error', $error);
        }
    }

    /**
     * action verify Anmeldung
     *
     * @return void
     */
    public function verifyAction()
    {
        $error = 0;
        $dbuid = 0;
        $html = intval($this->settings['module_sys_dmail_html']);
        $dmCat = str_replace(' ', '', $this->settings['module_sys_dmail_category']);
        $uid = intval($this->request->hasArgument('uid')) ? $this->request->getArgument('uid') : 0;
        $hash = ($this->request->hasArgument('hash')) ? $this->request->getArgument('hash') : '';
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        if (! $uid || ! $hash) {
            $this->view->assign('error', 1);
        } else {
            if ($sys_language_uid > 0 && $this->settings['languageMode']) {
                $address = $this->logRepository->findAnotherByUid($uid, $sys_language_uid);
            } else {
                $address = $this->logRepository->findOneByUid($uid);
            }
            if ($address) {
                $dbuid = $address->getUid();
                $this->view->assign('address', $address);
            }
            if (! $dbuid) {
                $error = 2;
            } elseif ($address->getStatus() == 2) {
                $error = 5;
            } else {
                $dbhash = $address->getSecurityhash();
                if ($hash != $dbhash) {
                    $error = 3;
                } else {
                    // $dbstatus = $address->getStatus();
                    $dbemail = $address->getEmail();
                    $now = new \DateTime();
                    $diff = $now->diff($address->getTstamp())->days;
                    if ($diff > $this->settings['daysExpire']) {
                        $error = 4;
                    } else {
                        $dbuidext = 0;
                        if (GeneralUtility::validEmail($dbemail)) {
                            if ($this->settings['table'] == 'tt_address') {
                                $dbuidext = $this->logRepository->getFromTtAddress($dbemail, $address->getPid());
                            }
                        }
                        if ($dbuidext > 0) {
                            $error = 6;
                        } else {
                            $address->setStatus(2);
                            $this->logRepository->update($address);
                            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                            $persistenceManager->persistAll();
                            $success = 0;
                            if ($this->settings['table'] == 'tt_address') {
                                if ($dmCat) {
                                    $dmCatArr = explode(',', $dmCat);
                                } else {
                                    $dmCatArr = [];
                                }
                                $success = $this->logRepository->insertInTtAddress($address, $html, $dmCatArr);
                            }
                            if ($this->settings['table'] && $success < 1) {
                                $error = 8;
                            } elseif (($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']) || $this->settings['email']['enableConfirmationMails']) {
                                $toAdmin = ($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']);
                                $this->prepareEmail($address, true, true, $this->settings['email']['enableConfirmationMails'], $toAdmin, $hash, 0);
                            }
                        }
                    }
                }
            }
            if ($this->settings['disableErrorMsg'] && ($error == 5 || $error == 6)) {
                $error = 0;
            }

            if (($error == 0) && ($this->settings['subscribeVerifyMessageUid'])) {
                $uri = $this->uriBuilder->reset()
                    ->setTargetPageUid($this->settings['subscribeVerifyMessageUid'])
                    ->build();
                $this->redirectToURI($uri); // oder: $this->forward($this->settings['subscribeMessageUid']);
            } else {
                $this->view->assign('error', $error);
            }
        }
    }

    /**
     * action verifyUnsubscribe Abmeldung
     *
     * @return void
     */
    public function verifyUnsubscribeAction()
    {
        $error = 0;
        $dbuid = 0;
        $uid = intval($this->request->hasArgument('uid')) ? $this->request->getArgument('uid') : 0;
        $hash = ($this->request->hasArgument('hash')) ? $this->request->getArgument('hash') : '';
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        if (! $uid || ! $hash) {
            $this->view->assign('error', 1);
        } else {
            if ($sys_language_uid > 0 && $this->settings['languageMode']) {
                $address = $this->logRepository->findAnotherByUid($uid, $sys_language_uid);
            } else {
                $address = $this->logRepository->findOneByUid($uid);
            }
            if ($address) {
                $dbuid = $address->getUid();
                $this->view->assign('address', $address);
            }
            if (! $dbuid) {
                $error = 2;
            } elseif ($address->getStatus() == 4) {
                $error = 5;
            } else {
                $dbhash = $address->getSecurityhash();
                if ($hash != $dbhash) {
                    $error = 3;
                } else {
                    $dbemail = $address->getEmail();
                    $now = new \DateTime();
                    $diff = $now->diff($address->getTstamp())->days;
                    if ($diff > $this->settings['daysExpire']) {
                        $error = 4;
                    } else {
                        $dbuidext = 0;
                        if (\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($dbemail)) {
                            if ($this->settings['table'] == 'tt_address') {
                                $dbuidext = $this->logRepository->getFromTtAddress($dbemail, $address->getPid());
                            }
                        }
                        if ($this->settings['table'] && ! $dbuidext) {
                            $error = 6;
                        } else {
                            $address->setStatus(4);
                            $this->logRepository->update($address);
                            $persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                            $persistenceManager->persistAll();

                            if ($this->settings['table'] == 'tt_address') {
                                if ($this->settings['module_sys_dmail_category']) {
                                    $dmail_cats = str_replace(' ', '', $this->settings['module_sys_dmail_category']);
                                    $dmCatArr = explode(',', $dmail_cats);
                                } else {
                                    $dmCatArr = [];
                                }
                                $this->logRepository->deleteInTtAddress($dbuidext, $this->settings['deleteMode'], $dmCatArr);
                            }
                            if (($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']) || ($this->settings['email']['enableConfirmationMails'])) {
                                $toAdmin = ($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']);
                                $this->prepareEmail($address, false, true, $this->settings['email']['enableConfirmationMails'], $toAdmin, $hash, 0);
                            }
                        }
                    }
                }
            }
            if ($this->settings['disableErrorMsg'] && ($error == 5 || $error == 6)) {
                $error = 0;
            }

            if (($error == 0) && ($this->settings['unsubscribeVerifyMessageUid'])) {
                $uri = $this->uriBuilder->reset()
                    ->setTargetPageUid($this->settings['unsubscribeVerifyMessageUid'])
                    ->build();
                $this->redirectToURI($uri); // oder: $this->forward($this->settings['unsubscribeMessageUid']);
            } else {
                $this->view->assign('error', $error);
            }
        }
    }

    /**
     * Prepare Array for emails and trigger sending of emails
     *
     * @param \Fixpunkt\FpNewsletter\Domain\Model\Log $log
     * @param boolean $isSubscribe
     *            Subscription or unsubscription?
     * @param boolean $isConfirmation
     *            verfify or confirmation?
     * @param boolean $toUser
     *            email to user?
     * @param boolean $toAdmin
     *            email to admin?
     * @param string $hash
     *            hash
     * @param integer $verifyUid
     *            UID of the verification page
     */
    protected function prepareEmail(\Fixpunkt\FpNewsletter\Domain\Model\Log &$log, $isSubscribe = true, $isConfirmation = false, $toUser = false, $toAdmin = false, $hash = '', $verifyUid = 0)
    {
        $genders = [
            "0" => '',
            "1" => $this->settings['gender']['mrs'],
            "2" => $this->settings['gender']['mr'],
            "3" => $this->settings['gender']['divers']
        ];
        $email = $log->getEmail();
        $from = trim($log->getFirstname() . ' ' . $log->getLastname());
        if (! $from) {
            $from = 'Subscriber';
        }
        $dataArray = [];
        $dataArray['uid'] = $log->getUid();
        $dataArray['sys_language_uid'] = $log->get_languageUid();
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
        $dataArray['settings'] = $this->settings;
        if ($toUser) {
            if ($isSubscribe) {
                if ($isConfirmation) {
                    $subject = $this->settings['email']['subscribedSubject'];
                    $template = 'Subscribed';
                } else {
                    $subject = $this->settings['email']['subscribeVerifySubject'];
                    $template = 'SubscribeVerify';
                }
            } else {
                if ($isConfirmation) {
                    $subject = $this->settings['email']['unsubscribedSubject'];
                    $template = 'Unsubscribed';
                } else {
                    $subject = $this->settings['email']['unsubscribeVerifySubject'];
                    $template = 'UnsubscribeVerify';
                }
            }
            $this->sendTemplateEmail(
                array($email => $from),
                array($this->settings['email']['senderMail'] => $this->settings['email']['senderName']),
                $subject,
                $template,
                $dataArray,
                false);
        }
        if ($toAdmin) {
            if ($isSubscribe) {
                if ($isConfirmation) {
                    $subject = $this->settings['email']['adminSubscribeSubject'];
                    $template = 'SubscribeToAdmin';
                } else {
                    $subject = $this->settings['email']['adminSubscribeSubject'];
                    $template = 'UserToAdmin';
                }
            } else {
                if ($isConfirmation) {
                    $subject = $this->settings['email']['adminUnsubscribeSubject'];
                    $template = 'UnsubscribeToAdmin';
                } else {
                    $subject = $this->settings['email']['adminUnsubscribeSubject'];
                    $template = 'UserToAdmin';
                }
            }
            $this->sendTemplateEmail(
                array($this->settings['email']['adminMail'] => $this->settings['email']['adminName']),
                array($this->settings['email']['senderMail'] => $this->settings['email']['senderName']),
                $subject,
                $template,
                $dataArray,
                true);
        }
    }

    /**
     * Send an email
     *
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
    protected function sendTemplateEmail(array $recipient, array $sender, $subject, $templateName, array $variables = array(), $toAdmin = false)
    {
        // Alternative: http://lbrmedia.net/codebase/Eintrag/extbase-60-standalone-template-renderer/
        // Das hier ist von hier: http://wiki.typo3.org/How_to_use_the_Fluid_Standalone_view_to_render_template_based_emails
        // und https://wiki.typo3.org/How_to_use_the_Fluid_Standalone_view_to_render_template_based_emails
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        if (($sys_language_uid > 0) && ! $toAdmin && ! $this->settings['email']['dontAppendL']) {
            $templateName .= $sys_language_uid;
        }
        $extensionName = $this->request->getControllerExtensionName();

        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
        $emailViewHtml = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $emailViewHtml->getRequest()->setControllerExtensionName($extensionName); // make sure f:translate() knows where to find the LLL file
        $emailViewHtml->setTemplateRootPaths($extbaseFrameworkConfiguration['view']['templateRootPaths']);
        $emailViewHtml->setLayoutRootPaths($extbaseFrameworkConfiguration['view']['layoutRootPaths']);
        $emailViewHtml->setPartialRootPaths($extbaseFrameworkConfiguration['view']['partialRootPaths']);
        $emailViewHtml->setTemplate('Email/' . $templateName . '.html');
        $emailViewHtml->setFormat('html');
        $emailViewHtml->assignMultiple($variables);
        $emailBodyHtml = $emailViewHtml->render();

        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
        $emailViewText = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $emailViewText->getRequest()->setControllerExtensionName($extensionName); // make sure f:translate() knows where to find the LLL file
        $emailViewText->setTemplateRootPaths($extbaseFrameworkConfiguration['view']['templateRootPaths']);
        $emailViewText->setLayoutRootPaths($extbaseFrameworkConfiguration['view']['layoutRootPaths']);
        $emailViewText->setPartialRootPaths($extbaseFrameworkConfiguration['view']['partialRootPaths']);
        $emailViewText->setTemplate('Email/' . $templateName . '.txt');
        $emailViewText->setFormat('txt');
        $emailViewText->assignMultiple($variables);
        $emailBodyText = $emailViewText->render();
        // echo "###" . $emailBodyText . '###';
        // return;

        /** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
        $message = $this->objectManager->get('TYPO3\\CMS\\Core\\Mail\\MailMessage');
        if (TYPO3_branch == '9.5') {
            $message->setTo($recipient);
            $message->setFrom($sender)->setSubject($subject);
            $message->setBody($emailBodyText, 'text/plain');
            $message->addPart($emailBodyHtml, 'text/html');
        } else {
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
        }
        $message->send();
        return $message->isSent();
    }
}
