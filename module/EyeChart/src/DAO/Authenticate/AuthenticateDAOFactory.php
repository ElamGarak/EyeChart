<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Authenticate;

use Psr\Container\ContainerInterface;
use Zend\Db\Sql\Sql;

/**
 * Class AuthenticateDAOFactory
 * @package EyeChart\DAL\DAO\Authenticate
 */
final class AuthenticateDAOFactory
{
    /**
     * @param ContainerInterface $container
     * @return AuthenticateDAO
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container): AuthenticateDAO
    {
        /** @var Sql $sql */
        $sql = new Sql($container->get('db'));

        return new AuthenticateDAO($sql);
    }
}
