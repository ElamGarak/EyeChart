<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Authenticate;

use EyeChart\VO\LoginVO;
use EyeChart\VO\VOInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

/**
 * Class AuthenticateDAO
 * @package EyeChart\DAL\DAO\Authenticate
 */
class AuthenticateDAO
{
    /** @var Sql */
    private $sql;

    /**
     * AuthenticateDAO constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->sql = new Sql($adapter);
    }

    /**
     * TODO
     * @param LoginVO|VOInterface $vo
     * @return bool
     */
    public function checkCredentials(VOInterface $vo): bool
    {
        return false;
    }

    /**
     * TODO
     * @param string $userId
     * @return bool
     */
    public function isUserValid(string $userId): bool
    {
        return false;
    }
}
