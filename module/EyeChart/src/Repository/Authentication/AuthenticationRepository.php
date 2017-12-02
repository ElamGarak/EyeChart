<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/4/2017
 * (c) 2017
 */

namespace EyeChart\Repository\Authentication;

use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\NoResultsFoundException;
use EyeChart\Exception\UnableToAuthenticateException;
use EyeChart\Exception\UnauthorizedException;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\SessionMapper;
use EyeChart\Model\Authenticate\AuthenticateModel;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;
use EyeChart\Model\Authenticate\EncryptionModel;
use EyeChart\Model\Employee\EmployeeModel;
use EyeChart\Service\Authenticate\AuthenticateAdapter;
use EyeChart\VO\AuthenticationVO;
use EyeChart\VO\TokenVO;
use EyeChart\VO\VOInterface;
use Zend\Authentication\AuthenticationService as ZendAuthentication;
use Zend\Authentication\AuthenticationServiceInterface;

/**
 * Class AuthenticationRepository
 * @package EyeChart\Repository\Authentication
 */
class AuthenticationRepository
{

    /** @var AuthenticateModel */
    private $authenticateModel;

    /** @var AuthenticateStorageModel */
    private $authenticateStorageModel;

    /** @var EncryptionModel */
    private $encryptionModel;

    /** @var AuthenticateAdapter */
    private $authenticateAdapter;

    /** @var ZendAuthentication|AuthenticationServiceInterface  */
    private $zendAuthentication;

    /** @var EmployeeModel */
    private $employeeModel;

    /**
     * AuthenticationRepository constructor
     *
     * @param AuthenticateModel $authenticateModel
     * @param AuthenticateStorageModel $authenticateStorageModel
     * @param EncryptionModel $encryptionModel
     * @param AuthenticateAdapter $authenticateAdapter
     * @param EmployeeModel $employeeModel
     * @param AuthenticationServiceInterface|ZendAuthentication $zendAuthentication
     */
    public function __construct(
        AuthenticateModel $authenticateModel,
        AuthenticateStorageModel $authenticateStorageModel,
        EncryptionModel $encryptionModel,
        AuthenticateAdapter $authenticateAdapter,
        EmployeeModel $employeeModel,
        AuthenticationServiceInterface $zendAuthentication
    ) {
        $this->authenticateModel        = $authenticateModel;
        $this->authenticateStorageModel = $authenticateStorageModel;
        $this->encryptionModel          = $encryptionModel;
        $this->authenticateAdapter      = $authenticateAdapter;
        $this->zendAuthentication       = $zendAuthentication;
        $this->employeeModel            = $employeeModel;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->authenticateStorageModel->isEmpty();
    }

    /**
     * @return \mixed[]
     */
    public function read(): array
    {
        return $this->authenticateStorageModel->read();
    }

    /**
     * @param SessionEntity[] $storage
     * @return bool
     */
    public function write($storage): bool
    {
        return $this->authenticateStorageModel->write($storage);
    }

    public function clear(): void
    {
        $this->authenticateStorageModel->clear();
    }

    /**
     * @param VOInterface $vo
     */
    public function prune(VOInterface $vo): void
    {
        $message = AuthenticateMapper::SESSION_ENDED_MESSAGE;
        if ($this->authenticateStorageModel->clearSessionRecord($vo) === false) {
            $message = AuthenticateMapper::SESSION_EXPIRED_MESSAGE;
        }

        $this->authenticateModel->addMessage($message);
    }

    /**
     * @param VOInterface $vo
     * @return bool
     */
    public function authenticateUser(VOInterface $vo): bool
    {
        $this->authenticateModel->setTokenToAuthenticate($vo);
        $this->zendAuthentication->setStorage($this->authenticateStorageModel);

        $result = $this->zendAuthentication->authenticate($this->authenticateAdapter);

        if ($result->isValid() === false) {
            $this->logout($vo);
        }

        return $result->isValid();
    }

    /**
     * @param VOInterface $vo
     */
    public function checkSessionStatus(VOInterface $vo): void
    {
        $this->authenticateStorageModel->checkSessionStatus($vo);
    }

    /**
     * @param VOInterface $vo
     * @return string[]
     */
    public function logout(VOInterface $vo): array
    {
        $this->authenticateStorageModel->clearSessionRecord($vo);

        return $this->authenticateModel->getMessages();
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @return string
     * @throws UnauthorizedException
     */
    public function login(VOInterface $authenticationVO): string
    {
        try {
            $results = $this->authenticateModel->getByteCodeAndTag($authenticationVO);
            $authenticationVO->setByteCode($results[AuthenticateMapper::BYTE_CODE]);
            $authenticationVO->setTag($results[AuthenticateMapper::TAG]);

            $this->encryptionModel->setBytes($authenticationVO->getByteCode());
            $this->encryptionModel->setTag($authenticationVO->getTag());
            $credentials = $this->encryptionModel->encrypt($authenticationVO->getPassword(), $authenticationVO->getUsername());
            $authenticationVO->setCredentials($credentials);

            $this->authenticateModel->checkCredentials($authenticationVO);
        } catch (NoResultsFoundException $exception) {
            throw new UnauthorizedException('You are not authorized to access this application');
        } catch (UnableToAuthenticateException $exception) {
            throw new UnauthorizedException($exception->getMessage(), $exception->getCode());
        }

        $sessionEntity = $this->authenticateModel->generateSessionEntity($authenticationVO);

        $this->authenticateStorageModel->write([ $sessionEntity ]);
        $this->zendAuthentication->setStorage($this->authenticateStorageModel);

        return $sessionEntity->getToken();
    }

    /**
     * @param VOInterface|TokenVO $tokenVO
     * @return array[]
     */
    public function getUserSessionStatus(VOInterface $tokenVO): array
    {
        $sessionStatus = $this->authenticateStorageModel->getUserSessionStatus($tokenVO);

        if ($sessionStatus[SessionMapper::EXPIRED] === true) {
            $authenticationVO = AuthenticationVO::build()->setToken($tokenVO->getToken());
            $this->authenticateStorageModel->clearSessionRecord($authenticationVO);
        }

        return $sessionStatus;
    }
}
