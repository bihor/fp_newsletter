<?php
namespace Fixpunkt\FpNewsletter\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Kurt Gusbeth <k.gusbeth@fixpunkt.com>
 */
class LogTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Fixpunkt\FpNewsletter\Domain\Model\Log
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Fixpunkt\FpNewsletter\Domain\Model\Log();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getGenderReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setGenderForIntSetsGender()
    {
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );

    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle()
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'title',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getFirstnameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFirstname()
        );

    }

    /**
     * @test
     */
    public function setFirstnameForStringSetsFirstname()
    {
        $this->subject->setFirstname('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'firstname',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getLastnameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getLastname()
        );

    }

    /**
     * @test
     */
    public function setLastnameForStringSetsLastname()
    {
        $this->subject->setLastname('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'lastname',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );

    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'email',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getStatusReturnsInitialValueForInt()
    {
    }

    /**
     * @test
     */
    public function setStatusForIntSetsStatus()
    {
    }

    /**
     * @test
     */
    public function getSecurityhashReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSecurityhash()
        );

    }

    /**
     * @test
     */
    public function setSecurityhashForStringSetsSecurityhash()
    {
        $this->subject->setSecurityhash('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'securityhash',
            $this->subject
        );

    }

    /**
     * @test
     */
    public function getGdprReturnsInitialValueForBool()
    {
        self::assertSame(
            false,
            $this->subject->getGdpr()
        );

    }

    /**
     * @test
     */
    public function setGdprForBoolSetsGdpr()
    {
        $this->subject->setGdpr(true);

        self::assertAttributeEquals(
            true,
            'gdpr',
            $this->subject
        );

    }
}
