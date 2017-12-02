<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/15/2017
 * (c) 2017
 */

namespace EyeChart\VO;

use Assert\Assertion;
use EyeChart\Mappers\AuthenticateMapper;

/**
 * Class AuthenticationVO
 * @package EyeChart\VO
 */
final class AuthenticationVO extends AbstractVO
{
    /** @var string */
    protected $username = '';

    /** @var string */
    protected $password = '';

    /** @var string */
    protected $credentials = '';

    /** @var string */
    protected $byteCode = '';

    /** @var string */
    protected $tag = '';

    /** @var string */
    protected $token = '';

    /**
     * @return VOInterface|AuthenticationVO
     */
    public static function build(): VOInterface
    {
        return new self;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * @return string
     */
    public function getByteCode(): string
    {
        return $this->byteCode;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $userName
     * @return AuthenticationVO
     */
    public function setUsername(string $userName): AuthenticationVO
    {
        Assertion::notEmpty($userName, "Username was not provided");

        $this->username = $userName;

        return $this;
    }

    /**
     * @param string $password
     * @return AuthenticationVO
     */
    public function setPassword(string $password): AuthenticationVO
    {
        Assertion::notEmpty($password, "Password was not provided");

        $this->password = $password;

        return $this;
    }

    /**
     * @param string $credentials
     * @return AuthenticationVO
     */
    public function setCredentials(string $credentials): AuthenticationVO
    {
        Assertion::notEmpty($credentials, "Credentials may not be empty");

        $this->credentials = $credentials;

        return $this;
    }

    /**
     * @param string $byteCode
     * @return AuthenticationVO
     */
    public function setByteCode(string $byteCode): AuthenticationVO
    {
        Assertion::notEmpty($byteCode, "Byte code may not be empty");

        $this->byteCode = $byteCode;

        return $this;
    }

    /**
     * @param string $tag
     * @return AuthenticationVO
     */
    public function setTag(string $tag): AuthenticationVO
    {
        Assertion::notEmpty($tag, "Tag may not be empty");

        $this->tag = $tag;

        return $this;
    }

    /**
     * @param string $token
     * @return AuthenticationVO
     */
    public function setToken(string $token): AuthenticationVO
    {
        if (! empty($token)) {
            Assertion::length($token, AuthenticateMapper::TOKEN_LENGTH, "Invalid Token was passed");
        }

        $this->token = $token;

        return $this;
    }
}
