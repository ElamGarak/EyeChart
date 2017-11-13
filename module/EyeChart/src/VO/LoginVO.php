<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\VO;

use Assert\Assertion;

/**
 * Class LoginVO
 * @package EyeChart\VO
 */
final class LoginVO extends AbstractVO
{

    /** @var string */
    protected $userName;

    /** @var string */
    protected $password;

    /**
     * LoginVO constructor.
     *
     * @param string $userName
     * @param string $passWord
     */
    public function __construct($userName, $passWord)
    {
        $this->setUserName($userName);
        $this->setPassword($passWord);
    }

    /**
     * @return string
     */
    public function getUserName():? string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getPassword():? string
    {
        return $this->password;
    }

    /**
     * @param string $userName
     */
    private function setUserName(string $userName): void
    {
        Assertion::notEmpty($userName, "Username was not provided");

        $this->userName = $userName;
    }

    /**
     * @param string $passWord
     */
    private function setPassword(string $passWord): void
    {
        Assertion::notEmpty($passWord, "Password was not provided");

        $this->password = $passWord;
    }
}
