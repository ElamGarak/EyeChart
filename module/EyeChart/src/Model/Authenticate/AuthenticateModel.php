<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/4/2017
 * (c) 2017
 */

namespace EyeChart\Model\Authenticate;

use Defuse\Crypto\Exception\CryptoException;
use Defuse\Crypto\KeyProtectedByPassword;
use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\UnableToAuthenticateException;
use EyeChart\Exception\UserCredentialsInvalidException;
use EyeChart\Exception\UserNotActiveException;
use EyeChart\Exception\UserNotFoundException;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\VOInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class AuthenticateModel
 * @package EyeChart\Model\Authenticate
 */
class AuthenticateModel
{
    /** @var AuthenticateDAO */
    private $authenticateDAO;

    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /** @var SessionEntity */
    private $sessionEntity;

    /**
     * AuthenticateModel constructor.
     * @param AuthenticateDAO $authenticateDAO
     * @param EntityInterface|AuthenticateEntity $authenticateEntity
     * @param SessionEntity $sessionEntity
     */
    public function __construct(
        AuthenticateDAO $authenticateDAO,
        EntityInterface $authenticateEntity,
        SessionEntity $sessionEntity
    ) {
        $this->authenticateDAO    = $authenticateDAO;
        $this->authenticateEntity = $authenticateEntity;
        $this->sessionEntity      = $sessionEntity;
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @throws UnableToAuthenticateException
     * @throws UserCredentialsInvalidException
     */
    public function checkCredentials(VOInterface $authenticationVO): void
    {
        try {
            $protectedKey = KeyProtectedByPassword::loadFromAsciiSafeString(
                $authenticationVO->getDerivedCredentials()->getCredentials()
            );
            $protectedKey->unlockKey($authenticationVO->getPassword());

            $this->authenticateEntity->setIsValid(true);
        } catch (CryptoException $exception) {
            throw new UnableToAuthenticateException($authenticationVO);
        }
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @return SessionEntity
     */
    public function generateSessionEntity(VOInterface $authenticationVO): SessionEntity
    {
        return $this->sessionEntity
            ->setToken(Uuid::uuid1()->toString())
            ->setSessionUser($authenticationVO->getUsername())
            ->setLastActive(time());
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @codeCoverageIgnore
     */
    public function setTokenToAuthenticate(VOInterface $authenticationVO): void
    {
        $this->authenticateEntity->setToken($authenticationVO->getToken());
    }

    /**
     * @param string $message
     * @codeCoverageIgnore
     */
    public function addMessage(string $message): void
    {
        $this->authenticateEntity->addMessage($message);
    }

    /**
     * @return string[]
     * @codeCoverageIgnore
     */
    public function getMessages(): array
    {
        return $this->authenticateEntity->getMessages();
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @return string
     * @throws UserNotFoundException
     * @throws UserNotActiveException
     */
    public function getUsersStoredCredentials(VOInterface $authenticationVO): string
    {

        $results = $this->authenticateDAO->getUsersStoredCredentials($authenticationVO);

        if (!array_key_exists(AuthenticateMapper::CREDENTIALS, $results)) {
            throw new UserNotFoundException($authenticationVO->getUsername());
        }

        if ($results[AuthenticateMapper::IS_ACTIVE] === 0) {
            throw new UserNotActiveException($authenticationVO->getUsername());
        }

        return $results[AuthenticateMapper::CREDENTIALS];
    }


    /**
     * @param string $stringToEncode
     * @return string
     * @codeCoverageIgnore
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
     * @codeCoverageIgnore
     */
    public function getDecoded(string $key, string $protectedString): string
    {
        $protectedKey = KeyProtectedByPassword::loadFromAsciiSafeString($protectedString);
        $userKey      = $protectedKey->unlockKey($key);

        return $userKey->saveToAsciiSafeString();
    }
}
