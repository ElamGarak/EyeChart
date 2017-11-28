<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Model\Email;

use EyeChart\Entity\Email\EmailEntity;
use Zend\Config\Config;
use Psr\Container\ContainerInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\SmtpOptions;

/**
 * Class EmailModelFactory
 * @package EmployeeSelfSign\Model\Email
 * @codeCoverageIgnore
 */
final class EmailModelFactory
{

    /**
     * Create and return EmailModel
     *
     * @param ContainerInterface $container
     *
     * @return EmailModel
     */
    public function __invoke(ContainerInterface $container): EmailModel
    {
        $emailEntity = $container->get(EmailEntity::class);

        $message     = new Message();
        $smtpOptions = new SmtpOptions();
        $config      = new Config($container->get('config'));

        $environments  = $config->get('environments');
        $emailSettings = $config->emailModel->toArray();

        return new EmailModel($emailEntity, $message, $smtpOptions, $environments, $emailSettings);
    }
}
