<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */
namespace EyeChart\Controller\Index;

use EyeChart\Entity\AuthenticateEntity;
use Psr\Container\ContainerInterface;
use League\Tactician\CommandBus;

/**
 * Class IndexControllerFactory
 * @package EyeChart\Controller\Index
 */
class IndexControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @return IndexController
     */
    public function __invoke(ContainerInterface $container): IndexController
    {
        $authenticateEntity = $container->get(AuthenticateEntity::class);
        $commandBus         = $container->get(CommandBus::class);

        return new IndexController($authenticateEntity, $commandBus);
    }
}
