<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/3/2017
 * (c) 2017
 */

namespace EyeChart\Model\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\SettingNotFoundException;
use EyeChart\Mappers\SessionMapper;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\TokenVO;
use EyeChart\VO\VOInterface;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Config\Config;

/**
 * Class AuthenticateStorageModel
 * @package EyeChart\Model\Authenticate
 */
class AuthenticateStorageModel implements StorageInterface
{
    /** @var AuthenticateStorageDAO */
    private $authenticateStorageDao;

    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /** @var SessionEntity|EntityInterface */
    private $sessionEntity;

    /** @var Config */
    private $environment;

    /**
     * AuthenticateStorageModel constructor.
     * @param StorageInterface|AuthenticateStorageDAO $authenticateStorageDAO
     * @param EntityInterface|AuthenticateEntity $authenticateEntity
     * @param EntityInterface|SessionEntity $sessionEntity
     * @param Config $environment
     */
    public function __construct(
        StorageInterface $authenticateStorageDAO,
        EntityInterface $authenticateEntity,
        EntityInterface $sessionEntity,
        Config $environment
    ) {
        $this->authenticateStorageDao = $authenticateStorageDAO;
        $this->authenticateEntity     = $authenticateEntity;
        $this->sessionEntity          = $sessionEntity;
        $this->environment            = $environment;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function isEmpty(): bool
    {
        return $this->authenticateStorageDao->isEmpty();
    }

    /**
     * @return \mixed[]
     * @codeCoverageIgnore
     */
    public function read(): array
    {
        return $this->authenticateStorageDao->read();
    }

    /**
     * @param SessionEntity[] $storage
     * @return bool
     * @codeCoverageIgnore
     */
    public function write($storage): bool
    {
        return $this->authenticateStorageDao->write($storage);
    }

    /**
     * @codeCoverageIgnore
     */
    public function clear(): void
    {
        $this->authenticateStorageDao->clear();
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @return bool
     * @codeCoverageIgnore
     */
    public function clearSessionRecord(VOInterface $authenticationVO): bool
    {
        return $this->authenticateStorageDao->clearSessionRecord($authenticationVO);
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVo
     */
    public function checkSessionStatus(VOInterface $authenticationVo): void
    {
        $this->sessionEntity->setToken($authenticationVo->getToken());
        $record = $this->authenticateStorageDao->read();

        if ($this->hasTokenExpired($record) === true) {
            $this->clearSessionRecord($authenticationVo);
            $this->authenticateEntity->setIsValid(false);

            return;
        }

        $this->refresh($record[SessionMapper::TOKEN]);
    }

    /**
     * @param string $token
     * @codeCoverageIgnore
     */
    public function refresh(string $token): void
    {
        $this->authenticateStorageDao->refresh($token);
    }

    /**
     * @param VOInterface|TokenVO $tokenVO
     * @return array
     */
    public function getUserSessionStatus(VOInterface $tokenVO): array
    {
        $this->sessionEntity->setToken($tokenVO->getToken());

        $sessionRecord = $this->authenticateStorageDao->read();

        return [
            SessionMapper::SYS_TIME     => time(),
            SessionMapper::REMAINING    => $this->getExpirationTime($sessionRecord),
            SessionMapper::EXPIRED      => $this->hasTokenExpired($sessionRecord),
            SessionMapper::THRESHOLD    => $this->getSessionTimeoutThreshold(),
            SessionMapper::ACTIVE_CHECK => $this->activeSessionCheck()
        ];
    }

    /**
     * @param array $sessionRecord
     * @return int
     */
    private function getExpirationTime(array $sessionRecord): int
    {
        return max(($sessionRecord[SessionMapper::ACCESSED] + $this->sessionEntity->getLifetime()) - time(), 0);
    }

    /**
     * @param mixed[] $sessionRecord
     * @return bool
     */
    private function hasTokenExpired(array $sessionRecord): bool
    {
        $remainingSeconds = ($sessionRecord[SessionMapper::ACCESSED] + $this->sessionEntity->getLifetime()) - time();

        return ($remainingSeconds <= 0);
    }

    private function activeSessionCheck(): bool
    {
        if (! $this->environment->get('activeSessionCheck')) {
            throw new SettingNotFoundException("Key 'activeSessionCheck' was not found, check config");
        }

        return $this->environment->get('activeSessionCheck');
    }

    /**
     * @return int
     * @throws SettingNotFoundException
     */
    private function getSessionTimeoutThreshold(): int
    {
        if (! $this->environment->get('timeoutWarningThreshold')) {
            throw new SettingNotFoundException("Key 'timeoutWarningThreshold' was not found, check config");
        }

        return $this->environment->get('timeoutWarningThreshold');
    }
}
