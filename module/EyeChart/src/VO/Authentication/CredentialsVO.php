<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/4/2017
 * (c) 2017
 */

namespace EyeChart\VO\Authentication;

use Assert\Assertion;
use EyeChart\VO\VO;
use EyeChart\VO\VOInterface;

/**
 * Class CredentialsVO
 * @package EyeChart\VO\Authentication
 */
class CredentialsVO extends VO
{
    /** @var string  */
    private $credentials = '';

    /**
     * @return VOInterface|CredentialsVO
     */
    public static function build(): VOInterface
    {
        return new self;
    }

    /**
     * @return string
     */
    public function getCredentials(): string
    {
        return $this->credentials;
    }

    /**
     * @param string $credentials
     * @return CredentialsVO
     */
    public function setCredentials(string $credentials): CredentialsVO
    {
        Assertion::length(
            $credentials,
            512,
            "Supplied Credentials may only be exactly 512 characters in length"
        );

        $this->credentials = $credentials;

        return $this;
    }
}
