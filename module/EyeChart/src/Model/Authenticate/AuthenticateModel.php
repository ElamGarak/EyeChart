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
use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\UnableToAuthenticateException;
use EyeChart\VO\AuthenticationVO;
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
     * @param VOInterface $authenticationVO
     * @throws UnableToAuthenticateException
     */
    public function checkCredentials(VOInterface $authenticationVO): void
    {
        if ($this->authenticateDAO->checkCredentials($authenticationVO) === false) {
            throw new UnableToAuthenticateException($authenticationVO);
        };

        $this->authenticateEntity
             ->setIsValid(true)
             ->initializeByVO($authenticationVO);
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @return SessionEntity
     */
    public function generateSessionEntity(VOInterface $authenticationVO): SessionEntity
    {
        return $this->sessionEntity->setToken(Uuid::uuid1()->toString())
            ->setSessionUser($authenticationVO->getUsername())
            ->setLastActive(time());
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->authenticateEntity->getToken();
    }
}
