<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\DAO\Authenticate;

use Assert\Assertion;
use Exception;
use EyeChart\DAO\AbstractDAO;
use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\MissingSessionException;
use EyeChart\Mappers\SessionMapper;
use EyeChart\VO\AuthenticationVO;
use EyeChart\VO\VOInterface;
use Zend\Authentication\Exception\ExceptionInterface;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Predicate\Literal;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateStorageDAO
 * @package EyeChart\DAO\Authenticate
 */
final class AuthenticateStorageDAO extends AbstractDAO implements StorageInterface
{
    /** @var Sql */
    private $sql;

    /** @var SessionEntity */
    private $sessionEntity;

    /** @var mixed[]  */
    private $existingStorage = [];

    /**
     * AuthenticateStorageService constructor.
     * @param Adapter $adapter
     * @param SessionEntity $sessionEntity
     */
    public function __construct(Adapter $adapter, SessionEntity $sessionEntity)
    {
        parent::__construct($adapter);

        $this->sql           = new Sql($adapter);
        $this->sessionEntity = $sessionEntity;
    }

    /**
     * Returns true if and only if storage is empty
     *
     * @return bool
     * @deprecated
     */
    public function isEmpty(): bool
    {
        $this->existingStorage = $this->read();

        return empty($this->existingStorage);
    }

    /**
     * Return token record from session table
     *
     * @return mixed[]
     * @throws MissingSessionException
     */
    public function read(): array
    {
        $select = $this->sql->select();

        $select->columns([
            SessionMapper::SESSION_RECORD_ID,
            SessionMapper::PHP_SESSION_ID,
            SessionMapper::SESSION_USER,
            SessionMapper::TOKEN,
            SessionMapper::LIFETIME,
            SessionMapper::ACCESSED
        ]);

        $select->from(SessionMapper::TABLE);

        $where = new Where();

        $where->equalTo(SessionMapper::TOKEN, $this->sessionEntity->getToken());

        $select->where($where);

        $result = parent::getResultSingleResult($select);

        if (empty($result)) {
            throw new MissingSessionException($this->sessionEntity, __METHOD__);
        }

        return $result;
    }

    /**
     * Add token record to session table
     *
     * @param  SessionEntity[] $storage
     * @throws ExceptionInterface
     * @return boolean
     */
    public function write($storage): bool
    {
        // ZF StorageInterface for write does not permit type hint so we can check it here
        Assertion::isArray($storage, 'Session Entity must be in an array');
        // Now ensure only one session was passed
        Assertion::eq(count($storage), 1, 'Storage array may only contain one Session Entity');

        // Now get the session entity and proceed...
        $sessionEntity = $storage[0];

        // Make sure this is an actual session entity
        Assertion::isInstanceOf(
            $sessionEntity,
            SessionEntity::class,
            'Only a session entity may be passed though storage'
        );

        return $this->add($sessionEntity);
    }

    /**
     * Remove token record from session table
     *
     * @return bool
     * @throws Exception
     */
    public function clear(): bool
    {
        if (empty($this->sessionEntity->getToken())) {
            throw new Exception(
                'Session record can not be cleared without a valid token set to the session entity'
            );
        }

        $delete = $this->sql->delete();

        $delete->from(SessionMapper::TABLE);

        $where = new Where();

        $where->equalTo(SessionMapper::TOKEN, $this->sessionEntity->getToken());

        $delete->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($delete);

        $result = $statement->execute();

        return $result->isQueryResult();
    }

    /**
     * @param EntityInterface|SessionEntity $sessionEntity
     * @return bool
     */
    private function add(EntityInterface $sessionEntity): bool
    {
        $insert = $this->sql->insert();

        $insert->values($test = [
            SessionMapper::PHP_SESSION_ID => $sessionEntity->getSessionId(),
            SessionMapper::SESSION_USER   => $sessionEntity->getSessionUser(),
            SessionMapper::TOKEN          => $sessionEntity->getToken(),
            SessionMapper::LIFETIME       => new Literal($this->sessionEntity->getLifetime()),
            SessionMapper::ACCESSED       => new Literal($this->sessionEntity->getLastActive())
        ]);

        $insert->into(SessionMapper::TABLE);

        $statement = $this->sql->prepareStatementForSqlObject($insert);

        $result = $statement->execute();

        return $result->isQueryResult();
    }

    /**
     * @param VOInterface|AuthenticationVO $authenticationVO
     * @return bool
     */
    public function clearSessionRecord(VOInterface $authenticationVO): bool
    {
        $this->sessionEntity->setToken($authenticationVO->getToken());

        try {
            // Check to see if user is still logged in with current token
            $this->read();
        } catch (MissingSessionException $exception) {
            // User token was removed prior to the check
            return false;
        }

        // User has an active token, clear it now
        $this->clear();

        return true;
    }

    /**
     * @return mixed[]
     * @deprecated
     */
    public function getEmployeeInformation(): array
    {
        return $this->getUserStorage();
    }

    /**
     * @return mixed[]
     * @deprecated
     */
    public function getUserStorage(): array
    {
        $userSession = $this->read();

        if (array_key_exists($this->sessionEntity->getSessionRecordId(), $userSession)) {
            return $userSession[$this->sessionEntity->getSessionRecordId()];
        }

        return [];
    }

    /**
     * @return int
     */
    public function getSessionLifeTime(): int
    {
        return $this->sessionEntity->getLifetime();
    }

    /**
     * @param string $token
     * @return bool
     */
    public function refresh(string $token): bool
    {
        $update = $this->sql->update();

        $update->table(SessionMapper::TABLE);

        $update->set([
            SessionMapper::ACCESSED => time()
        ]);

        $where = new Where();

        $where->equalTo(SessionMapper::TOKEN, $token);

        $update->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        return $result->isQueryResult();
    }
}
