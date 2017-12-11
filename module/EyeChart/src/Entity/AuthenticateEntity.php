<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Entity;

use Assert\Assertion;
use EyeChart\Mappers\AuthenticateMapper;

/**
 * Class AuthenticateEntity
 * @package EyeChart\Entity\Authenticate
 */
class AuthenticateEntity extends AbstractEntity
{
    /** @var string */
    protected $token = '';

    /** @var string */
    protected $username = '';

    /** @var string */
    protected $password = '';

    /** @var bool */
    protected $isValid = false;

    /** @var mixed[] */
    protected $userData = [];

    /** @var string[] */
    protected $messages = [];

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return AuthenticateEntity
     * @codeCoverageIgnore
     */
    public function setUserName(string $username): AuthenticateEntity
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return AuthenticateEntity
     * @codeCoverageIgnore
     */
    public function setPassword(string $password): AuthenticateEntity
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return AuthenticateEntity
     */
    public function setToken(string $token): AuthenticateEntity
    {
        if (trim($token) !== '') {
            Assertion::length($token, AuthenticateMapper::TOKEN_LENGTH, 'Token not provided');

            $this->token = $token;
        }

        return $this;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function getIsValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     * @return AuthenticateEntity
     * @codeCoverageIgnore
     */
    public function setIsValid(bool $isValid): AuthenticateEntity
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * @return mixed[]
     * @codeCoverageIgnore
     */
    public function getUserData(): array
    {
        return $this->userData;
    }

    /**
     * @param array $userData
     * @return AuthenticateEntity
     * @codeCoverageIgnore
     */
    public function setUserData(array $userData): AuthenticateEntity
    {
        $this->userData = $userData;

        return $this;
    }

    /**
     * @return string[]
     * @codeCoverageIgnore
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param string $message
     */
    public function addMessage(string $message): void
    {
        Assertion::notBlank($message, 'Message may not be blank');

        $this->messages[] = $message;
    }
}
