<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/30/2017
 * (c) Eye Chart
 */

namespace EyeChart\Model\Authenticate;

use Assert\Assertion;
use Zend\Config\Config;

/**
 * Class EncryptionModel
 * @package EyeChart\Model\Authenticate
 */
final class EncryptionModel
{
    /** @var string */
    private $cipher;

    /** @var string */
    private $bytes;

    /** @var string */
    private $tag = '';

    /**
     * Encryption constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->setCipher($config->get('cipher'));
    }

    /**
     * @param string $data
     * @param string $key
     * @return string
     */
    public function encrypt(string $data, string $key): string
    {
        return openssl_encrypt($data, $this->cipher, $key, OPENSSL_RAW_DATA, $this->bytes, $this->tag);
    }

    /**
     * @param string $key
     * @return string
     */
    public function decrypt(string $key): string
    {
        // TODO Pull this from the DB
        $encryptedCredentials = $this->encrypt('password', 'username');

        return openssl_decrypt($encryptedCredentials, $this->cipher, $key, OPENSSL_RAW_DATA, $this->bytes, $this->tag);
    }

    /**
     * @param string $cipher
     */
    public function setCipher(string $cipher): void
    {
        Assertion::notBlank($cipher, 'No cipher was passed');
        Assertion::inArray($cipher, openssl_get_cipher_methods(), 'Invalid or unrecognized cyper was passed');

        $this->cipher = $cipher;

        $ivLength    = openssl_cipher_iv_length($this->cipher);
        $this->bytes = openssl_random_pseudo_bytes($ivLength);
    }
}
