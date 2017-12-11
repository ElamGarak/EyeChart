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
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\VOInterface;
use PHPUnit\Framework\TestCase;

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
}
