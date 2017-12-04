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
use EyeChart\Exception\UserCredentialsDoNotMatchException;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\VOInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateDAO
 * @package EyeChart\DAO\Authenticate
 */
class AuthenticateDAO extends AbstractDAO
{
    /**
     * @param AuthenticationVO|VOInterface $vo
     * @throws UserCredentialsDoNotMatchException
     */
    public function checkCredentials(VOInterface $vo)
    {
        $select = parent::getSqlAdapter()->select();

        $select->columns([
            AuthenticateMapper::USER_NAME,
        ])->from(AuthenticateMapper::TABLE);

        $where = new Where();
        $where->equalTo(AuthenticateMapper::USER_NAME, $vo->getUsername())->and
              ->equalTo(AuthenticateMapper::CREDENTIALS, $vo->getDerivedCredentials()->getCredentials())->and
              ->equalTo(AuthenticateMapper::IS_ACTIVE, true);

        $select->where($where);

        $result = parent::getResultSingleResult($select, ResultSet::TYPE_ARRAY);

        if (is_null($result)) {
            throw new UserCredentialsDoNotMatchException();
        }
    }

    /**
     * @param AuthenticationVO|VOInterface $vo
     * @return bool
     */
    public function isUserActive(VOInterface $vo): bool
    {
        $select = parent::getSqlAdapter()->select();

        $select->columns([
            AuthenticateMapper::IS_ACTIVE,
        ])->from(AuthenticateMapper::TABLE);

        $where = new Where();
        $where->equalTo(AuthenticateMapper::USER_NAME, $vo->getUsername());

        $select->where($where);

        $result = parent::getResultSingleResult($select, ResultSet::TYPE_ARRAY);

        $results = $this->parseDataTypes($result);

        return (bool) $results[AuthenticateMapper::IS_ACTIVE];
    }

    /**
     * @param VOInterface|AuthenticationVO $vo
     * @return mixed[]
     */
    public function getUsersStoredCredentials(VOInterface $vo): array
    {
        $select = parent::getSqlAdapter()->select();

        $select->columns([
            AuthenticateMapper::CREDENTIALS,
        ])->from(AuthenticateMapper::TABLE);

        $where = new Where();
        $where->equalTo(AuthenticateMapper::USER_NAME, $vo->getUsername());

        $select->where($where);

        return parent::getResultSingleResult($select, ResultSet::TYPE_ARRAY);
    }
}
