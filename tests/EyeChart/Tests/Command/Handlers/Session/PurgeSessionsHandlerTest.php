<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/10/2017
 * (c) 2017
 */

namespace EyeChart\Tests\Command\Handlers\Session;
use EyeChart\Command\Commands\PurgeSessionCommand;
use EyeChart\Command\Handlers\Session\PurgeSessionHandler;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class PurgeSessionsHandlerTest
 * @package EyeChart\Tests\Command\Handlers\Session
 */
final class PurgeSessionsHandlerTest extends TestCase
{
    /** @var PurgeSessionHandler */
    private $handler;

    /** @var AuthenticateStorageModel|PHPUnit_Framework_MockObject_MockObject */
    private $mockedModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockedModel = $this->getMockBuilder(AuthenticateStorageModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->handler = new PurgeSessionHandler($this->mockedModel);
    }

    public function testHandle(): void
    {
        $command = new PurgeSessionCommand([]);

        $this->mockedModel->expects($this->once())
            ->method('purge')
            ->with($command);

        $this->handler->handle($command);
    }
}
