<?php
namespace Fixpunkt\FpNewsletter\Domain\Model;

/***
 *
 * This file is part of the "Newsletter managment" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Kurt Gusbeth <k.gusbeth@fixpunkt.com>, fixpunkt werbeagentur gmbh
 * Erst ab TYPO3 9:
 * use TYPO3\CMS\Extbase\Annotation as Extbase;
 * 
 ***/

/**
 * Log
 */
class Log extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Time stamp date
     *
     * @var \DateTime
     */
    protected $tstamp = NULL;
    
    /**
     * Language
     *
     * @var int
     */
    protected $_languageUid = 0;
    
    /**
     * Anrede
     *
     * @var int
     */
    protected $gender = 0;
    
    /**
     * Title
     *
     * @var string
     */
    protected $title = '';
    
    /**
     * Vorname
     *
     * @var string
     */
    protected $firstname = '';

    /**
     * Nachname
     *
     * @var string
     */
    protected $lastname = '';

    /**
     * E-Mail
     * erst ab TYPO3 9: atExtbase\Validate("NotEmpty")
     * 
     * @validate NotEmpty
     * @var string
     */
    protected $email = '';
    
    /**
     * Adresse
     *
     * @var string
     */
    protected $address = '';
    
    /**
     * PLZ
     *
     * @var string
     */
    protected $zip = '';
    
    /**
     * Ort
     *
     * @var string
     */
    protected $city = '';
    
    /**
     * Region
     *
     * @var string
     */
    protected $region = '';
    
    /**
     * Land
     *
     * @var string
     */
    protected $country = '';
    
    /**
     * Telefon
     *
     * @var string
     */
    protected $phone = '';
    
    /**
     * Mobil-Telefon
     *
     * @var string
     */
    protected $mobile = '';
    
    /**
     * Telefax
     *
     * @var string
     */
    protected $fax = '';
    
    /**
     * WWW
     *
     * @var string
     */
    protected $www = '';
    
    /**
     * Position
     *
     * @var string
     */
    protected $position = '';
    
    /**
     * Firma
     *
     * @var string
     */
    protected $company = '';
    
    /**
     * Kategorien
     *
     * @var string
     */
    protected $categories = '';
    
    /**
     * Status
     *
     * @var int
     */
    protected $status = 0;

    /**
     * Hash
     *
     * @var string
     */
    protected $securityhash = '';
    
    /**
     * reCaptcha token
     *
     * @var string
     */
    protected $retoken = '';
    
    /**
     * Math. captcha
     *
     * @var string
     */
    protected $mathcaptcha = '';
    
    /**
     * Math. captcha no. 1
     *
     * @var int
     */
    protected $mathcaptcha1 = 0;
    
    /**
     * Math. captcha no. 2
     *
     * @var int
     */
    protected $mathcaptcha2 = 0;
    
    /**
     * Math. captcha operator
     *
     * @var bool
     */
    protected $mathcaptchaop = false;
    
    /**
     * GDPR checkbox
     * erst ab TYPO3 9: atExtbase\Validate("Boolean", options={"is": true})
     * 
     * @validate Boolean(is=true)
     * @var bool
     */
    protected $gdpr = false;

    
    /**
     * Returns the tstamp
     *
     * @return \DateTime $tstamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Sets the tstamp
     *
     * @param string $tstamp
     * @return void
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
    }
    
    /**
     * Returns the sys_language_uid
     *
     * @return int $sys_language_uid
     */
    public function get_languageUid()
    {
        return $this->_languageUid;
    }
    
    /**
     * Sets the sys_language_uid
     *
     * @param int $sys_language_uid
     * @return void
     */
    public function set_languageUid($sys_language_uid)
    {
        $this->_languageUid = $sys_language_uid;
    }
    
    /**
     * Returns the gender
     *
     * @return int $gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets the gender
     *
     * @param int $gender
     * @return void
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }
    
    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
    	return $this->title;
    }
    
    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
    	$this->title = $title;
    }
    
    /**
     * Returns the firstname
     *
     * @return string $firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Sets the firstname
     *
     * @param string $firstname
     * @return void
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Returns the lastname
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Sets the lastname
     *
     * @param string $lastname
     * @return void
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
    	return $this->address;
    }
    
    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
    	$this->address = $address;
    }
    
    /**
     * Returns the zip
     *
     * @return string $zip
     */
    public function getZip()
    {
    	return $this->zip;
    }
    
    /**
     * Sets the zip
     *
     * @param string $zip
     * @return void
     */
    public function setZip($zip)
    {
    	$this->zip = $zip;
    }
    
    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
    	return $this->city;
    }
    
    /**
     * Sets the city
     *
     * @param string $city
     * @return void
     */
    public function setCity($city)
    {
    	$this->city = $city;
    }
    
    /**
     * Returns the region
     *
     * @return string $region
     */
    public function getRegion()
    {
    	return $this->region;
    }
    
    /**
     * Sets the region
     *
     * @param string $region
     * @return void
     */
    public function setRegion($region)
    {
    	$this->region = $region;
    }
    
    /**
     * Returns the country
     *
     * @return string $country
     */
    public function getCountry()
    {
    	return $this->country;
    }
    
    /**
     * Sets the country
     *
     * @param string $country
     * @return void
     */
    public function setCountry($country)
    {
    	$this->country = $country;
    }
    
    /**
     * Returns the phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
    	return $this->phone;
    }
    
    /**
     * Sets the phone
     *
     * @param string $phone
     * @return void
     */
    public function setPhone($phone)
    {
    	$this->phone = $phone;
    }
    
    /**
     * Returns the mobile
     *
     * @return string $mobile
     */
    public function getMobile()
    {
    	return $this->mobile;
    }
    
    /**
     * Sets the mobile
     *
     * @param string $mobile
     * @return void
     */
    public function setMobile($mobile)
    {
    	$this->mobile = $mobile;
    }
    
    /**
     * Returns the fax
     *
     * @return string $fax
     */
    public function getFax()
    {
    	return $this->fax;
    }
    
    /**
     * Sets the fax
     *
     * @param string $fax
     * @return void
     */
    public function setFax($fax)
    {
    	$this->fax = $fax;
    }
    
    /**
     * Returns the www
     *
     * @return string $www
     */
    public function getWww()
    {
    	return $this->www;
    }
    
    /**
     * Sets the www
     *
     * @param string $www
     * @return void
     */
    public function setWww($www)
    {
    	$this->www = $www;
    }
    
    /**
     * Returns the position
     *
     * @return string $position
     */
    public function getPosition()
    {
    	return $this->position;
    }
    
    /**
     * Sets the position
     *
     * @param string $position
     * @return void
     */
    public function setPosition($position)
    {
    	$this->position = $position;
    }
    
    /**
     * Returns the company
     *
     * @return string $company
     */
    public function getCompany()
    {
    	return $this->company;
    }
    
    /**
     * Sets the company
     *
     * @param string $company
     * @return void
     */
    public function setCompany($company)
    {
    	$this->company = $company;
    }
    
    /**
     * Returns the categories
     *
     * @return string $categories
     */
    public function getCategories()
    {
    	return $this->categories;
    }
    
    /**
     * Sets the categories
     *
     * @param string $categories
     * @return void
     */
    public function setCategories($categories)
    {
    	$this->categories = $categories;
    }
    
    /**
     * Returns the status
     *
     * @return int $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the status
     *
     * @param int $status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns the securityhash
     *
     * @return string $securityhash
     */
    public function getSecurityhash()
    {
        return $this->securityhash;
    }

    /**
     * Sets the securityhash
     *
     * @param string $securityhash
     * @return void
     */
    public function setSecurityhash($securityhash)
    {
        $this->securityhash = $securityhash;
    }
    
    /**
     * Returns the retoken
     *
     * @return string $retoken
     */
    public function getRetoken()
    {
    	return $this->retoken;
    }
    
    /**
     * Sets the retoken
     *
     * @param string $retoken
     * @return void
     */
    public function setRetoken($retoken)
    {
    	$this->retoken = $retoken;
    }
    
    /**
     * Returns the mathcaptcha
     *
     * @return string $mathcaptcha
     */
    public function getMathcaptcha()
    {
    	return $this->mathcaptcha;
    }
    
    /**
     * Sets the mathcaptcha
     *
     * @param string $mathcaptcha
     * @return void
     */
    public function setMathcaptcha($mathcaptcha)
    {
    	$this->mathcaptcha = $mathcaptcha;
    }
    
    /**
     * Returns the mathcaptcha1
     *
     * @return int $mathcaptcha1
     */
    public function getMathcaptcha1()
    {
    	return $this->mathcaptcha1;
    }
    
    /**
     * Sets the mathcaptcha1
     *
     * @param int $mathcaptcha1
     * @return void
     */
    public function setMathcaptcha1($mathcaptcha1)
    {
    	$this->mathcaptcha1 = $mathcaptcha1;
    }
    
    /**
     * Returns the mathcaptcha2
     *
     * @return int $mathcaptcha2
     */
    public function getMathcaptcha2()
    {
    	return $this->mathcaptcha2;
    }
    
    /**
     * Sets the mathcaptcha2
     *
     * @param int $mathcaptcha2
     * @return void
     */
    public function setMathcaptcha2($mathcaptcha2)
    {
    	$this->mathcaptcha2 = $mathcaptcha2;
    }
    
    /**
     * Returns the math. captcha operator
     *
     * @return bool $mathcaptchaop
     */
    public function getMathcaptchaop()
    {
    	return $this->mathcaptchaop;
    }
    
    /**
     * Sets the math. captcha operator
     *
     * @param bool $mathcaptchaop
     * @return void
     */
    public function setMathcaptchaop($mathcaptchaop)
    {
    	$this->mathcaptchaop = $mathcaptchaop;
    }
    
    /**
     * Returns the boolean state of math. captcha operator
     *
     * @return bool
     */
    public function isMathcaptchaop()
    {
    	return $this->mathcaptchaop;
    }
    
    /**
     * Returns the gdpr
     *
     * @return bool $gdpr
     */
    public function getGdpr()
    {
        return $this->gdpr;
    }

    /**
     * Sets the gdpr
     *
     * @param bool $gdpr
     * @return void
     */
    public function setGdpr($gdpr)
    {
        $this->gdpr = $gdpr;
    }

    /**
     * Returns the boolean state of gdpr
     *
     * @return bool
     */
    public function isGdpr()
    {
        return $this->gdpr;
    }
}
