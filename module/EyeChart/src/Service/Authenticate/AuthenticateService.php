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
class AuthenticateService
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
     * @param VOInterface $vo
     * @return bool
     */
    public function authenticateUser(VOInterface $vo): bool
    {
        return $this->authenticationRepository->authenticateUser($vo);
    }

    /**
     * @param VOInterface $vo
     */
    public function checkSessionStatus(VOInterface $vo): void
    {
        $this->authenticationRepository->checkSessionStatus($vo);
    }

    /**
     * @param VOInterface $authenticationVO
     * @return string
     */
    public function login(VOInterface $authenticationVO): string
    {
        return $this->authenticationRepository->login($authenticationVO);
    }

    /**
     * @param VOInterface $vo
     * @return string[]
     */
    public function logout(VOInterface $vo): array
    {
        return $this->authenticationRepository->logout($vo);
    }
}
