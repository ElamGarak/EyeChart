<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/30/2017
 * (c) Eye Chart
 */

namespace EyeChart\Model\Authenticate;

use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\KeyProtectedByPassword;

/**
 * Class EncryptionModel
 * @package EyeChart\Model\Authenticate
 *
 * TODO
 *  1) Rename this class, its no longer used for encryption and instaed will be used as a glorified password valididy checker
 *  2) Capture valid exceptions and rethrow using what has been created
 *  3) Rewrite unit test (No need to slam this hard, just test my logic)
 *  4) Continue stitching this into the application
 */
final class EncryptionModel
{
    /**
     * @param string $stringToEncode
     * @return string
     * @throws EnvironmentIsBrokenException
     */
    public function getEncoded(string $stringToEncode): string
    {
        $protectedKey = KeyProtectedByPassword::createRandomPasswordProtectedKey($stringToEncode);

        return $protectedKey->saveToAsciiSafeString();
    }

    /**
     * @param string $key
     * @param string $protectedString
     * @return string
     * @throws BadFormatException
     * @throws EnvironmentIsBrokenException
     * @throws WrongKeyOrModifiedCiphertextException
     */
    public function getDecoded(string $key, string $protectedString): string
    {
        $protectedKey = KeyProtectedByPassword::loadFromAsciiSafeString($protectedString);
        $userKey      = $protectedKey->unlockKey($key);

        return $userKey->saveToAsciiSafeString();
    }

    public function checkPassCodeValidity(string $storedCode, string $inputCode)
    {
        $protectedKey = KeyProtectedByPassword::loadFromAsciiSafeString($storedCode);
        $userKey      = $protectedKey->unlockKey($inputCode);
    }
}
