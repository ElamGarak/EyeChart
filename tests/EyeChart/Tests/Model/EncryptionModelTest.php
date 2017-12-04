<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/1/2017
 * (c) 2017
 */


namespace EyeChart\Tests\Model\Authenticate;

use Defuse\Crypto\KeyProtectedByPassword;
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

    /** @var string */
    private static $cipher = 'aes-256-cbc';

    /** @var string */
    private static $encryptionAccessKey = 'Captain James T. Kirk';

    /** @var string */
    private static $passCodeBeforeEncryption = 'code 000 destruct 0';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$config = new Config([
            'authentication' => [
                'cipher' => self::$cipher
            ]
        ]);
    }

    public function setUp(): void
    {
        $this->model = new EncryptionModel(self::$config->get('authentication'));
    }

    public function testEncrypt(): void
    {
        $fooEncoded = $this->model->getEncoded('foo');
        $fooDecoded = $this->model->getDecoded('foo', $fooEncoded);

        //def10000def50200279ce24cccc729b59d342226b09a27aea8a19b1d8ee63b9bd556f3c90746b9a54b7b91a0f4e6c84ca0e5f32b1788513b22f1f0f8b1a537ee7873b14bb72151ff082703818ff174eff1dbd8ee80ccff634ee21db37c23d42da171552e427329aa755b0cb3053fff4a9d5a253e06f7ec2da0048d41f10ce91f56e4ce4024a58d3f65a57ad9a214b27bd9585ce0dbae044d16676828ff046c8a1b3bfdf8f1842ba3d6edf03fd207ac18d039414668862f104ca9b33277e4e147856046b3bcdfdc20f07d25ddb0318bb98d8ca93ee6076344e077a5ef77e5adcd6ee24ad4a73dfcf158b57cfabcc8fc72d274850c8a84acfac3e9aaf3f4a74534

        // Good
        $this->model->checkPassCodeValidity(
            'def10000def50200279ce24cccc729b59d342226b09a27aea8a19b1d8ee63b9bd556f3c90746b9a54b7b91a0f4e6c84ca0e5f32b1788513b22f1f0f8b1a537ee7873b14bb72151ff082703818ff174eff1dbd8ee80ccff634ee21db37c23d42da171552e427329aa755b0cb3053fff4a9d5a253e06f7ec2da0048d41f10ce91f56e4ce4024a58d3f65a57ad9a214b27bd9585ce0dbae044d16676828ff046c8a1b3bfdf8f1842ba3d6edf03fd207ac18d039414668862f104ca9b33277e4e147856046b3bcdfdc20f07d25ddb0318bb98d8ca93ee6076344e077a5ef77e5adcd6ee24ad4a73dfcf158b57cfabcc8fc72d274850c8a84acfac3e9aaf3f4a74534',
            'foo'
        );

        // Wrong password
        $this->model->checkPassCodeValidity(
            'def10000def50200279ce24cccc729b59d342226b09a27aea8a19b1d8ee63b9bd556f3c90746b9a54b7b91a0f4e6c84ca0e5f32b1788513b22f1f0f8b1a537ee7873b14bb72151ff082703818ff174eff1dbd8ee80ccff634ee21db37c23d42da171552e427329aa755b0cb3053fff4a9d5a253e06f7ec2da0048d41f10ce91f56e4ce4024a58d3f65a57ad9a214b27bd9585ce0dbae044d16676828ff046c8a1b3bfdf8f1842ba3d6edf03fd207ac18d039414668862f104ca9b33277e4e147856046b3bcdfdc20f07d25ddb0318bb98d8ca93ee6076344e077a5ef77e5adcd6ee24ad4a73dfcf158b57cfabcc8fc72d274850c8a84acfac3e9aaf3f4a74534',
            'bar'
        );

        // Bad stored code
        $this->model->checkPassCodeValidity(
            'def10000ef50200279ce24cccc729b59d342226b09a27aea8a19b1d8ee63b9bd556f3c90746b9a54b7b91a0f4e6c84ca0e5f32b1788513b22f1f0f8b1a537ee7873b14bb72151ff082703818ff174eff1dbd8ee80ccff634ee21db37c23d42da171552e427329aa755b0cb3053fff4a9d5a253e06f7ec2da0048d41f10ce91f56e4ce4024a58d3f65a57ad9a214b27bd9585ce0dbae044d16676828ff046c8a1b3bfdf8f1842ba3d6edf03fd207ac18d039414668862f104ca9b33277e4e147856046b3bcdfdc20f07d25ddb0318bb98d8ca93ee6076344e077a5ef77e5adcd6ee24ad4a73dfcf158b57cfabcc8fc72d274850c8a84acfac3e9aaf3f4a74534',
            'bar'
        );
exit;
        $encryptionKey = base64_encode(self::$encryptionAccessKey);

        $dataToDecrypt = $this->model->encrypt(self::$passCodeBeforeEncryption, $encryptionKey);

        $encryptionKey = base64_decode($encryptionKey);
        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        list($encrypted_data, $iv) = explode('::', base64_decode($dataToDecrypt), 2);
        $decrypt = openssl_decrypt($encrypted_data, self::$cipher, $encryptionKey, 0, $iv);

        if (false === $decrypt) {
            echo "OpenSSL error: %s", openssl_error_string();
        }

        $this->assertEquals($decrypt, self::$passCodeBeforeEncryption);
    }

