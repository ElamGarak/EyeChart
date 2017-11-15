<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/4/2017
 * (c) 2017
 */

namespace EyeChart\Model\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\EntityInterface;
use EyeChart\Exception\UnableToAuthenticateException;
use EyeChart\Mappers\SessionMapper;
use EyeChart\VO\VOInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class AuthenticateModel
 * @package EyeChart\Model\Authenticate
 */
final class AuthenticateModel
{
    /** @var AuthenticateDAO */
    private $authenticateDAO;

    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /**
     * AuthenticateModel constructor.
     * @param AuthenticateDAO $authenticateDAO
     * @param EntityInterface|AuthenticateEntity $authenticateEntity
     */
    public function __construct(AuthenticateDAO $authenticateDAO, EntityInterface $authenticateEntity)
    {
        $this->authenticateDAO    = $authenticateDAO;
        $this->authenticateEntity = $authenticateEntity;
    }

    /**
     * @param VOInterface $authenticationVO
     * @throws UnableToAuthenticateException
     */
    public function authenticateUser(VOInterface $authenticationVO): void
    {
        if ($this->authenticateDAO->checkCredentials($authenticationVO) === false) {
            throw new UnableToAuthenticateException($authenticationVO);
        };

        $this->authenticateEntity->initializeByVO($authenticationVO);
    }

    /**
     * @param EntityInterface $employeeEntity
     * @return array[]
     */
    public function assembleStorageRecord(EntityInterface $employeeEntity): array
    {
        $this->authenticateEntity->setToken(Uuid::uuid1()->toString());

        $storageRecord = [
            $this->authenticateEntity->getToken() => [
                SessionMapper::MODIFIED => time(),
            ]
        ];

        $storageRecord[$this->authenticateEntity->getToken()] = array_merge(
            $storageRecord[$this->authenticateEntity->getToken()],
            $this->authenticateEntity->toArray(),
            $employeeEntity->toArray()
        );

        return $storageRecord;
    }
}
