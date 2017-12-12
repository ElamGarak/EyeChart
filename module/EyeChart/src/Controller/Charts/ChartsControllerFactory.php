<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Controller\Charts;

use EyeChart\Entity\AuthenticateEntity;
use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;

/**
 * Class ChartsControllerFactory
 * @package EyeChart\Controller\Charts
 */
class ChartsControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @return ChartsController
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): ChartsController
    {
        $authenticateEntity = $container->get(AuthenticateEntity::class);
        $commandBus         = $container->get(CommandBus::class);

        return new ChartsController($authenticateEntity, $commandBus);
    }
}
