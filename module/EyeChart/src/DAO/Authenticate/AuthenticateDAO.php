<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Authenticate;

use EyeChart\DAO\AbstractDAO;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\AuthenticationVO;
use EyeChart\VO\VOInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateDAO
 * @package EyeChart\DAL\DAO\Authenticate
 */
class AuthenticateDAO extends AbstractDAO
{
    /**
     * @param AuthenticationVO|VOInterface $vo
     * @return bool
     */
    public function checkCredentials(VOInterface $vo): bool
    {
        $select = parent::getSqlAdapter()->select();

        $select->columns([
            AuthenticateMapper::USER_NAME,
        ])->from(AuthenticateMapper::TABLE);

        $where = new Where();
        $where->equalTo(AuthenticateMapper::USER_NAME, $vo->getUsername())->and
              ->equalTo(AuthenticateMapper::PASSWORD, $vo->getPassword());

        $select->where($where);

        $result = parent::getResultSingleResult($select, ResultSet::TYPE_ARRAY);

        return (! is_null($result));
    }
}
