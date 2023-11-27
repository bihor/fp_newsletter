<?php

declare(strict_types=1);
namespace Fixpunkt\FpNewsletter\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class FrontendUser extends AbstractEntity
{
    protected string $username = '';
    protected string $password = '';
    protected string $usergroup = '';

    protected string $name = '';
    protected string $firstName = '';
    protected string $middleName = '';
    protected string $lastName = '';
    protected string $address = '';
    protected string $telephone = '';
    protected string $fax = '';
    protected string $email = '';
    protected string $title = '';
    protected string $zip = '';
    protected string $city = '';
    protected string $country = '';
    protected string $www = '';
    protected string $company = '';

    protected int $luxletterLanguage = 0;
    protected int $mailActive = 0;
    protected int $mailHtml = 0;
    protected string $mailSalutation = '';
    protected int $categories = 0;

    public function __construct(string $username = '', string $password = '')
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function initializeObject()
    {

    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setMiddleName(string $middleName): self
    {
        $this->middleName = $middleName;
        return $this;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setFax(string $fax): self
    {
        $this->fax = $fax;
        return $this;
    }

    public function getFax(): string
    {
        return $this->fax;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;
        return $this;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setWww(string $www): self
    {
        $this->www = $www;
        return $this;
    }

    public function getWww(): string
    {
        return $this->www;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;
        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setUsergroup(string $usergroup): self
    {
        $this->usergroup = $usergroup;
        return $this;
    }

    public function getUsergroup(): string
    {
        return $this->usergroup;
    }

    public function getLuxletterLanguage(): int
    {
        return $this->luxletterLanguage;
    }

    public function setLuxletterLanguage(int $luxletterLanguage): self
    {
        $this->luxletterLanguage = $luxletterLanguage;
        return $this;
    }

    public function getMailActive(): int
    {
        return $this->mailActive;
    }

    public function setMailActive(int $active): self
    {
        $this->mailActive = $active;
        return $this;
    }

    public function getMailHtml(): int
    {
        return $this->mailHtml;
    }

    public function setMailHtml(int $html): self
    {
        $this->mailHtml = $html;
        return $this;
    }

    public function setMailSalutation(string $salutation): self
    {
        $this->mailSalutation = $salutation;
        return $this;
    }

    public function getMailSalutation(): string
    {
        return $this->mailSalutation;
    }

    public function getCategories(): int
    {
        return $this->categories;
    }

    public function setCategories(int $categories): self
    {
        $this->categories = $categories;
        return $this;
    }
}
