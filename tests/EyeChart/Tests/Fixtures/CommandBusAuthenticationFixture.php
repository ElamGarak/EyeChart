<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace EyeChart\Tests\Fixtures;

use EyeChart\Command\Commands\AuthenticateCommand;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Http\Request;
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use Zend\View\Model\ViewModel;

/**
 * Class CommandBusAuthenticationFixture
 * @package Fixtures
 *
 * This fixture 'mocks' the process of authentication via command bus.
 */
final class CommandBusAuthenticationFixture extends TestCase
{
    /** @var  CommandBus|PHPUnit_Framework_MockObject_MockObject */
    private $mockedCommandBus;

    /** @var MvcEvent|PHPUnit_Framework_MockObject_MockObject */
    private $mockedMvcEvent;

    /** @var Request|PHPUnit_Framework_MockObject_MockObject */
    private $request;

    /**
     * CommandBusAuthenticationFixture constructor.
     * When instantiated, the command bus and mvc event are both mocked with a simulated authentication command.
     */
    public function __construct()
    {
        parent::__construct();

        $this->mockedCommandBus = $this->getMockBuilder(CommandBus::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedMvcEvent = $this->getMockBuilder(MvcEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedMvcEvent->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->getRequest());

        $this->mockedCommandBus->expects($this->any())
            ->method('handle')
            ->with(new AuthenticateCommand($this->mockedMvcEvent));
    }

    /**
     * Returns mocked command bus with simulated authentication event
     * @return CommandBus|PHPUnit_Framework_MockObject_MockObject
     */
    public function getCommandBus(): CommandBus
    {
        return $this->mockedCommandBus;
    }

    /**
     * Returns mocked event which can then be injected into the subject under test
     * @return MvcEvent|PHPUnit_Framework_MockObject_MockObject
     */
    public function getEvent(): MvcEvent
    {
        return $this->mockedMvcEvent;
    }

    /**
     * Add route(s) to the mocked mvc along with required name
     *
     * @param string[] $routes
     * @param string $matchedRouteName
     */
    public function addRouteToEvent(array $routes, string $matchedRouteName): void
    {
        $routeMatch = new RouteMatch($routes);
        $routeMatch->setMatchedRouteName($matchedRouteName);

        $this->mockedMvcEvent->expects($this->any())
            ->method('getRouteMatch')
            ->willReturn($routeMatch);
    }

    /**
     * Add a view model to the mocked mvc event along with required parameters
     */
    public function addHttpViewModel(): void
    {
        $this->mockedMvcEvent->expects($this->any())
            ->method('getViewModel')
            ->willReturn(new ViewModel());
    }

    /**
     * @param mixed $plugin
     * @return PluginManager|PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockedPluginManager($plugin): PHPUnit_Framework_MockObject_MockObject
    {
        $pluginManager = $this->getMockBuilder(PluginManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pluginManager->expects($this->any())
            ->method('get')
            ->willReturn($plugin);

        return $pluginManager;
    }

    /**
     * @param Request|PHPUnit_Framework_MockObject_MockObject $request
     */
    public function resetRequest(Request $request): void
    {
        $this->request = $request;

        self::__construct();
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|Request
     */
    public function getRequest()
    {
        // Lazy getter allows for tester to optionally pass in their own request object
        if (is_null($this->request)) {
            $this->request = new Request();
        }

        return $this->request;
    }
}
