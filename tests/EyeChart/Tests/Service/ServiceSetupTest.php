<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/28/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Service;

use EyeChart\Repository\Authentication\AuthenticationRepository;
use EyeChart\Service\Authenticate\AuthenticateService;
use EyeChart\VO\AuthenticationVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_Matcher_InvokedCount;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Authentication\AuthenticationService as ZendAuthentication;

/**
 * Class ServiceSetupTest
 *
 * NOTE: This class acts as an inherited helper, running common tests.  To do so it must be picked up by the testing
 *       framework when extended.  However, when this occurs, this class itself becomes part of the testing suite and
 *       will itself be run independent of the rest.  This results in testing failures unless the default static values
 *       are initialized with valid values.  In this case, the default subject under test selected is the Authenticate
 *       Authentication Service and its dependancies.  This eliminates the need for extra logic to handle empty defaults
 *       and provides the extra benefit of testing the Authenticate Service.
 *
 * @package EyeChart\Tests\Service
 */
class ServiceSetupTest extends TestCase
{
    /**
     * See setUp for more detail on default value
     *
     * @var Object
     */
    protected static $service;

    /**
     * ee setUp for more detail on default value
     *
     * @var AuthenticationRepository|PHPUnit_Framework_MockObject_MockObject
     */
    protected static $serviceDependency;

    public function setUp(): void
    {
        if (! is_null(self::$serviceDependency)) {
            // Concrete class has already set this value, do not run test against default
            parent::setUp();

            return;
        }

        // No default was defined (This class itself has been instantiated by the framework) set value here to satisfy
        // the tests below
        self::$serviceDependency = $this->getMockBuilder(AuthenticationRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var ZendAuthentication $zendAuthentication */
        $zendAuthentication = $this->getMockBuilder(ZendAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();

        // No default was defined (This class itself has been instantiated by the framework) set value here to satisfy
        // the tests below
        self::$service = new AuthenticateService(self::$serviceDependency, $zendAuthentication);

        parent::setUp();
    }

    /**
     * Service method test
     *
     * @param PHPUnit_Framework_MockObject_Matcher_InvokedCount $expects
     * @param string $serviceMethod
     * @param string $modelMethod
     * @param mixed $param
     * @param mixed $expected
     *
     * @dataProvider provideServices
     */
    public function testServiceMethod($expects, $serviceMethod, $modelMethod, $param, $expected): void
    {
        self::$serviceDependency->expects($expects)
            ->method($modelMethod)
            ->willReturn($expected);

        $actual = self::$service->{$serviceMethod}($param);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Default data provider (To be overridden by all concretes with their own provider
     *
     * @return array[]
     */
    public function provideServices()
    {
        $vo = new AuthenticationVO();

        return [
            [$this->once(), 'authenticateUser', 'authenticateUser', $vo, true],
            [$this->once(), 'checkSessionStatus', 'checkSessionStatus', $vo, null],
            [$this->once(), 'login', 'login', $vo, 'foo'],
            [$this->once(), 'logout', 'logout', $vo, []],
        ];
    }

    public function tearDown()
    {
        self::$service           = null;
        self::$serviceDependency = null;

        parent::tearDown();
    }
}
