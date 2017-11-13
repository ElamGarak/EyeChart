<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */


namespace EyeChart\Service\Email;

use EyeChart\Model\Email\EmailModel;
use Psr\Container\ContainerInterface;

/**
 * Class EmailServiceFactory
 * @package EmployeeSelfSign\Service\Email
 */
final class EmailServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return EmailService
     */
    public function __invoke(ContainerInterface $container): EmailService
    {
        return new EmailService($container->get(EmailModel::class));
    }
}
