<?php

declare(strict_types=1);

namespace Fixpunkt\FpNewsletter\Controller;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use Fixpunkt\FpNewsletter\Domain\Model\Log;
use Fixpunkt\FpNewsletter\Domain\Repository\LogRepository;
use Fixpunkt\FpNewsletter\Domain\Repository\FrontendUserRepository;
use Fixpunkt\FpNewsletter\Utility\HelpersUtility;

/**
 *
 * This file is part of the "Newsletter management" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 Kurt Gusbeth <k.gusbeth@fixpunkt.com>, fixpunkt für digitales GmbH
 *
 */

/**
 * LogController
 */
class LogController extends ActionController
{

    /**
     *
     * @var ConfigurationManagerInterface
     */
    protected $configurationManager;

    /*
     * Constructor
     * @param FrontendUserRepository $frontendUserRepository
     * @param LogRepository $logRepository
     * @param HelpersUtility $helpersUtility
     */
    public function __construct(protected FrontendUserRepository $frontendUserRepository, protected LogRepository $logRepository, protected HelpersUtility $helpersUtility)
    {
    }

    /**
     * Initializes the current action
     */
    public function initializeAction()
    {
        $tsSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        $tsSettings = $tsSettings['plugin.']['tx_fpnewsletter.']['settings.'];
        $originalSettings = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );
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
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        $logs = $this->logRepository->findAll();
        $this->view->assign('logs', $logs);
        return $this->htmlResponse();
    }

    /**
     * action new
     *
     * @param Log|null $log            Log-Entry
     * @param int|null $error          Error-Code
     * @param string|null $error_msg   Error-Message
     * @return ResponseInterface
     */
    public function newAction(Log $log = null, int $error = 0, string $error_msg = null): ResponseInterface
    {
        $genders = $this->helpersUtility->getGenders($this->settings['preferXlfFile'], $this->settings['gender']);
        $optional = [];
        $required = [];
        $optionalFields = $this->settings['optionalFields'];
        $requiredFields = $this->settings['optionalFieldsRequired'];
        if ($optionalFields) {
            $tmp = explode(',', (string) $optionalFields);
            foreach ($tmp as $field) {
                $optional[trim($field)] = 1;
                $required[trim($field)] = 0;
            }
        }
        if ($requiredFields) {
            $tmp = explode(',', (string) $requiredFields);
            foreach ($tmp as $field) {
                $required[trim($field)] = 1;
            }
        }
        if ($log && $log->getUid()) {
            // wenn man von "create" her kommt, muss der Eintrag validiert werden
			$securityhash = $this->request->hasArgument('securityhash') ? $this->request->getArgument('securityhash') : '';
            if (empty($securityhash) || !is_string($securityhash) || !hash_equals($log->getSecurityhash(), $securityhash)) {
                $error = 1;
                $log = NULL;
            }
        }
        if (! $log) {
            $log = GeneralUtility::makeInstance(Log::class);
        }
        if (!$log->getEmail() && $this->settings['parameters']['email']) {
            $email = $_GET[$this->settings['parameters']['email']] ?? '';
            if (! $email) {
                $email = $_POST[$this->settings['parameters']['email']] ?? '';
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
        if (!$error && $this->settings['checkForRequiredExtensions'] && $this->settings['table']=='tt_address') {
            if (!ExtensionManagementUtility::isLoaded('tt_address')) {
                $error = 20;
            }
            if ((intval($this->settings['html'])>-1 || $this->settings['categoryOrGroup'])
                && !ExtensionManagementUtility::isLoaded('mail')) {
                $error = 21;
            }
        }
        $this->view->assign('genders', $genders);
        $this->view->assign('optional', $optional);
        $this->view->assign('required', $required);
        $this->view->assign('error', $error);
        $this->view->assign('error_msg', $error_msg);
        $this->view->assign('log', $log);
        return $this->htmlResponse();
    }

    /**
     * action form, cachable
     *
     * @return ResponseInterface
     */
    public function formAction(): ResponseInterface
    {
        $genders = $this->helpersUtility->getGenders($this->settings['preferXlfFile'], $this->settings['gender']);
        $optional = [];
        $required = [];
        $optionalFields = $this->settings['optionalFields'];
        $requiredFields = $this->settings['optionalFieldsRequired'];
        if ($optionalFields) {
            $tmp = explode(',', (string) $optionalFields);
            foreach ($tmp as $field) {
                $optional[trim($field)] = 1;
                $required[trim($field)] = 0;
            }
        }
        if ($requiredFields) {
            $tmp = explode(',', (string) $requiredFields);
            foreach ($tmp as $field) {
                $required[trim($field)] = 1;
            }
        }
        $this->settings['mathCAPTCHA'] = 0;
        $this->view->assign('genders', $genders);
        $this->view->assign('optional', $optional);
        $this->view->assign('required', $required);
        return $this->htmlResponse();
    }

    /**
     * action resend
     *
     * @return ResponseInterface
     */
    public function resendAction(): ResponseInterface
    {
        $log = null;
        $subscribeVerifyUid = $this->settings['subscribeVerifyUid'];
        if (! $subscribeVerifyUid) {
            // Fallback
            $subscribeVerifyUid = intval($GLOBALS["TSFE"]->id);
        }
        $email = $this->request->hasArgument('email') ? $this->request->getArgument('email') : '';
        if ($email && $subscribeVerifyUid) {
            $maxDate = time() - 86400 * $this->settings['daysExpire'];
            $storagePidsArray = $this->logRepository->getStoragePids();
            $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
            $sys_language_uid = intval($languageAspect->getId());
            $requestLanguage = $this->request->getAttribute('language');
            $requestLocale = $requestLanguage->getLocale();
            $requestLanguageCode = $requestLocale->getLanguageCode();
            if ($sys_language_uid > 0 && $this->settings['languageMode']) {
                $log = $this->logRepository->getByEmailAndPid($email, $storagePidsArray, $sys_language_uid, $maxDate);
            } else {
                $log = $this->logRepository->getByEmailAndPid($email, $storagePidsArray, 0, $maxDate);
            }
            if ($log) {
                if (intval($subscribeVerifyUid) == intval($GLOBALS["TSFE"]->id)) {
                    $pi = strtolower($this->request->getPluginName());  // z.B. 'resend';
                } else {
                    $pi = 'verify';
                }
                $this->helpersUtility->prepareEmail($log, $this->settings, $this->getViewArray(), true, false, false,true, false, $log->getSecurityhash(), intval($subscribeVerifyUid), $pi, $requestLanguageCode);
            }
        }
        $this->view->assign('email', $email);
        $this->view->assign('log', $log);
        return $this->htmlResponse();
    }

    /**
     * action editEmail: request email for edit data
     *
     * @return ResponseInterface
     */
    public function editEmailAction(): ResponseInterface
    {
        $log = null;
        $error = 0;
        $email = $this->request->hasArgument('email') ? $this->request->getArgument('email') : '';
        if ($email) {
            // send email with a link to an edit page
            $dbuidext = 0;
            if (GeneralUtility::validEmail($email)) {
                $storagePidsArray = $this->logRepository->getStoragePids();
                $pid = intval($storagePidsArray[0]);
                if ($this->settings['table'] == 'tt_address' || $this->settings['table'] == 'fe_users') {
                    $dbuidext = $this->logRepository->getUidFromExternal($email, $pid, $this->settings['table']);
                }
                if (!$dbuidext) {
                    $error = 7;
                } else {
                    $log = GeneralUtility::makeInstance(Log::class);
                    $log->setPid($pid);
                    $log->setEmail($email);
                    $hash = $this->helpersUtility->setHashAndLanguage($log, intval($this->settings['languageMode']));
                    $requestLanguage = $this->request->getAttribute('language');
                    $requestLocale = $requestLanguage->getLocale();
                    $requestLanguageCode = $requestLocale->getLanguageCode();
                    $log->setStatus(10);
                    $this->logRepository->add($log);
                    $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
                    $persistenceManager->persistAll();
                    $error = 51;
                    $this->helpersUtility->prepareEmail($log, $this->settings, $this->getViewArray(), false, false, true,true, false, $hash, intval($this->settings['editUid']), 'email', $requestLanguageCode);
                    // reset log entry
                    $log = null;
                }
                if ($this->settings['disableErrorMsg'] && ($error == 7)) {
                    $error = 51;
                }
            } else {
                $error = 8;
            }
        }
        $editUid = intval($this->settings['editUid']);
        if (! $editUid) {
            // Fallback
            $editUid = intval($GLOBALS["TSFE"]->id);
        }
        if ($editUid == intval($GLOBALS["TSFE"]->id)) {
            $pi = strtolower($this->request->getPluginName());  // z.B. 'editemail' oder 'edit;
        } else {
            $pi = 'email';
        }
        $this->view->assign('pi', $pi);
        $this->view->assign('log', $log);
        $this->view->assign('error', $error);
        return $this->htmlResponse();
    }

    /**
     * action edit data
     *
     * @return ResponseInterface
     */
    public function editAction(): ResponseInterface
    {
        $genders = $this->helpersUtility->getGenders($this->settings['preferXlfFile'], $this->settings['gender']);
        $optional = [];
        $required = [];
        $optionalFields = $this->settings['optionalFields'];
        $requiredFields = $this->settings['optionalFieldsRequired'];
        if ($optionalFields) {
            $tmp = explode(',', (string) $optionalFields);
            foreach ($tmp as $field) {
                $optional[trim($field)] = 1;
                $required[trim($field)] = 0;
            }
        }
        if ($requiredFields) {
            $tmp = explode(',', (string) $requiredFields);
            foreach ($tmp as $field) {
                $required[trim($field)] = 1;
            }
        }
        $catOrderBy = ($this->settings['categoryOrderBy'] == 'title') ? 'title' : '';
        if (!$catOrderBy) {
            $catOrderBy = ($this->settings['categoryOrderBy'] == 'sorting') ? 'sorting' : 'uid';
        }
        $log = null;
        $error = 0;
        $dbuid = 0;
        $table = $this->settings['table'];
        $groups = [];
        $own_groups = [];
        $uid = intval($this->request->hasArgument('uid')) ? $this->request->getArgument('uid') : 0;
        $hash = ($this->request->hasArgument('hash')) ? $this->request->getArgument('hash') : '';
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        if (! $uid || ! $hash) {
            $this->view->assign('error', 1);
        } else {
            if ($sys_language_uid > 0 && $this->settings['languageMode']) {
                $log = $this->logRepository->findAnotherByUid($uid, $sys_language_uid);
            } else {
                $log = $this->logRepository->findOneByUid($uid);
            }
            if ($log) {
                $dbuid = $log->getUid();
                $dbemail = $log->getEmail();
            }
            if (!$dbuid) {
                $error = 2;
            } elseif ($log->getStatus() < 10) {
                $error = 2;
            } else {
                $error = $this->helpersUtility->checkIfValid($log, $hash, $this->settings['daysExpire']);
                if (!$error) {
                    $dbuidext = $this->logRepository->getExternalUid($dbemail, $log->getPid(), $table, $this->settings['searchPidMode']);
                    if ($dbuidext) {
                        $user = $this->logRepository->getUserFromExternal($dbuidext, $table);
                        $log->setTitle($user['title']);
                        $log->setFirstname($user['first_name']);
                        $log->setLastname($user['last_name']);
                        $log->setAddress($user['address']);
                        $log->setZip($user['zip']);
                        $log->setCity($user['city']);
                        $log->setCountry($user['country']);
                        $log->setFax($user['fax']);
                        $log->setWww($user['www']);
                        $log->setCompany($user['company']);
                        if ($table == 'tt_address') {
                            $log->setRegion($user['region']);
                            $log->setPhone($user['phone']);
                            $log->setMobile($user['mobile']);
                            $log->setPosition($user['position']);
                            $gender = 0;
                            if ($user['gender'] == 'f') $gender = 1;
                            elseif ($user['gender'] == 'm') $gender = 2;
                            elseif ($user['gender'] == 'v') $gender = 3;
                            $log->setGender($gender);
                        } elseif ($table == 'fe_users') {
                            $log->setPhone($user['telephone']);
                            if ($this->settings['newsletterExtension'] == 'mail') {
                                $gender = 0;
                                if ($user['mail_salutation'] == $this->settings['gender']['mrs']) $gender = 1;
                                elseif ($user['mail_salutation'] == $this->settings['gender']['mr']) $gender = 2;
                                elseif ($user['mail_salutation'] == $this->settings['gender']['divers']) $gender = 3;
                                $log->setGender($gender);
                            }
                        }
                        if ($table == 'tt_address' || $this->settings['newsletterExtension'] == 'mail') {
                            $groups = $this->logRepository->getAllCats($catOrderBy);
                            $own_groups_tmp = $this->logRepository->getOwnCats($dbuidext, $table);
                            foreach ($own_groups_tmp as $tmp) {
                                $own_groups[] = $tmp['uid_local'];
                            }
                        } elseif ($table == 'fe_users') {
                            $groups = $this->logRepository->getAllGroups($catOrderBy);
                            $own_groups = explode(',', (string) $user['usergroup']);
                        }
                        if (!$this->settings['categoryMode']) {
                            // nur angegebene Kategorien erlauben
                            $dmCat = str_replace(' ', '', (string) $this->settings['categoryOrGroup']);
                            $dmCatArr = explode(',', $dmCat);
                            foreach ($groups as $key => $array) {
                                if (!in_array($array['uid'], $dmCatArr)) {
                                    unset($groups[$key]);
                                }
                            }
                        }
                        foreach ($groups as $key => $array) {
                            if (in_array($array['uid'], $own_groups)) {
                                $groups[$key]['own'] = true;
                            }
                        }
                    } else {
                        $error = 1;
                    }
                }
            }
        }
        $this->view->assign('genders', $genders);
        $this->view->assign('optional', $optional);
        $this->view->assign('required', $required);
        $this->view->assign('groups', $groups);
        $this->view->assign('log', $log);
        $this->view->assign('error', $error);
        return $this->htmlResponse();
    }

    /**
     * action update
     *
     * @return ResponseInterface
     */
    public function updateAction(Log $log): ResponseInterface
    {
        $error = 0;
        $table = $this->settings['table'];
        $categories = ($this->request->hasArgument('categories')) ? $this->request->getArgument('categories') : '';
        $securityhash = $this->request->hasArgument('hash') ? $this->request->getArgument('hash') : '';
        if (empty($securityhash) || !is_string($securityhash) || !hash_equals($log->getSecurityhash(), $securityhash)) {
            $error = 3;
        } else {
            $new_categories = [];
            if (is_array($categories)) {
                foreach ($categories as $key => $value) {
                    if ($value) {
                        $new_categories[] = $value;
                    }
                }
            } else {
                $new_categories[] = $categories;
            }
            $log->setCategories(implode(",", $new_categories));
            $log->setStatus(11);
            $this->logRepository->update($log);
            $dbemail = $log->getEmail();
            $dbuidext = $this->logRepository->getExternalUid($dbemail, $log->getPid(), $table, $this->settings['searchPidMode']);
            $salutation = $this->helpersUtility->getSalutation(intval($log->getGender()), $this->settings['gender']);
            if ($dbuidext) {
                if ($table == 'tt_address') {
                    $this->logRepository->updateInTtAddress(
                        $log, intval($this->settings['html']), $dbuidext, $salutation, $this->settings['additionalTtAddressFields']
                    );
                } elseif ($table == 'fe_users') {
                    $this->logRepository->updateInFeUsers(
                        $log, $dbuidext, $this->settings['newsletterExtension']
                    );
                }
            } else {
                $error = 1;
            }
        }
        $this->view->assign('error', $error);
        $this->view->assign('log', $log);
        return $this->htmlResponse();
    }

    /**
     * action subscribeExt
     *
     * @return ResponseInterface
     */
    public function subscribeExtAction(): ResponseInterface
    {
        if ($this->settings['parameters']['active'] && $this->settings['parameters']['email']) {
            $pactive = explode('|', (string) $this->settings['parameters']['active']);
            $active = $_POST[$pactive[0]] ?? [];
            if ($active[$pactive[1]][$pactive[2]]) {
                $pemail = explode('|', (string) $this->settings['parameters']['email']);
                $email = $_POST[$pemail[0]] ?? [];
                $email = $email[$pemail[1]][$pemail[2]];
                if ($email) {
                    $storagePidsArray = $this->logRepository->getStoragePids();
                    $pid = intval($storagePidsArray[0]);
                    $log = GeneralUtility::makeInstance(Log::class);
                    $log->setPid($pid);
                    $log->setEmail($email);
                    $log->setGdpr(true);
					return (new ForwardResponse('create'))
					->withControllerName('Log')
					->withExtensionName('fp_newsletter')
					->withArguments(['log' => $log]);
                }
            }
        }
        return $this->htmlResponse();
    }

    /**
     * action create
     *
     * @param Log|null $log
     * @return ResponseInterface
     * @throws StopActionException
     * @throws SiteNotFoundException
     */
    public function createAction(Log $log = null): ResponseInterface
    {
        if (!$log) {
            $uri = $this->uriBuilder->uriFor('new', ['error_msg' => 'Missing Log entry! / Log-Parameter fehlt!']);
            return $this->responseFactory->createResponse(307)
                ->withHeader('Location', $uri);
        }
        //if ($log->getGdpr()) { $log->setGdpr(true); }
        $requestLanguage = $this->request->getAttribute('language');
        $requestLocale = $requestLanguage->getLocale();
        $requestLanguageCode = $requestLocale->getLanguageCode();
        $hash = $this->helpersUtility->setHashAndLanguage($log, intval($this->settings['languageMode']));
        $log->setStatus(0);
        if ($log->getUid() > 0) {
            $this->logRepository->update($log);
        } else {
            $this->logRepository->add($log);
        }
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $persistenceManager->persistAll();

        $error = 0;
        $subscribeVerifyUid = $this->settings['subscribeVerifyUid'];
        if (! $subscribeVerifyUid) {
            // Fallback
            $subscribeVerifyUid = intval($GLOBALS["TSFE"]->id);
        }
        $email = $log->getEmail();
        $dbuidext = 0;
        if (GeneralUtility::validEmail($email)) {
            if ($this->settings['table'] == 'tt_address' || $this->settings['table'] == 'fe_users') {
                $dbuidext = $this->logRepository->getUidFromExternal($email, $log->getPid(), $this->settings['table']);
            }
        } else {
            $error = 8;
        }
        if ($this->settings['reCAPTCHA_site_key'] && $this->settings['reCAPTCHA_secret_key']) {
            $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);

            $url = "https://www.google.com/recaptcha/api/siteverify";
            $additionalOptions = [
                'form_params' => [
                    'secret' => $this->settings['reCAPTCHA_secret_key'],
                    'response' => $log->getRetoken()
                ]
            ];

            $request = $requestFactory->request($url, 'POST', $additionalOptions);

            if ($request->getStatusCode() === 200) {
                $resultBody = json_decode((string) $request->getBody()->getContents(), true);
                if (!$resultBody['success'])
                    $error = 9;

            } else {
                $error = 9;
            }
        }
        if ($this->settings['mathCAPTCHA']) {
            $tmp_error = $this->helpersUtility->checkMathCaptcha(intval($log->getMathcaptcha()));
            if ($tmp_error > 0) {
                $error = $tmp_error;
            }
        }
        if ($this->settings['honeypot'] && $log->getExtras()) {
            // Der Honigtopf ist gefüllt
            $error = 10;
        }
        $error_msg = '';
        $customValidatorEvent = GeneralUtility::makeInstance(\Fixpunkt\FpNewsletter\Events\ValidateEvent::class);
        if(!$customValidatorEvent->isValid()) {
            $error = 901;
            $error_msg = $customValidatorEvent->getMessage();
        }
        if ($dbuidext > 0) {
            $error = 6;
            $log->setStatus(6);
            $this->logRepository->update($log);
            $persistenceManager->persistAll();
        }
        if (! $error) {
            $log->setStatus(1);
            $this->logRepository->update($log);
            $persistenceManager->persistAll();
            $toAdmin = ($this->settings['email']['adminMail'] && $this->settings['email']['adminMailBeforeVerification']);
            if (intval($subscribeVerifyUid) == intval($GLOBALS["TSFE"]->id)) {
                $pi = strtolower($this->request->getPluginName());  // z.B. 'new';
            } else {
                $pi = 'verify';
            }
            $this->helpersUtility->prepareEmail($log, $this->settings, $this->getViewArray(), true, false, false,true, $toAdmin, $hash, intval($subscribeVerifyUid), $pi, $requestLanguageCode);
        } else if ($error >= 8) {
            $uri = $this->uriBuilder->uriFor('new', [
                'log' => $log,
                'error' => $error,
                'error_msg' => $error_msg,
                'securityhash' => $log->getSecurityhash()
            ]);
            return $this->responseFactory->createResponse(307)
                ->withHeader('Location', $uri);
        }
        if ($this->settings['disableErrorMsg'] && ($error == 5 || $error == 6)) {
            $error = 0;
        }

        if (($error == 0) && ($this->settings['subscribeMessageUid'])) {
            $uri = $this->uriBuilder->reset()
                ->setTargetPageUid((int) $this->settings['subscribeMessageUid'])
                ->build();
            return $this->responseFactory->createResponse(307)
                ->withHeader('Location', $uri);
        } else {
            $this->view->assign('error', $error);
        }
        return $this->htmlResponse();
    }

    /**
     * action unsubscribe with form
     *
     * @param Log|null $log
     *            Log-Entry
     * @param int $error
     *            Error-Code
     * @return ResponseInterface
     * @throws \Exception
     */
    public function unsubscribeAction(Log $log = null, int $error = 0): ResponseInterface
    {
        $storagePidsArray = $this->logRepository->getStoragePids();
        $pid = intval($storagePidsArray[0]);
        if ($log && $log->getUid()) {
            // es ist nicht klar, woher dieser securityhash her kommt, denn im Template ist er nicht drin.
			$securityhash = $this->request->hasArgument('securityhash') ? $this->request->getArgument('securityhash') : '';
            if (empty($securityhash) || !is_string($securityhash) || !hash_equals($log->getSecurityhash(), $securityhash)) {
                $error = 1;
                $log = NULL;
            }
        }
        if (! $log) {
            $log = GeneralUtility::makeInstance(Log::class);
            $log->setPid($pid);
            // default E-Mail holen, falls log noch nicht definiert ist; default email from unsubscribeLuxAction
            $email = $this->request->hasArgument('defaultEmail') ? $this->request->getArgument('defaultEmail') : '';
            if (! $email && $this->settings['parameters']['email']) {
                if (isset($_GET[$this->settings['parameters']['email']])) {
                    $email = $_GET[$this->settings['parameters']['email']];
                }
                if (! $email && isset($_POST[$this->settings['parameters']['email']])) {
                    $email = $_POST[$this->settings['parameters']['email']];
                }
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
        if (intval($this->settings['unsubscribeUid']) == intval($GLOBALS["TSFE"]->id)) {
            $pi = strtolower($this->request->getPluginName());  // z.B. 'unsubscribelux';
        } else {
            $pi = 'unsubscribe';
        }
        $unsubscribeUid = ($this->settings['unsubscribeUid']) ? : intval($GLOBALS["TSFE"]->id);
        $this->view->assign('unsubscribeUid', $unsubscribeUid);
        $this->view->assign('plugin', $pi);
        $this->view->assign('log', $log);
        $this->view->assign('error', $error);
        return $this->htmlResponse();
    }

    /**
     * action unsubscribe with Luxletter link
     *
     * @return ResponseInterface
     */
    public function unsubscribeLuxAction(): ResponseInterface
    {
        $error = 0;
        if (isset($_GET['tx_luxletter_fe'])) {
            $luxletterParams = $_GET['tx_luxletter_fe'];
        } else {
            $luxletterParams = null;
        }
        if (!is_array($luxletterParams)) {
            $error = 10;
        } else {
            $user = $luxletterParams['user'];
            $newsletter = $luxletterParams['newsletter'];
            $hash = $luxletterParams['hash'];
            if (!$user || !$newsletter || !$hash) {
                $error = 10;
            } else {
                $userArray = $this->logRepository->getUserFromExternal($user, 'fe_users');
                $newsletterArray = $this->logRepository->getUserFromExternal($newsletter, 'tx_luxletter_domain_model_newsletter');
                if (is_array($userArray) && isset($userArray['email']) && isset($userArray['usergroup']) &&
                    is_array($newsletterArray) && isset($newsletterArray['receivers'])) {
                    // ist user in versendeter newsletter-Gruppe?
                    $match = false;
                    $usergroups = explode(",", (string) $userArray['usergroup']);
                    $receivers = explode(",", (string) $newsletterArray['receivers']);
                    foreach ($usergroups as $group) {
                        foreach ($receivers as $receiver) {
                            if ($group == $receiver) {
                                $match = true;
                                break;
                            }
                        }
                    }
                    if ($match) {
                        // stimmt der angegebene hash überein?
                        if ($this->helpersUtility->checkLuxletterHash($userArray, $hash)) {
                            // Abmeldung kann beginnen!
                            if ($this->settings['unsubscribeMode'] == 1) {
                                $uri = $this->uriBuilder->reset()
                                    ->uriFor(
                                        'unsubscribe',
                                        [
                                            'defaultEmail' => $userArray['email']
                                        ],
                                        'Log',
                                        null,
                                        'unsubscribelux'
                                    );
                                return $this->responseFactory->createResponse(307)
                                    ->withHeader('Location', $uri);
                            } else {
                                // unsubscribe user now
                                $GLOBALS['TSFE']->fe_user->setKey('ses', 'authLux', $hash);
                                $GLOBALS['TSFE']->fe_user->storeSessionData();
                                $uri = $this->uriBuilder->reset()
                                    ->uriFor(
                                        'delete',
                                        [
                                            'user' => $userArray
                                        ],
                                        'Log',
                                        null,
                                        'unsubscribelux'
                                    );
                                return $this->responseFactory->createResponse(307)
                                    ->withHeader('Location', $uri);
                            }
                        } else {
                            $error = 10;
                        }
                    } else {
                        $error = 11;
                    }
                } else {
                    if (!isset($userArray['email'])) {
                        $error = 11;
                    } else {
                        $error = 10;
                    }
                }
            }
        }
        $this->view->assign('error', $error);
        return $this->htmlResponse();
    }

    /**
     * action unsubscribe with mail link
     *
     * @return ResponseInterface
     */
    public function unsubscribeMailAction(): ResponseInterface
    {
        $error = 0;
        if (isset($_GET[ $this->settings['parameters']['email'] ])) {
            $email = $_GET[ $this->settings['parameters']['email'] ];
        } else {
            $email = null;
        }
        if (isset($_GET[ $this->settings['parameters']['authcode'] ])) {
            $hash = $_GET[ $this->settings['parameters']['authcode'] ];
        } else {
            $hash = null;
        }
        if (!$email || !$hash) {
            $error = 10;
        } else {
            if (GeneralUtility::validEmail($email)) {
                $storagePidsArray = $this->logRepository->getStoragePids();
                $pid = intval($storagePidsArray[0]);
                if ($this->settings['table'] == 'tt_address' || $this->settings['table'] == 'fe_users') {
                    $user = $this->logRepository->getUidFromExternal($email, $pid, $this->settings['table']);
                }
                if (!$user) {
                    $error = 11;
                } else {
                    $userArray = $this->logRepository->getUserFromExternal($user, $this->settings['table']);
                    if (is_array($userArray) && isset($userArray['email'])) {
                        // stimmt der angegebene hash überein?
                        if ($this->helpersUtility->checkMailHash($userArray, $hash, $this->settings['authCodeFields'])) {
                            // Abmeldung kann beginnen!
                            if ($this->settings['unsubscribeMode'] == 1) {
                                $uri = $this->uriBuilder->reset()
                                    ->uriFor(
                                        'unsubscribe',
                                        [
                                            'defaultEmail' => $userArray['email']
                                        ],
                                        'Log',
                                        null,
                                        'unsubscribemail'
                                    );
                                return $this->responseFactory->createResponse(307)
                                    ->withHeader('Location', $uri);
                            } else {
                                // unsubscribe user now
                                $GLOBALS['TSFE']->fe_user->setKey('ses', 'authMail', $hash);
                                $GLOBALS['TSFE']->fe_user->storeSessionData();
                                $uri = $this->uriBuilder->reset()
                                    ->uriFor(
                                        'delete',
                                        [
                                            'user' => $userArray
                                        ],
                                        'Log',
                                        null,
                                        'unsubscribemail'
                                    );
                                return $this->responseFactory->createResponse(307)
                                    ->withHeader('Location', $uri);
                            }
                        } else {
                            $error = 10;
                        }
                    } else {
                        $error = 10;
                    }
                }
            } else {
                $error = 8;
            }
        }
        $this->view->assign('error', $error);
        return $this->htmlResponse();
    }

    /**
     * action delete a user from the DB: unsubscribe him from the newsletter
     *
     * @param Log|null $log
     * @param array $user tt_address oder fe_users Daten
     * @return ResponseInterface
     */
    public function deleteAction(Log $log = null, array $user = []): ResponseInterface
    {
        $error = 0;
        $messageUid = 0;
        $skipCaptchaTest = false;
        $checkSession = false;
        $hash = '';
        $storagePidsArray = $this->logRepository->getStoragePids();
        if ($log) {
            // if we come from new/unsubscribeAction: an email must be present, but no UID!
            if ($log->getUid() || !$log->getEmail()) {
                $error = 1;
            }
        } elseif (isset($user['email'])) {
            // we came from unsubscribeLuxAction: an email and session must be present too!
            $log = GeneralUtility::makeInstance(Log::class);
            $log->setEmail($user['email']);
            $log->setPid(intval($user['pid']));
            $checkSession = true;
        } else {
            $error = 1;
        }
        if ($error == 0) {
            $email = $log->getEmail();
            $pid = $log->getPid();
            if (! $pid) {
                $pid = intval($storagePidsArray[0]);
            }
            // zum testen: var_dump ($storagePidsArray);
            $requestLanguage = $this->request->getAttribute('language');
            $requestLocale = $requestLanguage->getLocale();
            $requestLanguageCode = $requestLocale->getLanguageCode();
            $hash = $this->helpersUtility->setHashAndLanguage($log, intval($this->settings['languageMode']));
            $dbuidext = 0;

            if (GeneralUtility::validEmail($email)) {
                $dbuidext = $this->logRepository->getExternalUid($email, $pid, $this->settings['table'], $this->settings['searchPidMode']);
                // echo "uid $dbuidext mit $email, $pid (".$this->settings['searchPidMode'].") in " . $this->settings['table'];
                if ($dbuidext > 0) {
                    $extAddress = $this->logRepository->getUserFromExternal($dbuidext, $this->settings['table']);
                    $log->setLastname($extAddress['last_name']);
                    $log->setFirstname($extAddress['first_name']);
                    $log->setTitle($extAddress['title']);
                    if (intval($log->getPid()) != intval($extAddress['pid'])) {
                        // wenn $storagePidsArray[0] falsch war, dann jetzt korrigieren
                        $log->setPid(intval($extAddress['pid']));
                    }
                    if ($this->settings['table'] == 'tt_address' && $extAddress['gender']) {
                        $gender = 0;
                        if ($extAddress['gender'] == 'f') $gender = 1;
                        elseif ($extAddress['gender'] == 'm') $gender = 2;
                        elseif ($extAddress['gender'] == 'v') $gender = 3;
                        $log->setGender($gender);
                    } elseif ($this->settings['newsletterExtension'] == 'mail' && $this->settings['table'] == 'fe_users' && $extAddress['mail_salutation']) {
                        $gender = 0;
                        if ($extAddress['mail_salutation'] == $this->settings['gender']['mrs']) $gender = 1;
                        elseif ($extAddress['mail_salutation'] == $this->settings['gender']['mr']) $gender = 2;
                        elseif ($extAddress['mail_salutation'] == $this->settings['gender']['divers']) $gender = 3;
                        $log->setGender($gender);
                    }
                }
            } else {
                $error = 8;
            }
            if ($this->settings['table'] && ($dbuidext == 0)) {
                $error = 7;
            }
            if ($checkSession) {
                // wenn man von unsubscribeLux kommt, muss die Session noch überprüft werden
                $a = $GLOBALS['TSFE']->fe_user->getKey('ses', 'authLux');
                if ($a) {
                    // hash von unsubscribeLux ist vorhanden!
                    $GLOBALS['TSFE']->fe_user->setKey('ses', 'authLux', '');
                    $GLOBALS['TSFE']->fe_user->storeSessionData();
                    if ($this->helpersUtility->checkLuxletterHash($user, $a)) {
                        $skipCaptchaTest = true;
                    } else {
                        $error = 1;
                    }
                } else {
                    $a = $GLOBALS['TSFE']->fe_user->getKey('ses', 'authMail');
                    if ($a) {
                        // hash von unsubscribeMail ist vorhanden!
                        $GLOBALS['TSFE']->fe_user->setKey('ses', 'authMail', '');
                        $GLOBALS['TSFE']->fe_user->storeSessionData();
                        if ($this->helpersUtility->checkMailHash($user, $a, $this->settings['authCodeFields'])) {
                            $skipCaptchaTest = true;
                        } else {
                            $error = 1;
                        }
                    }
                }
                if (!$a) {
                    $error = 1;
                }
            }
            if (!$skipCaptchaTest) {
                if ($this->settings['reCAPTCHA_site_key'] && $this->settings['reCAPTCHA_secret_key']) {
                    $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);

                    $url = "https://www.google.com/recaptcha/api/siteverify";
                    $additionalOptions = [
                        'form_params' => [
                            'secret' => $this->settings['reCAPTCHA_secret_key'],
                            'response' => $log->getRetoken()
                        ]
                    ];

                    $request = $requestFactory->request($url, 'POST', $additionalOptions);

                    if ($request->getStatusCode() === 200) {
                        $resultBody = json_decode((string) $request->getBody()->getContents(), true);
                        if (!$resultBody['success'])
                            $error = 9;

                    } else {
                        $error = 9;
                    }
                }
                if ($this->settings['mathCAPTCHA']) {
                    $tmp_error = $this->helpersUtility->checkMathCaptcha(intval($log->getMathcaptcha()));
                    if ($tmp_error > 0) {
                        $error = $tmp_error;
                    }
                }
            }
            if ($this->settings['honeypot'] && $log->getExtras()) {
                // Der Honigtopf ist gefüllt
                $error = 10;
            }
            if ($error == 7) {
                $log->setStatus(8);
            } else {
                $log->setStatus(0);
            }
            if ($log->getUid() > 0) {
                $this->logRepository->update($log);
            } else {
                $this->logRepository->add($log);
            }
            $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
            $persistenceManager->persistAll();
        }

        if (! $error) {
            if ($this->settings['doubleOptOut']) {
                $unsubscribeVerifyUid = intval($this->settings['unsubscribeVerifyUid']);
                if (! $unsubscribeVerifyUid) {
                    // Fallback
                    $unsubscribeVerifyUid = intval($GLOBALS["TSFE"]->id);
                }
                if ($unsubscribeVerifyUid == intval($GLOBALS["TSFE"]->id)) {
                    $pi = strtolower($this->request->getPluginName());  // z.B. 'unsubscribe' oder 'unsubscribelux';
                } else {
                    $pi = 'verifyunsubscribe';
                }
                $log->setStatus(3);
                $this->logRepository->update($log);
                $persistenceManager->persistAll();
                $toAdmin = ($this->settings['email']['adminMail'] && $this->settings['email']['adminMailBeforeVerification']);
                $this->helpersUtility->prepareEmail($log, $this->settings, $this->getViewArray(), false, false, false, true, $toAdmin, $hash, $unsubscribeVerifyUid, $pi, $requestLanguageCode);
                $messageUid = (int) $this->settings['unsubscribeMessageUid'];
            } else {
                if ($this->settings['table'] == 'tt_address' || $this->settings['table'] == 'fe_users') {
                    $this->deleteThisUser($dbuidext);
                }
                $log->setStatus(7);
                $this->logRepository->update($log);
                $persistenceManager->persistAll();
                if (($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']) || ($this->settings['email']['enableConfirmationMails'])) {
                    $toAdmin = ($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']);
                    $this->helpersUtility->prepareEmail($log, $this->settings, $this->getViewArray(), false, true, false, filter_var($this->settings['email']['enableConfirmationMails'], FILTER_VALIDATE_BOOLEAN), $toAdmin, $hash, 0, '', $requestLanguageCode);
                }
                $messageUid = (int) $this->settings['unsubscribeVerifyMessageUid'];
            }
        } else if ($error >= 8) {
            $uri = $this->uriBuilder->reset()
                ->uriFor(
                    'unsubscribe',
                    [
                        'log' => $log,
                        'error' => $error,
                        'securityhash' => $log->getSecurityhash()
                    ]
                );
            return $this->responseFactory->createResponse(307)
                ->withHeader('Location', $uri);
        }
        if ($this->settings['disableErrorMsg'] && ($error == 7)) {
            $error = 0;
        }

        if (($error == 0) && ($messageUid)) {
            $uri = $this->uriBuilder->reset()
                ->setTargetPageUid($messageUid)
                ->build();
            return $this->responseFactory->createResponse(307)
                ->withHeader('Location', $uri);
        } else {
            $this->view->assign('error', $error);
        }
        return $this->htmlResponse();
    }

    /**
     * action verify Anmeldung
     *
     * @return ResponseInterface
     */
    public function verifyAction(): ResponseInterface
    {
        $error = 0;
        $dbuid = 0;
        $html = intval($this->settings['html']);
        $dmCat = str_replace(' ', '', (string) $this->settings['categoryOrGroup']);
        $uid = intval($this->request->hasArgument('uid')) ? $this->request->getArgument('uid') : 0;
        $hash = ($this->request->hasArgument('hash')) ? $this->request->getArgument('hash') : '';
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        $requestLanguage = $this->request->getAttribute('language');
        $requestLocale = $requestLanguage->getLocale();
        $requestLanguageCode = $requestLocale->getLanguageCode();
        if (! $uid || ! $hash) {
            $this->view->assign('error', 1);
        } else {
            if ($sys_language_uid > 0 && $this->settings['languageMode']) {
                $log = $this->logRepository->findAnotherByUid($uid, $sys_language_uid);
            } else {
                $log = $this->logRepository->findOneByUid($uid);
            }
            if ($log) {
                $dbuid = $log->getUid();
                $this->view->assign('address', $log);
            }
            if (! $dbuid) {
                $error = 2;
            } elseif ($log->getStatus() == 2) {
                $error = 5;
            } else {
                $error = $this->helpersUtility->checkIfValid($log, $hash, $this->settings['daysExpire']);
                if (!$error) {
                    // $dbstatus = $log->getStatus();
                    $dbemail = $log->getEmail();
                    $dbuidext = 0;
                    if ($this->settings['table'] == 'tt_address' || $this->settings['table'] == 'fe_users') {
                        $dbuidext = $this->logRepository->getUidFromExternal($dbemail, $log->getPid(), $this->settings['table']);
                    }
                    if ($dbuidext > 0) {
                        $error = 6;
                    } else {
                        $log->setStatus(2);
                        $this->logRepository->update($log);
                        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
                        $persistenceManager->persistAll();
                        $success = 0;
                        $salutation = $this->helpersUtility->getSalutation(intval($log->getGender()), $this->settings['gender']);
                        if ($dmCat) {
                            $dmCatArr = explode(',', $dmCat);
                        } else {
                            $dmCatArr = [];
                        }
                        if ($this->settings['table'] == 'tt_address') {
                            $success = $this->logRepository->insertInTtAddress(
                                $log, $html, $dmCatArr, $salutation, $this->settings['additionalTtAddressFields']
                            );
                        } else if ($this->settings['table'] == 'fe_users' && $this->settings['password']) {
                            $frontendUser = new \Fixpunkt\FpNewsletter\Domain\Model\FrontendUser();
                            $password = $this->settings['password'];
                            if ($password == 'random') {
                                $password = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Crypto\Random::class)->generateRandomBytes(20);
                            }
                            $hashInstance = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory::class)->getDefaultHashInstance('FE');
                            $hashedPassword = $hashInstance->getHashedPassword($password);
                            $frontendUser->setUsername($dbemail);
                            $frontendUser->setPassword($hashedPassword);
                            $frontendUser->setPid(intval($log->getPid()));
                            $frontendUser->setEmail($dbemail);
                            if ($log->getFirstName() || $log->getLastName()) {
                                $frontendUser->setFirstName($log->getFirstName());
                                $frontendUser->setLastName($log->getLastName());
                                $frontendUser->setName(trim($log->getFirstName() . ' ' . $log->getLastName()));
                            }
                            $frontendUser->setAddress($log->getAddress());
                            $frontendUser->setZip($log->getZip());
                            $frontendUser->setCity($log->getCity());
                            $frontendUser->setCountry($log->getCountry());
                            $frontendUser->setTelephone($log->getPhone());
                            $frontendUser->setFax($log->getFax());
                            $frontendUser->setCompany($log->getCompany());
                            if ($this->settings['newsletterExtension'] == 'mail') {
                                $frontendUser->setMailActive(1);
                                $frontendUser->setMailHtml(1);
                                $frontendUser->setMailSalutation($salutation);
                                $frontendUser->setCategories(count($dmCatArr));
                            } else {
                                // default: Luxletter
                                if ($this->settings['newsletterExtension'] == 'luxletter') {
                                    $frontendUser->setLuxletterLanguage($sys_language_uid);
                                }
                                if ($dmCat) {
                                    $frontendUser->setUsergroup($dmCat);
                                    //$frontendUser->addUserGroup($this->frontendUserGroupRepository->findByUid($this->settings['frontendUserGroup']));
                                }
                            }
                            $this->frontendUserRepository->add($frontendUser);
                            $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
                            $persistenceManager->persistAll();
                            $success = 1;
                            $tableUid = $frontendUser->getUid();
                            if ($tableUid) {
                                $this->logRepository->insertIntoMm($tableUid, $dmCatArr, $this->settings['table']);
                            }
                        }
                        if ($this->settings['table'] && $success < 1) {
                            $error = 8;
                        } elseif (($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']) || $this->settings['email']['enableConfirmationMails']) {
                            $toAdmin = ($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']);
                            $this->helpersUtility->prepareEmail($log, $this->settings, $this->getViewArray(), true, true, false, filter_var($this->settings['email']['enableConfirmationMails'], FILTER_VALIDATE_BOOLEAN), $toAdmin, $hash, 0, '', $requestLanguageCode);
                        }
                    }
                }
            }
            if ($this->settings['disableErrorMsg'] && ($error == 5 || $error == 6)) {
                $error = 0;
            }

            if (($error == 0) && ($this->settings['subscribeVerifyMessageUid'])) {
                $uri = $this->uriBuilder->reset()
                    ->setTargetPageUid((int) $this->settings['subscribeVerifyMessageUid'])
                    ->build();
                return $this->responseFactory->createResponse(307)
                    ->withHeader('Location', $uri);
            } else {
                $this->view->assign('error', $error);
            }
        }
        return $this->htmlResponse();
    }

    /**
     * action verifyUnsubscribe Abmeldung
     *
     * @return ResponseInterface
     */
    public function verifyUnsubscribeAction(): ResponseInterface
    {
        $error = 0;
        $dbuid = 0;
        $uid = intval($this->request->hasArgument('uid')) ? $this->request->getArgument('uid') : 0;
        $hash = ($this->request->hasArgument('hash')) ? $this->request->getArgument('hash') : '';
        $languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
        $sys_language_uid = intval($languageAspect->getId());
        $requestLanguage = $this->request->getAttribute('language');
        $requestLocale = $requestLanguage->getLocale();
        $requestLanguageCode = $requestLocale->getLanguageCode();
        if (! $uid || ! $hash) {
            $this->view->assign('error', 1);
        } else {
            if ($sys_language_uid > 0 && $this->settings['languageMode']) {
                $log = $this->logRepository->findAnotherByUid($uid, $sys_language_uid);
            } else {
                $log = $this->logRepository->findOneByUid($uid);
            }
            if ($log) {
                $dbuid = $log->getUid();
                $this->view->assign('address', $log);
            }
            if (! $dbuid) {
                $error = 2;
            } elseif ($log->getStatus() == 4) {
                $error = 5;
            } else {
                $error = $this->helpersUtility->checkIfValid($log, $hash, $this->settings['daysExpire']);
                if (!$error) {
                    $dbemail = $log->getEmail();
                    $dbuidext = $this->logRepository->getExternalUid($dbemail, $log->getPid(), $this->settings['table'], $this->settings['searchPidMode']);
                    if ($this->settings['table'] && ! $dbuidext) {
                        $error = 6;
                    } else {
                        $log->setStatus(4);
                        $this->logRepository->update($log);
                        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
                        $persistenceManager->persistAll();

                        if ($this->settings['table'] == 'tt_address' || $this->settings['table'] == 'fe_users') {
                            $this->deleteThisUser($dbuidext);
                        }
                        if (($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']) || ($this->settings['email']['enableConfirmationMails'])) {
                            $toAdmin = ($this->settings['email']['adminMail'] && ! $this->settings['email']['adminMailBeforeVerification']);
                            $this->helpersUtility->prepareEmail($log, $this->settings, $this->getViewArray(), false, true, false, filter_var($this->settings['email']['enableConfirmationMails'], FILTER_VALIDATE_BOOLEAN), $toAdmin, $hash, 0, '', $requestLanguageCode);
                        }
                    }
                }
            }
            if ($this->settings['disableErrorMsg'] && ($error == 5 || $error == 6)) {
                $error = 0;
            }

            if (($error == 0) && ($this->settings['unsubscribeVerifyMessageUid'])) {
                $uri = $this->uriBuilder->reset()
                    ->setTargetPageUid((int) $this->settings['unsubscribeVerifyMessageUid'])
                    ->build();
                return $this->responseFactory->createResponse(307)
                    ->withHeader('Location', $uri);
            } else {
                $this->view->assign('error', $error);
            }
        }
        return $this->htmlResponse();
    }

    /**
     * Delete a user
     *
     * @param int $uid uid of the user
     */
    protected function deleteThisUser($uid)
    {
        if ($this->settings['table'] == 'tt_address' ||
            ($this->settings['table'] == 'fe_users' && $this->settings['newsletterExtension'] == 'mail')) {
            if ($this->settings['categoryOrGroup']) {
                $dmail_cats = str_replace(' ', '', (string) $this->settings['categoryOrGroup']);
                $dmCatArr = explode(',', $dmail_cats);
            } else {
                $dmCatArr = [];
            }
        } else {
            $dmCatArr = [];
        }
        $this->logRepository->deleteExternalUser(
            $uid,
            $this->settings['deleteMode'],
            $dmCatArr,
            $this->settings['table'],
            $this->settings['newsletterExtension']
        );
    }

    /**
     * Get the view array
     */
    protected function getViewArray(): array
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        return $extbaseFrameworkConfiguration['view'];
    }
}