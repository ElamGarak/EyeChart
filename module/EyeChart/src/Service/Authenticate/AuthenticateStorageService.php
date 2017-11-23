<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Service\Authenticate;

use EyeChart\Entity\SessionEntity;
use EyeChart\Repository\Authentication\AuthenticationRepository;
use EyeChart\VO\VOInterface;
use Zend\Authentication\Storage\StorageInterface;

/**
 * Class AuthenticateStorageService
 * @package EyeChart\Service\Authenticate
 */
final class AuthenticateStorageService implements StorageInterface
{
    /** @var AuthenticationRepository  */
    private $authenticationRepository;

    /**
     * AuthenticateStorageService constructor.
     * @param AuthenticationRepository $authenticationRepository
     */
    public function __construct(AuthenticationRepository $authenticationRepository)
    {
        $this->authenticationRepository = $authenticationRepository;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->authenticationRepository->isEmpty();
    }

    /**
     * @return \mixed[]
     */
    public function read(): array
    {
        return $this->authenticationRepository->read();
    }

    /**
     * @param SessionEntity[] $sessionEntity
     * @return bool
     */
    public function write($sessionEntity): bool
    {
        return $this->authenticationRepository->write($sessionEntity);
    }

    public function clear(): void
    {
        $this->authenticationRepository->clear();
    }

    /**
     * @param VOInterface $tokenVO
     * @return array
     */
    public function getUserSessionByToken(VOInterface $tokenVO): array
    {
        return $this->authenticationRepository->getUserSessionStatus($tokenVO);
    }
}
