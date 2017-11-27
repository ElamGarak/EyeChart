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
use API\V1\Rpc\Login\LoginControllerFactory;
use EyeChart\Service\Authenticate\AuthenticateService;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class ApiControllerFactoryTest
 *
 * NOTE: This class acts as an inherited helper, running common tests.  To do so it must be picked up by the testing
 *       framework when extended.  However, when this occurs, this class itself becomes part of the testing suite and
 *       will itself be run independent of the rest.  This results in testing failures unless the default static values
 *       are initialized with valid values.  In this case, the default subject under test selected is the Login
 *       Controller Factory and its dependancies.  This eliminates the need for extra logic to handle empty defaults and
 *       provides the extra benefit of testing the Login Controller Factory.
 *
 * @package DriverManager\API\Tests
 */
class ApiControllerFactoryTest extends TestCase
{
    /** CONFIGURABLES (These are meant to be set within the concrete class that extends this class ********************/

    /**
     * Fully qualified name of factory class to be tested (See class documentation for default value)
     *
     * @var string
     */
    public static $factoryName = LoginControllerFactory::class;

    /**
     * Fully qualified name of controller class to be invoked (See class documentation for default value)
     *
     * @var string
     */
    public static $controllerName = LoginController::class;

    /**
     * Array of fully qualified classes to be mocked within the container (See class documentation for default value)
     *
     * @var string[]
     */
    public static $containerParams = [
        AuthenticateService::class
    ];

    /** NON-CONFIGURABLES (These are meant to used only within this class *********************************************/

    /**
     * Factory to be tested (Subject Under Test)
     *
     * @var object
     */
    private $factory;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $mockedContainer;

    /**
     * This ultimately sets up the subject under test with all required dependencies
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setSubjectFactoryForTest(new self::$factoryName)
             ->withContainerDependencies(self::$containerParams);
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
