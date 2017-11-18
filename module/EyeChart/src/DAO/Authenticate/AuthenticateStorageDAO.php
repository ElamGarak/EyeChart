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
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\EntityInterface;
use EyeChart\Entity\SessionEntity;
use EyeChart\Mappers\SessionMapper;
use Zend\Authentication\Adapter\DbTable\Exception\RuntimeException;
use Zend\Authentication\Exception\ExceptionInterface;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\Literal;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateStorageDAO
 * @package EyeChart\DAO\Authenticate
 */
final class AuthenticateStorageDAO implements StorageInterface
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
     * Returns the contents of storage
     *
     * @return mixed[]
     * @throws RuntimeException
     */
    public function read(): array
    {
        $select = $this->sql->select();

        //$select->columns([ SessionMapper::DATA ]);

        $select->from(SessionMapper::TABLE);

        $where = new Where();

        $where->equalTo(SessionMapper::SESSION_RECORD_ID, $this->sessionEntity->getSessionRecordId())->and
              ->equalTo(SessionMapper::PHP_SESSION_ID, $this->sessionEntity->getPhpSessionId());

        $select->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($select);

        $result = $statement->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet();
            $resultSet->initialize($result);

            $row = $resultSet->current();

            if (null != $row) {
                $storage = $row->getArrayCopy();

                //return json_decode($storage[SessionMapper::DATA], true);
            }

            return [];
        }

        throw new RuntimeException("Failed to find session record {$this->sessionEntity->getSessionRecordId()} in " . __METHOD__);
    }

    /**
     * Writes $contents to storage
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
     * Clears contents from storage
     *
     * @return bool
     */
    public function clear(): bool
    {
        $delete = $this->sql->delete();

        $delete->from(SessionMapper::TABLE);

        $where = new Where();

        $where->equalTo(SessionMapper::SESSION_RECORD_ID, $this->sessionEntity->getSessionRecordId())->and
              ->equalTo(SessionMapper::PHP_SESSION_ID, $this->sessionEntity->getPhpSessionId());

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
            SessionMapper::LIFETIME       => new Literal($this->sessionEntity->getLifetime())
        ]);

        $insert->into(SessionMapper::TABLE);

        $statement = $this->sql->prepareStatementForSqlObject($insert);

        $result = $statement->execute();

        return $result->isQueryResult();
    }

    /**
     * @param EntityInterface|SessionEntity $storage
     * @return bool
     * @deprecated
     */
    private function merge(EntityInterface $storage): bool
    {
        $sessionData = [ $this->sessionEntity->getSessionRecordId() => $storage ];

        $this->existingStorage = json_encode(array_merge_recursive($this->existingStorage, $sessionData));

        $this->sessionEntity->setToken($this->existingStorage);

        $update = $this->sql->update();

        $update->table(SessionMapper::TABLE);

        $update->set([
            //SessionMapper::DATA     => $this->sessionEntity->getToken(),
            SessionMapper::MODIFIED => time()
        ]);

        $where = new Where();

        $where->equalTo(SessionMapper::SESSION_RECORD_ID, $this->sessionEntity->getSessionRecordId())->and
              ->equalTo(SessionMapper::PHP_SESSION_ID, $this->sessionEntity->getPhpSessionId());

        $update->where($where);

        $statement = $this->sql->prepareStatementForSqlObject($update);

        $result = $statement->execute();

        return $result->isQueryResult();
    }

    /**
     * @param AuthenticateEntity|EntityInterface $authenticateEntity
     * @return bool
     */
    public function prune(EntityInterface $authenticateEntity): bool
    {
        $currentStorage = $this->read();

        if (array_key_exists($this->sessionEntity->getSessionRecordId(), $currentStorage) === false) {
            // User session expired at some point and there is nothing to prune

            $authenticateEntity->addMessage('Your session has expired');

            return false;
        }

        $userStorage = $currentStorage[$this->sessionEntity->getSessionRecordId()];

        if (array_key_exists($authenticateEntity->getToken(), $userStorage) === true) {
            unset($userStorage[$authenticateEntity->getToken()]);
        }

        if (count($userStorage) != 0) {
            // User has more than one token in the session, update with token removed and return

            $authenticateEntity->addMessage('You have been logged out of this session');

            return $this->merge($userStorage);
        }

        // User had only one token in the session, clear them out rather than leave an empty, orphan record
        $this->clear();

        $authenticateEntity->addMessage('You have been logged out');

        return true;
    }

    /**
     * @return mixed[]
     */
    public function getEmployeeInformation(): array
    {
        return $this->getUserStorage();
    }

    /**
     * @return mixed[]
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
     */
    public function refresh(string $token): void
    {
        $userStorage                                  = $this->getUserStorage();
        $userStorage[$token][SessionMapper::MODIFIED] = time();

        $this->merge($userStorage);
    }
}
