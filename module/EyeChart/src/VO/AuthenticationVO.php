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
}
