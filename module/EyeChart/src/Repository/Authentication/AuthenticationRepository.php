<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/4/2017
 * (c) 2017
 */

namespace EyeChart\Repository\Authentication;

use EmployeeModel;
use EyeChart\Model\Authenticate\AuthenticateModel;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;
use EyeChart\Service\Authenticate\AuthenticateAdapter;
use EyeChart\VO\TokenVO;
use EyeChart\VO\VOInterface;
use Zend\Authentication\AuthenticationService as ZendAuthentication;
use Zend\Authentication\AuthenticationServiceInterface;

/**
 * Class AuthenticationRepository
 * @package EyeChart\Repository\Authentication
 */
final class AuthenticationRepository
{

    /** @var AuthenticateModel */
    private $authenticateModel;

    /** @var AuthenticateStorageModel */
    private $authenticateStorageModel;

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
     * @param AuthenticateAdapter $authenticateAdapter
     * @param EmployeeModel $employeeModel
     * @param AuthenticationServiceInterface|ZendAuthentication $zendAuthentication
     */
    public function __construct(
        AuthenticateModel $authenticateModel,
        AuthenticateStorageModel $authenticateStorageModel,
        AuthenticateAdapter $authenticateAdapter,
        EmployeeModel $employeeModel,
        AuthenticationServiceInterface $zendAuthentication
    ) {
        $this->authenticateModel        = $authenticateModel;
        $this->authenticateStorageModel = $authenticateStorageModel;
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
     * @param mixed[] $storage
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
     * @return bool
     */
    public function prune(): bool
    {
        return $this->authenticateStorageModel->prune();
    }

    /**
     * @return mixed[]
     */
    public function getEmployeeInformation(): array
    {
        return $this->authenticateStorageModel->getEmployeeInformation();
    }

    /**
     * @return bool
     */
    public function authenticateUser(): bool
    {
        $this->zendAuthentication->setStorage($this->authenticateStorageModel);
        $result = $this->zendAuthentication->authenticate($this->authenticateAdapter);

        if ($result->isValid() === false) {
            $this->logout();
        }

        return $result->isValid();
    }

    public function checkSessionStatus(): void
    {
        $this->authenticateStorageModel->checkSessionStatus();
    }

    public function logout(): void
    {
        $this->authenticateStorageModel->prune();
    }

    /**
     * @param VOInterface $loginVO
     * @return void
     */
    public function login(VOInterface $loginVO): void
    {
        $this->authenticateModel->authenticateUser($loginVO);

        $employeeEntity = $this->employeeModel->getEmployeeRecordByCredentials($loginVO);
        $storageRecord  = $this->authenticateModel->assembleStorageRecord($employeeEntity);

        $this->authenticateStorageModel->write($storageRecord);
        $this->zendAuthentication->setStorage($this->authenticateStorageModel);
    }

    /**
     * @param TokenVO $tokenVO
     * @return array[]
     */
    public function getUserSessionByToken(TokenVO $tokenVO): array
    {
        return $this->authenticateStorageModel->getUserSessionByToken($tokenVO);
    }
}
