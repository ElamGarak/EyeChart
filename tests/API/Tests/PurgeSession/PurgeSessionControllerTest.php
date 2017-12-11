<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/10/2017
 * (c) 2017
 */

namespace API\Tests\PurgeSession;

use API\V1\Rpc\PurgeSessions\PurgeSessionsController;
use EyeChart\Command\Commands\PurgeSessionCommand;
use EyeChart\Tests\Fixtures\CommandBusAuthenticationFixture;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class PurgeSessionControllerTest
 * @package API\Tests\PurgeSession
 */
final class PurgeSessionControllerTest extends TestCase
{
    /** @var CommandBusAuthenticationFixture */
    private static $commandBusFixture;

    /** @var mixed */
    private static $expectedPayload;

    public static function setUpBeforeClass(): void
    {
        self::$commandBusFixture = new CommandBusAuthenticationFixture();
    }

    public function testRefreshSessionAction(): void
    {
        /** @var CommandBus|PHPUnit_Framework_MockObject_MockObject $mockedCommandBus */
        $mockedCommandBus = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockedCommandBus->expects($this->once())
            ->method('handle')
            ->with(new PurgeSessionCommand());

        $controller = new PurgeSessionsController($mockedCommandBus);

        $controller->getRequest()->setContent(json_encode(self::$expectedPayload));
        $controller->setEvent(self::$commandBusFixture->getEvent());

        $result = $controller->purgeSessionsAction();

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

        $controller = new PurgeSessionsController($mockedCommandBus);

        $controller->getRequest()->setContent(json_encode(self::$expectedPayload));
        $controller->setEvent(self::$commandBusFixture->getEvent());

        $result = $controller->purgeSessionsAction();

        $this->assertInstanceOf(ApiProblemResponse::class, $result);
    }
}
