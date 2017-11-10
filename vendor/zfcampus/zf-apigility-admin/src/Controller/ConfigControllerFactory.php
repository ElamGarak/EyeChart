<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\Apigility\Admin\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\Configuration\ConfigResource;

class ConfigControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ConfigController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConfigController($container->get(ConfigResource::class));
    }

    /**
     * @param ServiceLocatorInterface $container
     * @return ConfigController
     */
    public function createService(ServiceLocatorInterface $container)
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator() ?: $container;
        }
        return $this($container, ConfigController::class);
    }
}
