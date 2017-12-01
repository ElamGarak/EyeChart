<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/1/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Service\Authenticate;

use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Service\Authenticate\AuthenticateListener;
use EyeChart\Service\Authenticate\AuthenticateService;
use EyeChart\Tests\Fixtures\CommandBusAuthenticationFixture;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Config\Config;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use Zend\Stdlib\Parameters;

/**
 * Class AuthenticateListenerTest
 * @package EyeChart\Tests\Service\Authenticate
 */
class AuthenticateListenerTest extends TestCase
{
    /** @var AuthenticateService|PHPUnit_Framework_MockObject_MockObject */
    private $mockedAuthenticateService;

    /** @var Config|PHPUnit_Framework_MockObject_MockObject */
    private $config;

    /** @var EventManagerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $mockedEventManager;

    /** @var RouteMatch|PHPUnit_Framework_MockObject_MockObject */
    private $mockedMockedRouteMatch;

    /** @var AuthenticateListener */
    private $listener;

    /** @var AuthenticateEntity */
    private static $authenticateEntity;

    /** @var CommandBusAuthenticationFixture */
    private static $commandBusFixture;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$authenticateEntity = new AuthenticateEntity();
        self::$commandBusFixture  = new CommandBusAuthenticationFixture();
    }

    public function setUp(): void
    {
        $this->mockedAuthenticateService = $this->getMockBuilder(AuthenticateService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = new Config([]);

        $this->mockedEventManager = $this->getMockBuilder(EventManagerInterface::class)
            ->getMock();

        $this->mockedMockedRouteMatch = $this->getMockBuilder(RouteMatch::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->listener = new AuthenticateListener(
            $this->mockedAuthenticateService,
            self::$authenticateEntity,
            $this->config
        );
    }

    public function testAttach(): void
    {
        $this->mockedEventManager->expects($this->once())
            ->method('attach')
            ->with(MvcEvent::EVENT_ROUTE, [$this->listener, 'checkAuthentication']);

        $this->listener->attach($this->mockedEventManager);

        $results = $this->listener->getListeners();
        $this->assertEquals(1, count($results));
    }

    public function testDetach(): void
    {
        $this->mockedEventManager->expects($this->once())
            ->method('attach')
            ->with(MvcEvent::EVENT_ROUTE, [$this->listener, 'checkAuthentication'])
            ->willReturn(function () {});

        $this->mockedEventManager->expects($this->once())
            ->method('detach');

        $this->listener->attach($this->mockedEventManager);

        $results = $this->listener->getListeners();
        $this->assertEquals(1, count($results));

        $this->listener->detach($this->mockedEventManager);

        $results = $this->listener->getListeners();
        $this->assertEquals(0, count($results));
    }

    /**
     * This test has been purposely made brittle to test assertions carefully as the subject under test is crucial to
     * functionality of the entire application
     */
    public function testCheckAuthenticationForTokenWithinPostReturnsForApiglityRoutes(): void
    {
        $params = new Parameters();
        $params->set(AuthenticateMapper::TOKEN, str_pad('a', AuthenticateMapper::TOKEN_LENGTH));

        $request = new Request();
        $request->setPost($params);

        self::$commandBusFixture->resetRequest($request);

        $mockedMvcEvent = self::$commandBusFixture->getEvent();

        $this->mockedMockedRouteMatch->expects($this->once())
            ->method('getParams');

        $this->mockedMockedRouteMatch->expects($this->once())
            ->method('getMatchedRouteName')
            ->willReturn('zf-apigility/');

        $mockedMvcEvent->expects($this->exactly(2))
            ->method('getRouteMatch')
            ->willReturn($this->mockedMockedRouteMatch);

        $this->listener->checkAuthentication($mockedMvcEvent);
    }

    /**
     * This test has been purposely made brittle to test assertions carefully as the subject under test is crucial to
     * functionality of the entire application
     */
    public function testCheckAuthenticationForTokenWithinHeaderReturnsForApiglityRoutes(): void
    {
        $headers = new Headers();
        $headers->addHeaders([
            AuthenticateMapper::TOKEN => str_pad('a', AuthenticateMapper::TOKEN_LENGTH)
        ]);

        $request = new Request();
        $request->setHeaders($headers);

        self::$commandBusFixture->resetRequest($request);

        $mockedMvcEvent = self::$commandBusFixture->getEvent();

        $this->mockedMockedRouteMatch->expects($this->once())
            ->method('getParams');

        $this->mockedMockedRouteMatch->expects($this->once())
            ->method('getMatchedRouteName')
            ->willReturn('zf-apigility/');

        $mockedMvcEvent->expects($this->exactly(2))
            ->method('getRouteMatch')
            ->willReturn($this->mockedMockedRouteMatch);

        $this->listener->checkAuthentication($mockedMvcEvent);
    }

    public function testCheckAuthenticationForNoTokenRequiredWithJSONPayloads(): void
    {
        $headers = new Headers();
        $headers->addHeaders([
            AuthenticateMapper::TOKEN => str_pad('a', AuthenticateMapper::TOKEN_LENGTH),
            'Content-Type'            => 'application/json'
        ]);

        $request = new Request();
        $request->setHeaders($headers);

        self::$commandBusFixture->resetRequest($request);

        $mockedMvcEvent = self::$commandBusFixture->getEvent();

        $this->mockedMockedRouteMatch->expects($this->once())
            ->method('getParams');

        $this->mockedMockedRouteMatch->expects($this->once())
            ->method('getMatchedRouteName')
            ->willReturn('api.rpc.login');

        $mockedMvcEvent->expects($this->exactly(2))
            ->method('getRouteMatch')
            ->willReturn($this->mockedMockedRouteMatch);

        $config = new Config([
                                 'noTokenRequired' => [
                                     'api.rpc.login' => true
                                 ]
                             ]);

        $listener = new AuthenticateListener(
            $this->mockedAuthenticateService,
            self::$authenticateEntity,
            $config
        );

        $listener->checkAuthentication($mockedMvcEvent);
    }

    public function testCheckAuthenticationForTokenRequiredWithJSONPayloads(): void
    {
        $this->markTestIncomplete();
    }

    public function testCheckAuthenticationForTokenRequiredWithTestHtmlHeaderDropsThoughSwitch(): void
    {
        $this->markTestIncomplete();
    }

    public function testCheckAuthenticationForTokenRequiredWithXWWWFormUrlEncoded(): void
    {
        $this->markTestIncomplete();
    }

    public function testCheckAuthenticationForNoTokenRequiredWithFormData(): void
    {
        $this->markTestIncomplete();
    }

    public function testCheckAuthenticationForTokenRequiredWithFormData(): void
    {
        $this->markTestIncomplete();
    }

    /**
     * This results in an error log call, a better way should be found to test this
     */
    public function testCheckAuthenticationForTokenRequiredWithUnknownPayloads(): void
    {
        $headers = new Headers();
        $headers->addHeaders([
            AuthenticateMapper::TOKEN => str_pad('a', AuthenticateMapper::TOKEN_LENGTH),
            'Content-Type'            => 'application/php-unit'
        ]);

        $request = new Request();
        $request->setHeaders($headers);

        self::$commandBusFixture->resetRequest($request);

        $mockedMvcEvent = self::$commandBusFixture->getEvent();

        $this->mockedMockedRouteMatch->expects($this->once())
            ->method('getParams');

        $this->mockedMockedRouteMatch->expects($this->once())
            ->method('getMatchedRouteName')
            ->willReturn('foo');

        $mockedMvcEvent->expects($this->exactly(2))
            ->method('getRouteMatch')
            ->willReturn($this->mockedMockedRouteMatch);

        $this->listener->checkAuthentication($mockedMvcEvent);
    }

    public function testForTokenExistingAsAUrlParam(): void
    {
        $this->markTestIncomplete();
    }

    public function testForTokenNotExistingAsAUrlParam(): void
    {
        $this->markTestIncomplete();
    }

    public function testForTokenValidity(): void
    {
        $this->markTestIncomplete();
    }

    public function testCheckAuthenticationThrowsExceptions(): void
    {
        $this->markTestIncomplete();
    }
}
