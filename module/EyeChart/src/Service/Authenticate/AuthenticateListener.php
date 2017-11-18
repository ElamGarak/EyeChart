<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Service\Authenticate;

use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\AuthenticationVO;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Config\Config;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use EyeChart\Exception\UnauthorizedException;

/**
 * Class AuthenticateListener
 * @package EyeChart\Service\Authenticate
 */
final class AuthenticateListener implements ListenerAggregateInterface
{

    /**
     * @var callable[]
     */
    private $listeners = [];

    /**
     * @var AuthenticateService
     */
    private $authenticateService;

    /**
     *  @var AuthenticateEntity
     */
    private $authenticateEntity;

    /**
     * @var Config
     */
    private $config;

    /**
     * Constructor
     *
     * @param AuthenticateService $authenticateService
     * @param AuthenticateEntity  $authenticateEntity
     * @param Config              $config
     */
    public function __construct(
        AuthenticateService $authenticateService,
        AuthenticateEntity  $authenticateEntity,
        Config              $config
    ) {
        $this->authenticateService = $authenticateService;
        $this->authenticateEntity  = $authenticateEntity;
        $this->config              = $config;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'checkAuthentication']);
    }

    /**
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events): void
    {
        foreach ($this->listeners as $index => $callback) {
            $events->detach($callback);
            unset($this->listeners[$index]);
        }
    }

    /**
     * @param MvcEvent $mvcEvent
     * @throws UnauthorizedException
     */
    public function checkAuthentication(MvcEvent $mvcEvent): void
    {
        /** @var Request $request */
        $request = $mvcEvent->getRequest();
        $params  = $mvcEvent->getRouteMatch()->getParams();

        $post    = $request->getPost()->toArray();
        $headers = $request->getHeaders()->toArray();

        // check for authentication token within the header
        if (array_key_exists(AuthenticateMapper::TOKEN, $post)) {
            $headers = array_merge(
                $headers,
                [AuthenticateMapper::HEADER => $post[AuthenticateMapper::TOKEN]]
            );
        }

        $tokenRequired = false;

        // set default content-type if non set
        if (! isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'text/html';

            // log all empty header until all are captured
            error_log('Matched Route Name: ' . $mvcEvent->getRouteMatch()->getMatchedRouteName());
        }

        $routeName = $mvcEvent->getRouteMatch()->getMatchedRouteName();

        // workaround for apigility
        if (substr($routeName, 0, 13) === 'zf-apigility/') {
            return;
        }

        switch ($headers['Content-Type']) {
            case 'application/json':
                $noTokenRequiredConfig = $this->config->get('noTokenRequired')->toArray();
                if (array_key_exists($routeName, $noTokenRequiredConfig)) {
                    return;
                }
                $tokenRequired = true;
                break;
            case 'text/html':
            case 'application/x-www-form-urlencoded':
                $tokenRequired = $this->config->get('router')
                                              ->get('routes')
                                              ->get($routeName)
                                              ->get('options')
                                              ->get('tokenRequired');
                break;
            case ('multipart/form-data' === substr($headers['Content-Type'], 0, 19)):
                $noTokenRequiredConfig = $this->config->get('noTokenRequired')->toArray();
                if (array_key_exists($routeName, $noTokenRequiredConfig)) {
                    return;
                }
                $tokenRequired = true;
                break;
            default:
                error_log('unknown call type:' . $headers['Content-Type']);
        }

        if (! $tokenRequired) {
            return;
        }

        // if token exist as url parameter but not in the header
        if (! array_key_exists(AuthenticateMapper::HEADER, $headers) &&
            array_key_exists('authenticate_token', $params)
        ) {
            $headers[AuthenticateMapper::HEADER] = $params['authenticate_token'];
        }

        if (array_key_exists(AuthenticateMapper::HEADER, $headers)) {
            $authenticationVO = AuthenticationVO::build()->setToken($headers[AuthenticateMapper::HEADER]);

            $this->authenticateEntity->setIsValid($this->authenticateService->authenticateUser($authenticationVO));

            $this->authenticateService->checkSessionStatus($authenticationVO);

            if ($this->authenticateEntity->getIsValid() === true) {
                return;
            }

            // Request is not valid but also not an api call
            if ($headers['Content-Type'] === 'application/x-www-form-urlencoded') {
                // Use a standard redirect will be needed
                throw new UnauthorizedException();
            }
        }

        throw new UnauthorizedException();
    }
}
