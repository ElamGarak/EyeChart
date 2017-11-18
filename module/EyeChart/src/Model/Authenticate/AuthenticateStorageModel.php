<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/3/2017
 * (c) 2017
 */

namespace EyeChart\Model\Authenticate;

use Assert\Assertion;
use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\SettingNotFoundException;
use EyeChart\Mappers\SessionMapper;
use EyeChart\VO\AuthenticationVO;
use EyeChart\VO\TokenVO;
use EyeChart\VO\VOInterface;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Config\Config;

/**
 * Class AuthenticateStorageModel
 * @package EyeChart\Model\Authenticate
 */
final class AuthenticateStorageModel implements StorageInterface
{
    /** @var AuthenticateStorageDAO */
    private $authenticateStorageDao;

    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /** @var SessionEntity|EntityInterface */
    private $sessionEntity;

    /** @var Config */
    private $environments;

    /**
     * AuthenticateStorageModel constructor.
     * @param StorageInterface|AuthenticateStorageDAO $authenticateStorageDAO
     * @param EntityInterface|AuthenticateEntity $authenticateEntity
     * @param EntityInterface|SessionEntity $sessionEntity
     * @param Config $environments
     */
    public function __construct(
        StorageInterface $authenticateStorageDAO,
        EntityInterface $authenticateEntity,
        EntityInterface $sessionEntity,
        Config $environments
    ) {
        $this->authenticateStorageDao = $authenticateStorageDAO;
        $this->authenticateEntity     = $authenticateEntity;
        $this->sessionEntity          = $sessionEntity;
        $this->environments           = $environments;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->authenticateStorageDao->isEmpty();
    }

    /**
     * @return \mixed[]
     */
    public function read(): array
    {
        return $this->authenticateStorageDao->read();
    }

    /**
     * @param SessionEntity[] $storage
     * @return bool
     */
    public function write($storage): bool
    {
        return $this->authenticateStorageDao->write($storage);
    }

    public function clear(): void
    {
        $this->authenticateStorageDao->clear();
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @return bool
     */
    public function clearSessionRecord(VOInterface $authenticationVO): bool
    {
        return $this->authenticateStorageDao->clearSessionRecord($authenticationVO);
    }

    /**
     * @return AuthenticateEntity
     */
    public function getAuthenticateEntity(): AuthenticateEntity
    {
        return $this->authenticateEntity;
    }

    /**
     * @return mixed[]
     */
    public function getEmployeeInformation(): array
    {
        $storageData = $this->authenticateStorageDao->getEmployeeInformation();

        Assertion::keyExists($storageData, $this->authenticateEntity->getToken(), 'Token was not found.');

        return $storageData[$this->authenticateEntity->getToken()];
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
     */
    public function refresh(string $token): void
    {
        $this->authenticateStorageDao->refresh($token);
    }

    /**
     * @param TokenVO $tokenVO
     * @return array
     */
    public function getUserSessionByToken(TokenVO $tokenVO): array
    {
        $userStorage = $this->authenticateStorageDao->getUserStorage();

        if (empty($userStorage)) {
            return [];
        }

        if (! array_key_exists($tokenVO->getToken(), $userStorage)) {
            return [];
        }

        $this->authenticateEntity->setToken($tokenVO->getToken());
        $employeeInformation = $this->getEmployeeInformation();

        $expirationTime = $employeeInformation[SessionMapper::MODIFIED] + $this->authenticateStorageDao
                                                                               ->getSessionLifeTime();

        return [
            SessionMapper::SYS_TIME  => time(),
            SessionMapper::MODIFIED    => $employeeInformation[SessionMapper::MODIFIED],
            SessionMapper::REMAINING   => max($expirationTime - time(), 0),
            SessionMapper::EXPIRED     => $this->hasTokenExpired($employeeInformation),
            SessionMapper::THRESHOLD   => $this->getSessionTimeoutThreshold(),
            SessionMapper::ACTIVE_CHECK => $this->activeSessionCheck()
        ];
    }

    /**
     * @param mixed[] $sessionRecord
     * @return bool
     */
    private function hasTokenExpired(array $sessionRecord): bool
    {
        $remainingSeconds = ($sessionRecord[SessionMapper::ACCESSED] +
                             $this->authenticateStorageDao->getSessionLifeTime()) - time();

        return ($remainingSeconds <= 0);
    }

    private function activeSessionCheck(): bool
    {
        $systemEnvironment = $this->getSystemEnvironment();

        if (! $systemEnvironment->get('activeSessionCheck')) {
            throw new SettingNotFoundException("Key 'activeSessionCheck' was not found, check config");
        }

        return $systemEnvironment->get('timeoutWarningThreshold');
    }

    /**
     * @return int
     * @throws SettingNotFoundException
     */
    private function getSessionTimeoutThreshold(): int
    {
        $systemEnvironment = $this->getSystemEnvironment();

        if (! $systemEnvironment->get('timeoutWarningThreshold')) {
            throw new SettingNotFoundException("Key 'timeoutWarningThreshold' was not found, check config");
        }

        return $systemEnvironment->get('timeoutWarningThreshold');
    }

    /**
     * @return Config
     * @throws SettingNotFoundException
     */
    private function getSystemEnvironment(): Config
    {
        $system = gethostname();

        $systems = $this->environments->get('systems')->toArray();

        if (! array_key_exists($system, $systems)) {
            throw new SettingNotFoundException("System {$system} was not found, check config");
        }

        return $this->environments[$systems[$system]];
    }
}
