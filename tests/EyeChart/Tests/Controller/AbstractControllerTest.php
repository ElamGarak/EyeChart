<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 11/28/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Controller;

use Exception;
use EyeChart\Controller\AbstractController;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\MenuMapper;
use EyeChart\Tests\Fixtures\CommandBusAuthenticationFixture;
use PHPUnit\Framework\TestCase;
use Zend\Http\Headers;
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Stdlib\Parameters;

/**
 * Class AbstractControllerTest
 * @package EyeChart\Tests\Controller
 */
class AbstractControllerTest extends TestCase
{
    /** @var string */
    protected static $controllerNameSpace = AbstractController::class;

    /** @var  string[] */
    protected static $expectations = [
        MenuMapper::MENU_ROUTE => 'foobar'
    ];

    /** @var Parameters */
    private static $postParams;

    /** @var string[]  */
    private static $expectedPostParams = ['key' => 'foo', 'value' => 'bar'];

    /** @var Headers */
    private static $headers;

    /** @var string[]  */
    private static $expectedHeaders = ['key' => 'foo', 'value' => 'bar'];

    /** @var AuthenticateEntity */
    private static $authenticateEntity;

    /** @var CommandBusAuthenticationFixture */
    private static $commandBusFixture;

    /** @var AbstractController */
    private $controller;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$authenticateEntity = new AuthenticateEntity();
        self::$authenticateEntity->setToken(str_repeat('a', AuthenticateMapper::TOKEN_LENGTH));

        self::$expectations[AuthenticateMapper::TOKEN] = self::$authenticateEntity->getToken();

        self::$commandBusFixture = new CommandBusAuthenticationFixture();
        self::$commandBusFixture->addRouteToEvent(['foo'], self::$expectations[MenuMapper::MENU_ROUTE]);
        self::$commandBusFixture->addHttpViewModel();

        self::$headers = new Headers();
        self::$headers->addHeaders([self::$expectedHeaders['key'] => self::$expectedHeaders['value']]);

        self::$postParams = new Parameters();
        self::$postParams->set(self::$expectedPostParams['key'], self::$expectedPostParams['value']);
    }

    public function setUp(): void
    {
        parent::setUp();

        $commandBus       = self::$commandBusFixture->getCommandBus();
        $this->controller = new self::$controllerNameSpace(self::$authenticateEntity, $commandBus);

        $this->controller->setEvent(self::$commandBusFixture->getEvent());
        $this->controller->getRequest()->setHeaders(self::$headers);
        $this->controller->getRequest()->setContent(json_encode(self::$expectations));
        $this->controller->getRequest()->setPost(self::$postParams);

        foreach (self::$expectations as $key => $expectation) {
            $this->controller->layout()->setVariable($key, $expectation);
        }
    }

    public function testGetPostValue(): void
    {
        $actual = $this->controller->getPostValue(self::$expectedPostParams['key']);

        $this->assertEquals(self::$expectedPostParams['value'], $actual);
    }


    public function testAuthenticateAssertsRedirect(): void
    {
        $mockedRedirect = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockedRedirect->expects($this->once())
            ->method('toRoute')
            ->with('login');

        $pluginManager = self::$commandBusFixture->getMockedPluginManager($mockedRedirect);

        self::$commandBusFixture->getCommandBus()->expects($this->once())
            ->method('handle')
            ->willThrowException(new Exception());

        $this->controller->setPluginManager($pluginManager);



        $this->controller->authenticate();
    }

    public function testGetAuthenticateEntity(): void
    {
        $this->assertEquals(self::$authenticateEntity, $this->controller->getAuthenticateEntity());
    }

    public function testGetMatchedRouteName(): void
    {
        $name = $this->controller->getMatchedRouteName();

        $this->assertEquals(self::$expectations[MenuMapper::MENU_ROUTE], $name);
    }

    /**
     * @expectedException \EyeChart\Exception\InvalidPostRequestException
     */
    public function testGetPostValueThrowsException(): void
    {
        $this->controller->getPostValue('bim');
    }

    public function testGetHeaderValue(): void
    {
        $expected = 'bar';

        $headers = new Headers();
        $headers->addHeaders(['foo' => $expected]);

        $this->controller->getRequest()->setHeaders($headers);

        $actual = $this->controller->getHeaderValue(self::$expectedHeaders['key']);

        $this->assertEquals(self::$expectedHeaders['value'], $actual);
    }

    /**
     * @expectedException \EyeChart\Exception\InvalidHeaderRequestException
     */
    public function testGetHeaderValueThrowsException(): void
    {
        $this->controller->getRequest()->setHeaders(new Headers());

        $this->controller->getHeaderValue('foo');
    }
}
