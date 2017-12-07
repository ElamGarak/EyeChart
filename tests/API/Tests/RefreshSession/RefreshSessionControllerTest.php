<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/7/2017
 * (c) Eye Chart
 */

namespace API\Tests\RefreshSession;

use API\V1\Rpc\RefreshSession\RefreshSessionController;
use EyeChart\Command\Commands\AuthenticateCommand;
use EyeChart\Command\Commands\SessionRefreshCommand;
use EyeChart\Service\Authenticate\AuthenticateStorageService;
use EyeChart\Tests\Fixtures\CommandBusAuthenticationFixture;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class RefreshSessionControllerTest
 * @package API\Tests\RefreshSession
 */
class RefreshSessionControllerTest extends TestCase
{
    /** @var AuthenticateStorageService|PHPUnit_Framework_MockObject_MockObject */
    private $mockedService;

    /** @var CommandBusAuthenticationFixture */
    private static $commandBusFixture;

    /** @var mixed */
    private static $expectedPayload;

    public static function setUpBeforeClass(): void
    {
        self::$commandBusFixture = new CommandBusAuthenticationFixture();
        self::$expectedPayload   = [];
    }

    public function setUp(): void
    {
        $this->mockedService = $this->getMockBuilder(AuthenticateStorageService::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();
    }

    public function testRelationshipAutocompleteAction(): void
    {
        /** @var CommandBus|PHPUnit_Framework_MockObject_MockObject $mockedCommandBus */
        $mockedCommandBus = $this->getMockBuilder(CommandBus::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $mockedCommandBus->expects($this->at(0))
                         ->method('handle')
                         ->with(new AuthenticateCommand(self::$commandBusFixture->getEvent()));

        $mockedCommandBus->expects($this->at(1))
                         ->method('handle')
                         ->with(new SessionRefreshCommand());

        $controller = new RefreshSessionController(
            $this->mockedService,
            $mockedCommandBus
        );

        $controller->getRequest()->setContent(json_encode(self::$expectedPayload));
        $controller->setEvent(self::$commandBusFixture->getEvent());


        $result = $controller->refreshSessionAction();

        $this->assertEquals(['success' => true], $result->getVariables());
    }

    public function testRefreshSessionActonWillReturnProblemResponse(): void
    {
        /** @var CommandBus|PHPUnit_Framework_MockObject_MockObject $mockedCommandBus */
        $mockedCommandBus = $this->getMockBuilder(CommandBus::class)
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $mockedCommandBus->expects($this->at(0))
                         ->method('handle')
                         ->willThrowException(new \Exception('I before e except after g'));

        $controller = new RefreshSessionController(
            $this->mockedService,
            $mockedCommandBus
        );

        $controller->getRequest()->setContent(json_encode(self::$expectedPayload));
        $controller->setEvent(self::$commandBusFixture->getEvent());

        $result = $controller->refreshSessionAction();

        $this->assertInstanceOf(ApiProblemResponse::class, $result);
    }
}
