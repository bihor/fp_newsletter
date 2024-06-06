<?php
namespace Fixpunkt\FpNewsletter\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;
use Fixpunkt\FpNewsletter\Controller\LogController;
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
        parent::tearDown();
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
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $logRepository->expects(self::once())->method('findAll')->will(self::returnValue($allLogs));
        $this->subject->_set('logRepository', $logRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('logs', $allLogs);
        $this->subject->_set('view', $view);

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
