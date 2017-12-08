<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Handlers\Email;

use EyeChart\Model\Email\EmailModel;
use Psr\Container\ContainerInterface;

/**
 * Class EmailHandlerFactory
 * @package EyeChart\Command\Handlers\Email
 */
final class EmailHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return EmailHandler
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): EmailHandler
    {
        $model = $container->get(EmailModel::class);

        return new EmailHandler($model);
    }
}
