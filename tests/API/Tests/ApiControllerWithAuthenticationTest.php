<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 11/1/2017
 * (c) 2017 Swift Transportation
 */

namespace DriverManager\API\Tests;

use DriverManager\Tests\Fixtures\CommandBusAuthenticationFixture;
use DriverManager\Tests\Fixtures\ValueTesting\EntityValuesFixture;
use DriverManager\Tests\Fixtures\ValueTesting\VOValuesFixture;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionClass;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class ApiControllerWithAuthenticationTest
 * @package DriverManager\API\Tests
 * @group API
 */
class ApiControllerWithAuthenticationTest extends TestCase
{
    /** CONFIGURABLES (These are meant to be set within the concrete class that extends this class ********************/

    /**
     * Fully qualified name of controller class to be tested
     *
     * @var string
     */
    public static $controllerName = '';

    /**
     * Fully qualified name of service class to be used for mocking
     *
     * @var string
     */
    public static $serviceName = '';

    /**
     * Name of the action method to be tested within the controller
     *
     * @var string
     */
    public static $actionMethod = '';

    /**
     * NOTE: This is optional
     *
     * Fully qualified name of VO class to be used for input data testing
     *
     * @var string
     */
    public static $voName = '';

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
     *
     * @var string[]
     */
    public static $serviceMethods = [];

    /**
     * Flag if input payload testing is desired.
     *
     * Do not use this in cases where datatables or select2 payloads are expected.
     *
     * @var bool
     */
    public static $runTestPayload = false;

    /**
     * Every controller will have some sort of expected return output which is captured here
     *
     * @var mixed
     */
    public static $expectedReturn;

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
        self::$controllerName = '';
        self::$serviceName    = '';
        self::$voName         = '';
        self::$entityName     = '';
        self::$serviceMethods = [];
        self::$runTestPayload = false;
        self::$expectedReturn = null;
       
        parent::tearDownAfterClass();
    }

    /** TESTS *********************************************************************************************************/

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
            $this->markTestSkipped('Subject ' . self::$controllerName . ' under test does not accept payloads');

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

        // In other instances, invalid payloads are caught within the controller and a problem response is returned, in
        // these cases, assert true here to end the test
        if ($layout instanceof ApiProblemResponse) {
            $this->assertTrue(true);

            return;
        }

        // If neither of these cases are satisfied, it means the test has failed.
        $this->fail(self::$controllerName . ' failed when tested for invalid payloads');
    }

    /** HELPERS *******************************************************************************************************/

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
     * Providing a payload test has been requested, this will extract out what payload keys relevant to the controller
     * name are expected.
     */
    private static function setExpectedPayloads()
    {
        if (self::$runTestPayload === true) {
            switch (false) {
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
