<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/11/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Entity;

use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Mappers\AuthenticateMapper;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthenticateEntityTest
 * @package EyeChart\Tests\Entity
 */
class AuthenticateEntityTest extends TestCase
{
    /** @var AuthenticateEntity */
    private $entity;

    public function setUp(): void
    {
        $this->entity = new AuthenticateEntity();
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     */
    public function testSetTokenThrowsAssertionExceptionWhenTokenIsIncorrectLength(): void
    {
        $this->entity->setToken('foo');
    }

    public function testAddMessageDoesNotThrowsAssertionWhenTokenIsIncorrectLength(): void
    {
        $token = str_repeat('a', AuthenticateMapper::TOKEN_LENGTH);

        $this->entity->setToken($token);

        $results = $this->entity->getToken();

        $this->assertEquals($token, $results);
    }

    public function testSetTokenPassesThroughWhenTokenIsBlank(): void
    {
        $result = $this->entity->setToken('');

        $this->assertInstanceOf(AuthenticateEntity::class, $result);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     */
    public function testAddMessageThrowsAssertionExceptionWhenMessageIsBlank(): void
    {
        $this->entity->addMessage('');
    }

    public function testAddMessageDoesNotThrowsAssertionExceptionWhenMessageIsNotBlank(): void
    {
        $this->entity->addMessage('foo');

        $results = $this->entity->getMessages();

        $this->assertEquals('foo', $results[0]);
    }
}
