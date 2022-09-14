<?php

namespace Fixpunkt\FpNewsletter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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
    public function checkMathCaptcha(int $result)
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
}