<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 10/31/2017
 * (c) 2017 Swift Transportation
 */

namespace DriverManager\Tests\Fixtures;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerInterfaceFixture
 * @package Fixtures
 */
class ContainerInterfaceFixture extends TestCase
{
    /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $mockedContainer;

    public function setUp()
    {
        parent::setUp();

        $this->mockedContainer = $this->getMockBuilder(ContainerInterface::class)
                                      ->disableOriginalConstructor()
                                      ->getMock();
    }

    /**
     * @param object $factory
     * @param object[] $returnCallbacks
     *
     * @return object
     */
    public function invokeFactory($factory, array $returnCallbacks)
    {
        $this->mockedContainer->expects($this->any())
            ->method('get')
            ->willReturnCallback(function ($param) use ($returnCallbacks) {
                foreach ($returnCallbacks as $name => $returnCallback) {
                    if (gettype($returnCallback) !== 'object') {
                        throw new \UnexpectedValueException('Invalid return object passed');
                    }

                    if ($param === $name) {
                        return $returnCallback;
                    }
                }

                return null;
            });

        return $factory->__invoke($this->mockedContainer);
    }
}
