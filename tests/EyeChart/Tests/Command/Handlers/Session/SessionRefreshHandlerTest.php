<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/7/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Command\Handlers\Session;

use EyeChart\Command\Commands\SessionRefreshCommand;
use EyeChart\Command\Handlers\Session\SessionRefreshHandler;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;
use EyeChart\VO\Authentication\AuthenticationVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class SessionRefreshHandlerTest
 * @package EyeChart\Tests\Command\Handlers\Session
 */
class SessionRefreshHandlerTest extends TestCase
{
    /** @var SessionRefreshHandler */
    private $handler;

    /** @var AuthenticateStorageModel|PHPUnit_Framework_MockObject_MockObject */
    private $mockedModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockedModel = $this->getMockBuilder(AuthenticateStorageModel::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->handler = new SessionRefreshHandler($this->mockedModel);
    }

    public function testHandle(): void
    {
        $vo = AuthenticationVO::build();

        $command = new SessionRefreshCommand($vo);

        $this->mockedModel->expects($this->once())
                      ->method('refresh')
                      ->with($vo->getToken());

        $this->handler->handle($command);
    }
}
