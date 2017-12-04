<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/15/2017
 * (c) 2017
 */

namespace EyeChart\VO\Authentication;

use Assert\Assertion;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\AbstractVO;
use EyeChart\VO\VOInterface;

/**
 * Class AuthenticationVO
 * @package EyeChart\VO\Authentication\
 */
final class AuthenticationVO extends AbstractVO
{
    /** @var string */
    protected $username = '';

    /** @var string */
    protected $password = '';

    /** @var string */
    protected $token = '';

    /** @var CredentialsVO[] */
    protected $credentials = [];

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
     * @return CredentialsVO[]
     */
    public function getCredentials(): array
    {
        return $this->credentials;
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

    /**
     * @param string $credentials
     * @param string $key
     * @return AuthenticationVO
     */
    public function addCredentials(string $credentials, string $key): AuthenticationVO
    {
        $this->credentials[$key] = CredentialsVO::build()->setCredentials($credentials);

        return $this;
    }
}
