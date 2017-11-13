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
use EyeChart\VO\VOInterface;
use Zend\Authentication\AuthenticationService as ZendAuthentication;

/**
 * Class AuthenticateService
 * @package EyeChart\Service\Authenticate
 */
final class AuthenticateService
{
    /** @var AuthenticationRepository */
    private $authenticationRepository;

    /** @var ZendAuthentication */
    private $zendAuthentication;

    /**
     * AuthenticateService constructor.
     *
     * @param AuthenticationRepository $authenticationRepository
     * @param ZendAuthentication $zendAuthentication
     */
    public function __construct(
        AuthenticationRepository $authenticationRepository,
        ZendAuthentication  $zendAuthentication
    ) {
        $this->authenticationRepository = $authenticationRepository;
        $this->zendAuthentication       = $zendAuthentication;
    }

    /**
     * @return mixed[]
     */
    public function getUserData(): array
    {
        return $this->authenticationRepository->getEmployeeInformation();
    }

    /**
     * @return bool
     */
    public function authenticateUser(): bool
    {
        return $this->authenticationRepository->authenticateUser();
    }

    public function checkSessionStatus(): void
    {
        $this->authenticationRepository->checkSessionStatus();
    }

    /**
     * @param VOInterface $loginVO
     * @return string
     */
    public function login(VOInterface $loginVO): string
    {
        $this->authenticationRepository->login($loginVO);

        return $this->authenticationRepository->getToken();
    }

    public function logout(): void
    {
        $this->authenticationRepository->logout();
    }
}
