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
use EyeChart\Mappers\EmployeeMapper;
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

    /** @var StorageInterface */
    private $sessionStorage;

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
        $this->sessionEntity->setId($this->sessionManager->getId());
        $this->sessionEntity->setName($this->sessionManager->getName());

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
        $this->sessionStorage = $this->authenticateStorageDao->read();

        if (array_key_exists($this->sessionEntity->getId(), $this->sessionStorage) === false) {
            return new Result(
                Result::FAILURE_IDENTITY_AMBIGUOUS,
                AuthenticateMapper::TOKEN,
                ['No credentials were found']
            );
        }

        $storage = $this->sessionStorage[$this->sessionEntity->getId()];

        if (array_key_exists($this->authenticateEntity->getToken(), $storage) === false) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                AuthenticateMapper::TOKEN,
                ['Access token was not found']
            );
        }

        $employeeUserId = $storage[$this->authenticateEntity->getToken()][EmployeeMapper::USER_ID];

        $this->authenticateDao->isUserValid($employeeUserId);

        if ($this->authenticateDao->isUserValid($employeeUserId) === false) {
            return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                AuthenticateMapper::TOKEN,
                ['Credentials are not valid']
            );
        }

        return new Result(Result::SUCCESS, $storage, ['User is Valid']);
    }
}
