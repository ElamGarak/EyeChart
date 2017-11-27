<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace API\Tests;

use API\V1\Rpc\Login\LoginController;
use EyeChart\Service\Authenticate\AuthenticateService;
use EyeChart\Tests\Fixtures\CommandBusAuthenticationFixture;
use EyeChart\Tests\Fixtures\ValueTesting\EntityValuesFixture;
use EyeChart\Tests\Fixtures\ValueTesting\VOValuesFixture;
use EyeChart\VO\AuthenticationVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionClass;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class ApiControllerWithAuthenticationTest
 *
 * NOTE: This class acts as an inherited helper, running common tests.  To do so it must be picked up by the testing
 *       framework when extended.  However, when this occurs, this class itself becomes part of the testing suite and
 *       will itself be run independent of the rest.  This results in testing failures unless the default static values
 *       are initialized with valid values.  In this case, the default subject under test selected is the Login
 *       Controller and its dependancies.  This eliminates the need for extra logic to handle empty defaults and
 *       provides the extra benefit of testing the Login Controller.
 *
 * @package API\Tests
 */
class ApiControllerWithAuthenticationTest extends TestCase
{
    /** CONFIGURABLES (These are meant to be set within the concrete class that extends this class ********************/

    /**
     * Fully qualified name of controller class to be tested (See class documentation for default value)
     *
     * @var string
     */
    public static $controllerName = LoginController::class;

    /**
     * Fully qualified name of service class to be used for mocking (See class documentation for default value)
     *
     * @var string
     */
    public static $serviceName = AuthenticateService::class;

    /**
     * Name of the action method to be tested within the controller (See class documentation for default value)
     *
     * @var string
     */
    public static $actionMethod = 'loginAction';

    /**
     * NOTE: This is optional
     *
     * Fully qualified name of VO class to be used for input data testing (See class documentation for default value)
     *
     * @var string
     */
    public static $voName = AuthenticationVO::class;

    /**
     * NOTE: This is optional
     *
     * Fully qualified name of entity class to be used for input data testing
     *
     * @var string
     */
    public static $entityName = '';

    /**
     * An array of service method names that the controller being tested will have trigger
     * (See class documentation for default value)
     *
     * @var string[]
     */
    public static $serviceMethods = [
        'login' => 'a'
    ];

    /**
     * Flag if input payload testing is desired.
     *
     * Do not use this in cases where datatables or select2 payloads are expected.
     * (See class documentation for default value)
     *
     * @var bool
     */
    public static $runTestPayload = true;

    /**
     * Every controller will have some sort of expected return output which is captured here
     * (See class documentation for default value)
     *
     * @var mixed
     */
    public static $expectedReturn = [
        'token' => 'a'
    ];

    /** NON-CONFIGURABLES (These are meant to used only within this class *********************************************/

    /**
     * Controller to be tested (Subject Under Test)
     *
     * @var object|AbstractActionController
     */
    protected $controller;

    /**
     * Service to be mocked
     *
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $service;

    /**
     * Used in conjunction with self::$runTestPayload.  This will contain the expected payload with valid input data
     *
     * @var mixed[]
     */
    protected static $expectedPayload = [];

    /**
     * Fixture that contains mock of command bus authentication
     *
     * @var CommandBusAuthenticationFixture
     */
    private static $commandBusFixture;

    /**
     * Used in conjunction with self::$runTestPayload.  This will contain a payload with invalid input data
     *
     * @var mixed[]
     */
    private static $invalidPayload = [];

    /**
     * This sets up needed command bus dependency and expected payloads (if any)
     */
    public static function setUpBeforeClass(): void
    {
        // Since this class is part of a testing suite, it will also be picked up and ran on its own.  However, since
        // its only meant to only be extended, prevent it from returning tests by itself.
        if (empty(self::$controllerName)) {
            self::markTestSkipped('Inherited Test ' . __METHOD__ . 'ignored.');
        }

        parent::setUpBeforeClass();

        self::$commandBusFixture = new CommandBusAuthenticationFixture();
        self::setExpectedPayloads();
    }

    /**
     * This ultimately sets up the subject under test with all required dependencies
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setService();
        $this->setSubjectControllerForTest();
    }

    /**
     * Since this class is inherited by multiple concretes, each setting some or all of these static members, they need
     * to be reset after each set of tests per class is complete
     */
    public static function tearDownAfterClass(): void
    {
        self::$controllerName = LoginController::class;
        self::$serviceName    = AuthenticateService::class;
        self::$voName         = null;
        self::$entityName     = null;
        self::$serviceMethods = [
            'login' => 'a'
        ];
        self::$runTestPayload = true;
        self::$expectedReturn = [];
        self::$actionMethod = 'loginAction';
        self::$expectedPayload = [];

        parent::tearDownAfterClass();
    }

