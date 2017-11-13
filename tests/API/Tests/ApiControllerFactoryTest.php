<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 11/1/2017
 * (c) 2017 Swift Transportation
 */

namespace DriverManager\API\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class ApiControllerFactoryTest
 * @package DriverManager\API\Tests
 * @group API
 */
class ApiControllerFactoryTest extends TestCase
{
    /** CONFIGURABLES (These are meant to be set within the concrete class that extends this class ********************/

    /**
     * Fully qualified name of factory class to be tested
     *
     * @var string
     */
    public static $factoryName;

    /**
     * Fully qualified name of controller class to be invoked
     *
     * @var string
     */
    public static $controllerName;

    /**
     * Array of fully qualified classes to be mocked within the container
     *
     * @var string[]
     */
    public static $containerParams = [];

    /** NON-CONFIGURABLES (These are meant to used only within this class *********************************************/

    /**
     * Factory to be tested (Subject Under Test)
     *
     * @var object
     */
    private $factory;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $mockedContainer;

    public static function setUpBeforeClass(): void
    {
        // Since this class is part of a testing suite, it will also be picked up and ran on its own.  However, since
        // its only meant to only be extended, prevent it from returning tests by itself.
        if (empty(self::$factoryName)) {
            self::markTestSkipped('Inherited Test ' . __METHOD__ . 'ignored.');
        }
    }

    /**
     * This ultimately sets up the subject under test with all required dependencies
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setSubjectFactoryForTest(new self::$factoryName)
             ->withContainerDependencies(self::$containerParams);
    }

    /**
     * Since this class is inherited by multiple concretes, each setting some or all of these static members, they need
     * to be reset after each set of tests per class is complete
     */
    public static function tearDownAfterClass(): void
    {
        self::$factoryName     = null;
        self::$controllerName  = null;
        self::$containerParams = [];

        parent::tearDownAfterClass();
    }

    /** TESTS *********************************************************************************************************/

    public function testFactoryReturnsCorrectInstance(): void
    {
        $this->assertInstanceOf(self::$controllerName, $this->invokeSubjectFactory());
    }

    /** HELPERS *******************************************************************************************************/

    /**
     * @param object $factory
     * @return self
     * @throws \InvalidArgumentException
     */
    private function setSubjectFactoryForTest($factory): self
    {
        if (! is_object($factory)) {
            throw new \InvalidArgumentException("Invalid factoryName object passed");
        }

        $this->factory = $factory;

        return $this;
    }

    /**
     * @return object
     */
    private function invokeSubjectFactory()
    {
        return $this->factory->__invoke($this->mockedContainer);
    }

    /**
     * @param string[] $qualifiedNameSpaces
     * @return self
     * @throws \UnexpectedValueException
     */
    private function withContainerDependencies(array $qualifiedNameSpaces): self
    {
        $this->mockedContainer = $this->getMockBuilder(ContainerInterface::class)
             ->disableOriginalConstructor()
             ->getMock();

        $this->mockedContainer->expects($this->any())
             ->method('get')
             ->willReturnCallback(function ($param) use ($qualifiedNameSpaces) {
                foreach ($qualifiedNameSpaces as $qualifiedNameSpace) {
                    if ($param === $qualifiedNameSpace) {
                        return $this->getMockBuilder($qualifiedNameSpace)
                                    ->disableOriginalConstructor()
                                    ->getMock();
                    }
                }

                return null;
             });

        return $this;
    }
}
