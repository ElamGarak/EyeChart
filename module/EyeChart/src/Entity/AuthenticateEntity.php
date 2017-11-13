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

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUserName(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        if (trim($token) !== '') {
            Assertion::length($token, 36, 'Token not provided');

            $this->token = $token;
        }
    }

    /**
     * @return bool
     */
    public function getIsValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     */
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }

    /**
     * @return mixed[]
     */
    public function getUserData(): array
    {
        return $this->userData;
    }

    /**
     * @param array $userData
     */
    public function setUserData(array $userData): void
    {
        $this->userData = $userData;
    }
}