//    /**
//     * @expectedException  \EyeChart\Exception\Base64EncodingDecodingException
//     */
//    public function testEncryptThrowsBase64DecodeException(): void
//    {
//        $this->model->encrypt('-', '-');
//    }
//
//    /**
//     * @expectedException  \Assert\InvalidArgumentException
//     */
//    public function testEncryptThrowsAssertionExceptionForUnknownCypher(): void
//    {
//        $encryption_key = base64_encode(self::$encryptionAccessKey);
//
//        $model = new EncryptionModel(
//            new Config([
//                'cipher' => 'aes-256-gcmzz'
//            ])
//        );
//
//        $model->encrypt(self::$passCodeBeforeEncryption, $encryption_key);
//    }
//
//    /**
//     * @expectedException  \EyeChart\Exception\EncryptionFailureException
//     */
//    public function testEncryptThrows(): void
//    {
//        //$this->markTestIncomplete();
//
//        $encryption_key = base64_encode(self::$encryptionAccessKey);
//
//        $model = new EncryptionModel(
//            new Config([
//                'cipher' => 'aes-256-gcm'
//            ])
//        );
//
//        $model->encrypt(self::$passCodeBeforeEncryption, $encryption_key);
//    }
//
////    public function testEncryptThrowsBase64Exception(): void
////    {
////
////    }
//
//    public function testDecrypt(): void
//    {
//        //$key = 'jpacheco';
//
//        // Remove the base64 encoding from our key
//        $encryption_key = base64_decode(self::$encryptionAccessKey);
//
//        // Generate an initialization vector
//        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher));
//
//        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
//        $encrypted = openssl_encrypt(self::$passCodeBeforeEncryption, self::$cipher, $encryption_key, 0, $iv);
//
//        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
//        $expected = base64_encode($encrypted . '::' . $iv);
//
//        $result = $this->model->decrypt($expected, self::$encryptionAccessKey);
//
//        $this->assertEquals(self::$passCodeBeforeEncryption, $result);
//    }
//
//    /**
//     * @param string $cipher
//     * @dataProvider provideInvalidCiphers
//     * @expectedException \Assert\InvalidArgumentException
//     */
//    public function testSetCipherThrowsAssertions(string $cipher): void
//    {
//        $this->model->setCipher($cipher);
//    }
//
//    /**
//     * @return array[]
//     */
//    public function provideInvalidCiphers(): array
//    {
//        return [
//            [''],
//            ['foo'],
//            ['aes-257-gcm']
//        ];
//    }
//
//    /**
//     * @param mixed $cipher
//     * @dataProvider provideInvalidDataTypes
//     * @expectedException \TypeError
//     */
//    public function testSetCipherThrowsTypeErrors($cipher): void
//    {
//        $this->model->setCipher($cipher);
//    }
//
//    /**
//     * @return array[]
//     */
//    public function provideInvalidDataTypes(): array
//    {
//        return [
//            [null],
//            [[]],
//            [new stdClass()],
//            [true],
//            [false],
//            [1],
//            [0],
//            [1.1],
//        ];
//    }
}
