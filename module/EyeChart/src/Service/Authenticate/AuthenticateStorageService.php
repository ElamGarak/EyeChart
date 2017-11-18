<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Service\Authenticate;

use EyeChart\Repository\Authentication\AuthenticationRepository;
use EyeChart\VO\TokenVO;
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
     * @param mixed[] $storage
     * @return bool
     */
    public function write($storage): bool
    {
        return $this->authenticationRepository->write($storage);
    }

    public function clear(): void
    {
        $this->authenticationRepository->clear();
    }

    /**
     * @param VOInterface $vo
     * @return bool
     */
    public function prune(VOInterface $vo): bool
    {
        return $this->authenticationRepository->prune($vo);
    }

    /**
     * @return mixed[]
     */
    public function getEmployeeInformation()
    {
        return $this->authenticationRepository->getEmployeeInformation();
    }

    /**
     * @param VOInterface|TokenVO $tokenVO
     * @return array
     */
    public function getTokenSession(TokenVO $tokenVO): array
    {
        return $this->authenticationRepository->getTokenSession($tokenVO);
    }
}
