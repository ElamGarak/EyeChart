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
 * Class TokenVO
 * @package EyeChart\VO
 */
final class TokenVO extends AbstractVO
{

    /** @var string */
    protected $token;

    /**
     * @return VOInterface|TokenVO
     */
    public static function build(): VOInterface
    {
        return new self;
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
     * @return TokenVO
     */
    public function setToken(string $token): TokenVO
    {
        Assertion::length($token, 36, 'Invalid token provided');

        $this->token = $token;

        return $this;
    }
}
