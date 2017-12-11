<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace EyeChart\Tests\VO;

use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\VO;
use EyeChart\VO\TokenVO;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractVOTest
 * @package EyeChart\Tests\VO
 */
final class AbstractVOTest extends TestCase
{

    /**
     * @expectedException \EyeChart\Exception\InvalidDynamicSettingException
     */
    public function testMagicSetterThrowsException(): void
    {
        $subjectVO = new VO();

        /** @noinspection PhpUndefinedFieldInspection */
        $subjectVO->foo = 'bar';
    }

    public function testToArray(): void
    {
        $expectedValue = str_repeat('a', AuthenticateMapper::TOKEN_LENGTH);

        $concreteVO = TokenVO::build()->setToken($expectedValue);
        $actual = $concreteVO->toArray();

        $this->assertInternalType('array', $actual);
        $this->assertEquals($expectedValue, $actual['token']);
    }
}
