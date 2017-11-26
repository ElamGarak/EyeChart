<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Service\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\SessionMapper;
use EyeChart\VO\AuthenticationVO;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Session\SessionManager;

/**
 * Class AuthenticateAdapter
 * @package EyeChart\Service\Authenticate
 */
final class AuthenticateAdapter implements AdapterInterface
{

    /** @var SessionManager */
    private $sessionManager;

    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /** @var AuthenticateDAO  */
    private $authenticateDao;

    /** @var AuthenticateStorageDAO */
    private $authenticateStorageDao;

    /** @var SessionEntity */
    private $sessionEntity;

    /**
     * AuthenticateAdapter constructor
     *
     * @param SessionManager $sessionManager
     * @param EntityInterface|SessionEntity $sessionEntity
     * @param EntityInterface|AuthenticateEntity $authenticateEntity
     * @param AuthenticateDAO $authenticateDao
     * @param StorageInterface|AuthenticateStorageDAO $authenticateStorageDao
     */
    public function __construct(
        SessionManager $sessionManager,
        EntityInterface $sessionEntity,
        EntityInterface $authenticateEntity,
        AuthenticateDAO $authenticateDao,
        StorageInterface $authenticateStorageDao
    ) {
        $this->sessionManager = $sessionManager;
        $this->sessionManager->start();

        $this->sessionEntity = $sessionEntity;
        $this->sessionEntity->setSessionId($this->sessionManager->getId());
        $this->sessionEntity->setPhpSessionId($this->sessionManager->getName());

        $this->authenticateEntity     = $authenticateEntity;
        $this->authenticateDao        = $authenticateDao;
        $this->authenticateStorageDao = $authenticateStorageDao;
    }

    /**
     * Performs an authentication attempt
     *
     * @return Result
     *
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate(): Result
    {
        $this->sessionEntity->setToken($this->authenticateEntity->getToken());

        $this->authenticateStorageDao->read();

        if ($this->sessionEntity->isSessionId() === false) {
            return new Result(
                Result::FAILURE_IDENTITY_AMBIGUOUS,
                SessionMapper::SESSION_RECORD_ID,
                ['PHP Session ID was not found']
            );
        }

        if ($this->sessionEntity->isSessionRecordId() === false) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                AuthenticateMapper::TOKEN,
                ['Access token record was not found']
            );
        }

        $authenticateVO = AuthenticationVO::build()->setUsername($this->sessionEntity->getSessionUser());

        if ($this->authenticateDao->isUserActive($authenticateVO) === false) {
            return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                AuthenticateMapper::TOKEN,
                ['User is not active on this system']
            );
        }

        return new Result(Result::SUCCESS, $this->sessionEntity->getToken(), ['User is Valid']);
    }
}
