<?php
namespace Fixpunkt\FpNewsletter\Tests\Unit\Controller;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use Fixpunkt\FpNewsletter\Controller\LogController;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use Fixpunkt\FpNewsletter\Domain\Repository\LogRepository;
use Fixpunkt\FpNewsletter\Domain\Model\Log;
/**
 * Test case.
 *
 * @author Kurt Gusbeth <k.gusbeth@fixpunkt.com>
 */
class LogControllerTest extends UnitTestCase
{
    /**
     * @var LogController
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = $this->getMockBuilder(LogController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
    }

    /**
     * @test
     */
    public function listActionFetchesAllLogsFromRepositoryAndAssignsThemToView()
    {

        $allLogs = $this->getMockBuilder(ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $logRepository = $this->getMockBuilder(LogRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $logRepository->expects(self::once())->method('findAll')->will(self::returnValue($allLogs));
        $this->inject($this->subject, 'logRepository', $logRepository);

        // TODO
        /*
        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('logs', $allLogs);
        $this->inject($this->subject, 'view', $view);
        */
        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenLogToLogRepository()
    {
        $log = new Log();

        $logRepository = $this->getMockBuilder(LogRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $logRepository->expects(self::once())->method('add')->with($log);
        $this->inject($this->subject, 'logRepository', $logRepository);

        $this->subject->createAction($log);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenLogFromLogRepository()
    {
        $log = new Log();

        $logRepository = $this->getMockBuilder(LogRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $logRepository->expects(self::once())->method('remove')->with($log);
        $this->inject($this->subject, 'logRepository', $logRepository);

        $this->subject->deleteAction($log);
    }
}
