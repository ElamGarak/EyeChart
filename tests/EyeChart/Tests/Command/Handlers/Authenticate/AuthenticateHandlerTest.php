<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/7/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Command\Handlers;

use EyeChart\Command\Commands\AuthenticateCommand;
use EyeChart\Command\Handlers\Authenticate\AuthenticateHandler;
use EyeChart\Service\Authenticate\AuthenticateListener;
use EyeChart\Tests\Fixtures\CommandBusAuthenticationFixture;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class AuthenticateHandlerTest
 * @package EyeChart\Tests\Command\Handlers
 */
class AuthenticateHandlerTest extends TestCase
{
    /** @var AuthenticateHandler */
    private $handler;

    /** @var CommandBusAuthenticationFixture */
    private static $commandBusFixture;

    public static function setUpBeforeClass(): void
    {
        self::$commandBusFixture = new CommandBusAuthenticationFixture();
    }

    public function setUp(): void
    {
        parent::setUp();

        /** @var AuthenticateListener|PHPUnit_Framework_MockObject_MockObject $mockedListener */
        $mockedListener = $this->getMockBuilder(AuthenticateListener::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $mockedListener->expects($this->once())
                       ->method('checkAuthentication');

        $this->handler = new AuthenticateHandler($mockedListener);
    }

    public function testHandle(): void
    {
        /** @var AuthenticateCommand|PHPUnit_Framework_MockObject_MockObject $mockedCommand */
        $mockedCommand = $this->getMockBuilder(AuthenticateCommand::class)
                              ->disableOriginalConstructor()
                              ->getMock();

        $mockedCommand->expects($this->once())
                      ->method('getEvent')
                      ->willReturn(self::$commandBusFixture->getEvent());

        $this->handler->handle($mockedCommand);
    }
}
