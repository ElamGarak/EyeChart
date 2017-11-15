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
    private $token;

    /**
     * TokenVO constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->setToken($token);
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
    private function setToken(string $token): void
    {
        Assertion::length($token, 36, 'Invalid token provided');

        $this->token = $token;
    }
}
