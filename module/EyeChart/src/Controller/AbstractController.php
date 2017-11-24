<?php
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/24/2017
 * (c) 2017
 */

namespace EyeChart\Controller;

use Exception;
use EyeChart\Command\Commands\AuthenticateCommand;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Exception\InvalidHeaderRequestException;
use EyeChart\Exception\InvalidPostRequestException;
use HttpInvalidParamException;
use League\Tactician\CommandBus;
use OutOfBoundsException;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\RequestInterface;

/**
 * Class AbstractController
 * @package EyeChart\Controller
 */
class AbstractController extends AbstractActionController implements ControllerInterface
{
    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /** @var CommandBus */
    private $commandBus;

    /**
     * Constructor
     *
     * @param AuthenticateEntity $authenticateEntity
     * @param CommandBus         $commandBus
     */
    public function __construct(AuthenticateEntity $authenticateEntity, CommandBus $commandBus)
    {
        $this->authenticateEntity = $authenticateEntity;
        $this->commandBus         = $commandBus;
    }

    public function authenticate(): void
    {
        try {
            $this->commandBus->handle(
                new AuthenticateCommand($this->getEvent())
            );
        } catch (Exception $e) {
            $this->redirect()->toRoute('login');
        }
    }

    /**
     * @return AuthenticateEntity
     */
    public function getAuthenticateEntity(): AuthenticateEntity
    {
        return $this->authenticateEntity;
    }

    /**
     * @return RequestInterface|Request
     */
    public function getRequest(): Request
    {
        return $this->getEvent()->getRequest();
    }

    /**
     * @return mixed[]
     */
    public function extractPosts(): array
    {
        return $this->getRequest()->getPost()->toArray();
    }

    /**
     * @return mixed[]
     */
    public function extractHeaders(): array
    {
        return $this->getRequest()->getHeaders()->toArray();
    }

    /**
     * @return string
     */
    public function getMatchedRouteName(): string
    {
        return $this->getEvent()->getRouteMatch()->getMatchedRouteName();
    }

    /**
     * @param string $key
     * @return mixed
     * @throws HttpInvalidParamException
     */
    public function getPostValue(string $key) {
        try {
            return $this->extractPosts()[$key];
        } catch (OutOfBoundsException $exception) {
            throw new InvalidPostRequestException($key);
        }
    }

    /**
     * @param string $key
     * @return mixed
     * @throws InvalidHeaderRequestException
     */
    public function getHeaderValue(string $key) {
        try {
            return $this->extractHeaders()[$key];
        } catch (OutOfBoundsException $exception) {
            throw new InvalidHeaderRequestException($key);
        }
    }
}