    /**
     * Test the happy path of the controller
     */
    public function testActionReturnsSuccess(): void
    {
        foreach (self::$serviceMethods as $serviceMethod => $expectedServiceReturn) {
            $this->service->expects($this->atLeastOnce())
                          ->method($serviceMethod)
                          ->willReturn($expectedServiceReturn);
        }

        /** @var \Zend\View\Model\ViewModel $layout */
        $layout = $this->controller->{self::$actionMethod}();

        // Perform final assertions
        $this->assertEquals(self::$expectedReturn, $layout->getVariables());
    }

    /**
     * Test that the controller will throw an API response if an exception is thrown
     */
    public function testActionReturnsProblemResponse(): void
    {
        foreach (self::$serviceMethods as $serviceMethod => $expectedServiceReturn) {
            $this->service->expects($this->any())
                          ->method($serviceMethod)
                          ->willThrowException($expectedServiceReturn = new \Exception("foo"));

            /** @var ApiProblemResponse $layout */
            $layout = $this->controller->{self::$actionMethod}();

            // Perform final assertions
            $this->assertEquals($expectedServiceReturn->getMessage(), $layout->getReasonPhrase());
        }
    }

    /**
     * Test that the controller will either return a problem response or throw a TypeError exception for invalid
     * payloads
     */
    public function testActionWithInvalidPayload(): void
    {
        if (self::$runTestPayload === false) {
            // Since subject under test was marked to skip payload test, make a true assertion to end this test early.
            $this->assertTrue(true);

            return;
        }

        $this->controller->getRequest()->setContent(json_encode(self::$invalidPayload));

        // In some instances, invalid payloads will throw type errors rather than test for an expected exception,
        // catch it here and assert true to end the test.
        $layout = null;
        try {
            $layout = $this->controller->{self::$actionMethod}();
        } catch (\TypeError $typeError) {
            $this->assertTrue(true);

            return;
        }

        // In other instances, invalid payloads are caught within the controller and
        // a problem response is returned, in these cases, assert true here to end the test
        if ($layout instanceof ApiProblemResponse) {
            $this->assertTrue(true);

            return;
        }

        // If neither of these cases are satisfied, it means the test has failed.
        $this->fail(self::$controllerName . ' failed when tested for invalid payloads');
    }

    /**
     * Mocks a service
     */
    private function setService(): void
    {
        $this->service = $this->getMockedStub(self::$serviceName);
    }

    /**
     * @param string $nameSpace
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedStub(string $nameSpace): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockBuilder($nameSpace)
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Instantiate subject controller to be tested an inject mocked dependancies
     */
    private function setSubjectControllerForTest(): void
    {
        switch (false) {
            case (empty(self::$entityName)):
                // Some controllers may still use entities, if so inject these here
                $this->controller = new self::$controllerName(
                    $this->service,
                    $this->getMockedStub(self::$entityName),
                    self::$commandBusFixture->getCommandBus()
                );

                break;
            case (empty(self::$serviceName)):
                // Inject service and command bus (both mocked)
                $this->controller = new self::$controllerName(
                    $this->service,
                    self::$commandBusFixture->getCommandBus()
                );

                break;
        }

        // Set into place final dependancies for testing controller
        $this->controller->getRequest()->setContent(json_encode(self::$expectedPayload));
        $this->controller->setEvent(self::$commandBusFixture->getEvent());
    }

    /**
     * Providing a payload test has been requested,
     * this will extract out what payload keys are
     * relevant to the controller name are expected.
     */
    private static function setExpectedPayloads()
    {
        if (self::$runTestPayload === true) {
            switch (false) {
                case (! (is_null(self::$entityName) && is_null(self::$voName))):
                    self::$expectedPayload = [
                        'username' => 'foo',
                        'password' => 'bar'
                    ];

                    self::$expectedReturn = [
                        'token'    => 'a',
                        'messages' => 'Login Successful',
                        'success'  => true
                    ];

                    break;

                case (! (empty(self::$entityName) && empty(self::$voName))):
                    throw new \InvalidArgumentException("VO and Entities may not be tested together");

                    break;
                case (empty(self::$voName)):
                    // A VO is being used to accept payloads
                    $voReflected     = new ReflectionClass(self::$voName);
                    $valuesGenerator = new VOValuesFixture($voReflected);

                    self::$expectedPayload = $valuesGenerator->getValidExpectationsAsArray();
                    self::$invalidPayload  = $valuesGenerator->getInValidExpectationsAsArray();

                    break;
                case (empty(self::$entityName)):
                    // An Entity is being used to accept payloads
                    $entityReflected = new ReflectionClass(self::$entityName);
                    $valuesGenerator = new EntityValuesFixture($entityReflected);

                    self::$expectedPayload = $valuesGenerator->getValidExpectationsAsArray();
                    self::$invalidPayload  = $valuesGenerator->getInValidExpectationsAsArray();

                    break;
            }
        }
    }
}
