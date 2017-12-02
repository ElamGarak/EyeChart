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
     * @param string $dataToEncrypt
     * @param string $key
     * @return string
     */
    public function encrypt(string $dataToEncrypt, string $key): string
    {
        return openssl_encrypt($dataToEncrypt, $this->cipher, $key, OPENSSL_RAW_DATA, $this->getBytes(), $this->tag);
    }

    /**
     * @param string $dataToDecrypt
     * @param string $key
     * @return string
     */
    public function decrypt(string $dataToDecrypt, string $key): string
    {
        return openssl_decrypt($dataToDecrypt, $this->cipher, $key, OPENSSL_RAW_DATA, $this->getBytes(), $this->tag);
    }

    /**
     * @param string $cipher
     */
    public function setCipher(string $cipher): void
    {
        Assertion::notBlank($cipher, 'No cipher was passed');
        Assertion::inArray($cipher, openssl_get_cipher_methods(), 'Invalid or unrecognized cypher was passed');

        $this->cipher = $cipher;
    }


    public function setBytes(string $bytes): void
    {
        Assertion::notBlank($bytes, 'No blank bytes value may be passed');

        $this->bytes = $bytes;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function getBytes(): string
    {
        if (empty($this->bytes)) {
            $ivLength    = openssl_cipher_iv_length($this->cipher);
            $this->bytes = openssl_random_pseudo_bytes($ivLength);
        }

        return $this->bytes;
    }
}
