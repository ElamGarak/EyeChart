<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/30/2017
 * (c) Eye Chart
 */

namespace EyeChart\Model\Authenticate;

use Psr\Container\ContainerInterface;
use Zend\Config\Config;

/**
 * Class EncryptionModelFactory
 * @package EyeChart\Model\Authenticate
 */
final class EncryptionModelFactory
{
    /**
     * @param ContainerInterface $container
     * @return EncryptionModel
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): EncryptionModel
    {
        $config = new Config($container->get('Config'));

        return new EncryptionModel(
            $config->get('authentication')
        );
    }
}
