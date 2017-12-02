<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/1/2017
 * (c) 2017
 */


namespace EyeChart\Tests\Model\Authenticate;

use EyeChart\Model\Authenticate\EncryptionModel;
use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\Config\Config;

/**
 * Class EncryptionModelTest
 * @package EyeChart\Tests\Model\Authenticate
 */
class EncryptionModelTest extends TestCase
{
    /** @var EncryptionModel */
    private $model;

    /** @var Config */
    private static $config;

    /** @var array[] */
    private static $configSettings = [
        'authentication' => [
            'cipher' => ''
        ],
    ];

    /** @var string */
    private static $cipher = 'aes-256-gcm';

    /** @var string */
    private static $bytes = '';

    /** @var string|null */
    private static $tag = '';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$configSettings['authentication']['cipher'] = self::$cipher;

        self::$config = new Config(self::$configSettings);

        $ivLength    = openssl_cipher_iv_length(self::$cipher);
        self::$bytes = openssl_random_pseudo_bytes($ivLength);
    }

    public function setUp(): void
    {
        $this->model = new EncryptionModel(self::$config->get('authentication'));
        $this->model->setBytes(self::$bytes);
        $this->model->setTag(self::$tag);
    }

    public function testEncrypt(): void
    {
        $expected = openssl_encrypt('foo', self::$cipher , 'bar', OPENSSL_RAW_DATA, self::$bytes, self::$tag);

        $result = $this->model->encrypt('foo', 'bar');

        $this->assertEquals($result, $expected);
    }

    public function testDecrypt(): void
    {
        $encryptedFoo = openssl_encrypt('foo', self::$cipher , 'bar', OPENSSL_RAW_DATA, self::$bytes, self::$tag);

        $result = $this->model->decrypt($encryptedFoo, 'bar');

        $this->assertEquals('foo', $result);
    }

    /**
     * @param string $cipher
     * @dataProvider provideInvalidCiphers
     * @expectedException \Assert\InvalidArgumentException
     */
    public function testSetCipherThrowsAssertions(string $cipher): void
    {
        $this->model->setCipher($cipher);
    }

    /**
     * @return array[]
     */
    public function provideInvalidCiphers(): array
    {
        return [
            [''],
            ['foo'],
            ['aes-257-gcm']
        ];
    }

    /**
     * @param mixed $cipher
     * @dataProvider provideInvalidDataTypes
     * @expectedException \TypeError
     */
    public function testSetCipherThrowsTypeErrors($cipher): void
    {
        $this->model->setCipher($cipher);
    }

    /**
     * @param mixed $tag
     * @dataProvider provideInvalidDataTypes
     * @expectedException \TypeError
     */
    public function testSetTagThrowsTypeErrors($tag): void
    {
        $this->model->setTag($tag);
    }

    /**
     * @return array[]
     */
    public function provideInvalidDataTypes(): array
    {
        return [
            [null],
            [[]],
            [new stdClass()],
            [true],
            [false],
            [1],
            [0],
            [1.1],
        ];
    }

    public function testGetBytesReturnsPreSetBytes(): void
    {
        $actual = $this->model->getBytes();

        $this->assertEquals(self::$bytes, $actual);
    }

    public function testGetBytesReturnsDifferentBytesIfNotPreset(): void
    {
        $this->model = new EncryptionModel(self::$config->get('authentication'));

        $actual = $this->model->getBytes();

        $this->assertNotEquals(self::$bytes, $actual);
    }
}
