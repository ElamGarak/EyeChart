<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/11/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Entity;

use EyeChart\Entity\AbstractEntity;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Mappers\Mapper;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\VOInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class AbstractEntityTest
 * @package EyeChart\Tests\Entity
 */
final class AbstractEntityTest extends TestCase
{
    /** @var AbstractEntity|AuthenticateEntity */
    private $sut;

    public function setUp(): void
    {
        $this->sut = new AuthenticateEntity();
    }

    /**
     * @param mixed[]|VOInterface $dataSource
     * @dataProvider provideValidDataSources
     */
    public function testInitializeHappyPath($dataSource): void
    {
        $this->sut->initialize($dataSource);

        $this->assertEquals('foo', $this->sut->getUsername());
    }

    /**
     * @param mixed $dataSource
     * @dataProvider provideInvalidDateSources
     * @expectedException \EyeChart\Exception\InvalidDataSourceException
     */
    public function testInitializeThrowsInvalidDataSourceException($dataSource): void
    {
        $this->sut->initialize($dataSource);
    }

    public function testHydrateFromBaseHappyPath(): void
    {
        $dataSource = ['username' => 'foo'];

        $this->sut->hydrateFromDataBase($dataSource);

        $this->assertEquals('foo', $this->sut->getUsername());
    }

    public function testHydrateFromBaseEarlyContinueForUnknownPassedKey(): void
    {
        $dataSource = [
            'username' => 'foo',
            'foo'      => 'bar'
        ];

        $this->sut->hydrateFromDataBase($dataSource);

        $this->assertEquals('foo', $this->sut->getUsername());
    }

    /**
     * @param mixed[]|VOInterface $dataSource
     * @dataProvider provideValidDataSources
     */
    public function testToArrayReturnsArrayMatchingEntity($dataSource): void
    {
        $this->sut->initialize($dataSource);

        $values = $this->sut->toArray();

        $this->assertEquals($values['username'], $this->sut->getUsername());
    }

    /**
     * @return array[]
     */
    public function provideValidDataSources(): array
    {
        return [
            [
                [
                    'username' => 'foo'
                ]
            ],
            [
                AuthenticationVO::build()->setUsername('foo')
            ]
        ];
    }

    public function provideInvalidDateSources(): array
    {
        return [
            ['foo'],
            [1],
            [1.0],
            [null],
            [new stdClass()],
            [new Mapper()],
            [function () {}],
            [null]
        ];
    }
}
