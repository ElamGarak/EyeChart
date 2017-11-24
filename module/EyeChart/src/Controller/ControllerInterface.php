<?php
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/24/2017
 * (c) 2017
 */

namespace EyeChart\Controller;

use EyeChart\Entity\AuthenticateEntity;
use Zend\Http\Request;
use Zend\Stdlib\RequestInterface;

/**
 * Interface ControllerInterface
 * @package EyeChart\Controller
 */
interface ControllerInterface
{

    public function authenticate(): void;

    /** @return AuthenticateEntity */
    public function getAuthenticateEntity(): AuthenticateEntity;

    /** @return RequestInterface|Request */
    public function getRequest(): Request;

    /** @return mixed[] */
    public function extractPosts(): array;

    /** @return mixed[] */
    public function extractHeaders(): array;

    /**
     * @param string $key
     * @return mixed
     */
    public function getPostValue(string $key);

    /**
     * @param string $key
     * @return mixed
     */
    public function getHeaderValue(string $key);

    /** @return string */
    public function getMatchedRouteName(): string;
}
