<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/8/2017
 * (c) 2017
 */

namespace EyeChart\Tests\Model\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\MissingSessionException;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\SessionMapper;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\Authentication\CredentialsVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Driver\StatementInterface;
use Zend\Db\Sql\AbstractPreparableSql;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Literal;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Where;

/**
 * Class AuthenticateStorageDAOTest
 * @package EyeChart\Tests\Model\Authenticate
 */
class AuthenticateStorageDAOTest extends TestCase
{
    /** @var ResultInterface|PHPUnit_Framework_MockObject_MockObject */
    private $mockedResult;

    /** @var StatementInterface|PHPUnit_Framework_MockObject_MockObject */
    private $mockedStatement;

    /** @var Select|PHPUnit_Framework_MockObject_MockObject */
    private $mockedSelect;

    /** @var Insert|PHPUnit_Framework_MockObject_MockObject */
    private $mockedInsert;

    /** @var Update|PHPUnit_Framework_MockObject_MockObject */
    private $mockedUpdate;

    /** @var Delete|PHPUnit_Framework_MockObject_MockObject */
    private $mockedDelete;

    /** @var Sql|PHPUnit_Framework_MockObject_MockObject */
    private $mockedSql;

    /** @var AuthenticateStorageDAO */
    private $dao;

    /** @var AuthenticationVO */
    private static $vo;

    /** @var SessionEntity */
    private static $sessionEntity;

    /** @var string */
    private static $token = '';

    public static function setUpBeforeClass(): void
    {
        self::$sessionEntity = new SessionEntity();
        self::$token         = str_repeat('a', AuthenticateMapper::TOKEN_LENGTH);

        $credentialsVO = new CredentialsVO();

        self::$vo = AuthenticationVO::build()->setUsername('foo')
                                    ->setDerivedCredentials($credentialsVO);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->mockedSelect = $this->getMockBuilder(Select::class)->disableOriginalConstructor()->getMock();

        $this->mockedSelect->expects($this->any())
                           ->method('columns')
                           ->willReturn($this->mockedSelect);

        $this->mockedSelect->expects($this->any())
                           ->method('from')
                           ->willReturn($this->mockedSelect);

        $this->mockedSelect->expects($this->any())
                           ->method('where')
                           ->willReturn($this->mockedSelect);

        $this->mockedResult = $this->getMockBuilder(ResultInterface::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();

        $this->mockedResult->expects($this->any())
                           ->method('isQueryResult')
                           ->willReturn(true);

        $this->mockedResult->expects($this->any())
                           ->method('getFieldCount')
                           ->willReturn(1);

        $this->mockedStatement = $this->getMockBuilder(StatementInterface::class)
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->mockedStatement->expects($this->any())
                              ->method('execute')
                              ->willReturn($this->mockedResult);

        $this->mockedInsert = $this->getMockBuilder(Insert::class)->disableOriginalConstructor()->getMock();

        $this->mockedUpdate = $this->getMockBuilder(Update::class)->disableOriginalConstructor()->getMock();

        $this->mockedDelete = $this->getMockBuilder(Delete::class)->disableOriginalConstructor()->getMock();

        $this->mockedSql = $this->getMockBuilder(Sql::class)->disableOriginalConstructor()->getMock();

        $this->mockedSql->expects($this->any())
                        ->method('select')
                        ->willReturn($this->mockedSelect);

        $this->mockedSql->expects($this->any())
                        ->method('insert')
                        ->willReturn($this->mockedInsert);

        $this->mockedSql->expects($this->any())
                        ->method('update')
                        ->willReturn($this->mockedUpdate);

        $this->mockedSql->expects($this->any())
                        ->method('delete')
                        ->willReturn($this->mockedDelete);

        $this->dao = new AuthenticateStorageDAO($this->mockedSql, self::$sessionEntity);
    }

    public function testIsEmptyReturnsFalseOnException(): void
    {
        $this->mockedSelect->expects($this->any())
                           ->method('columns')
                           ->willThrowException(new MissingSessionException(self::$sessionEntity, __FUNCTION__));

        $result = $this->dao->isEmpty();

        $this->assertFalse($result);
    }

    public function testIsEmptyReturnsTrueOnEmpty(): void
    {
        $this->mockedResult->expects($this->any())
                           ->method('current')
                           ->willReturn([]);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->with($this->mockedSelect)
                        ->willReturn($this->mockedStatement);

        $result = $this->dao->isEmpty();

        $this->assertTrue($result);
    }

    public function testIsEmptyReturnsFalseIfNotEmpty(): void
    {
        $expected = $this->getExpectedReadColumns();

        $this->mockedResult->expects($this->any())
                           ->method('current')
                           ->willReturn($expected);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->with($this->mockedSelect)
                        ->willReturn($this->mockedStatement);

        $result = $this->dao->isEmpty();

        $this->assertFalse($result);

        $this->assertEquals($expected[SessionMapper::SESSION_RECORD_ID], self::$sessionEntity->getSessionRecordId());
        $this->assertEquals($expected[SessionMapper::SESSION_USER], self::$sessionEntity->getSessionUser());
        $this->assertEquals($expected[SessionMapper::ACCESSED], self::$sessionEntity->getLastActive());
    }

    public function testReadSetsSessionValuesIfResultsAreReturned(): void
    {
        $expected = $this->getExpectedReadColumns();

        $this->mockedResult->expects($this->any())
                           ->method('current')
                           ->willReturn($expected);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->with($this->mockedSelect)
                        ->willReturn($this->mockedStatement);

        $result = $this->dao->read();

        $this->assertInternalType('array', $result);

        $this->assertEquals($expected[SessionMapper::SESSION_RECORD_ID], $result[SessionMapper::SESSION_RECORD_ID]);
        $this->assertEquals($expected[SessionMapper::PHP_SESSION_ID], $result[SessionMapper::PHP_SESSION_ID]);
        $this->assertEquals($expected[SessionMapper::SESSION_USER], $result[SessionMapper::SESSION_USER]);
        $this->assertEquals($expected[SessionMapper::TOKEN], $result[SessionMapper::TOKEN]);
        $this->assertEquals($expected[SessionMapper::LIFETIME], $result[SessionMapper::LIFETIME]);
        $this->assertEquals($expected[SessionMapper::ACCESSED], $result[SessionMapper::ACCESSED]);

        $this->assertEquals($expected[SessionMapper::SESSION_RECORD_ID], self::$sessionEntity->getSessionRecordId());
        $this->assertEquals($expected[SessionMapper::SESSION_USER], self::$sessionEntity->getSessionUser());
        $this->assertEquals($expected[SessionMapper::ACCESSED], self::$sessionEntity->getLastActive());
    }

    /**
     * @expectedException \EyeChart\Exception\MissingSessionException
     */
    public function testReadThrowsMissingSessionException(): void
    {
        $this->mockedStatement->expects($this->any())
                              ->method('execute')
                              ->willReturn($this->mockedResult);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->with($this->mockedSelect)
                        ->willReturn($this->mockedStatement);

        $this->dao->read();
    }

    public function testReadDoesNotSetSessionValuesIfNoResultsAreReturned(): void
    {
        self::$sessionEntity = new SessionEntity();

        $this->mockedResult->expects($this->any())
                           ->method('current')
                           ->willReturn([]);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->with($this->mockedSelect)
                        ->willReturn($this->mockedStatement);

        $result = $this->dao->read();

        $this->assertInternalType('array', $result);
        $this->assertEmpty($result);

        $this->assertEquals(self::$sessionEntity->getSessionRecordId(), -1);
        $this->assertEmpty(self::$sessionEntity->getSessionUser());
        $this->assertEquals(self::$sessionEntity->getLastActive(), -1);
    }

    /**
     * @param mixed $invalidStorage
     * @expectedException \Assert\InvalidArgumentException
     * @dataProvider provideInvalidStorage
     */
    public function testWriteThrowsAssertionErrorIfStorageIsNotAnArray($invalidStorage): void
    {
        $this->dao->write($invalidStorage);
    }

    public function testWriteAssertsAdd(): void
    {
        self::$sessionEntity->setSessionId('foo')
                            ->setSessionUser('bar')
                            ->setToken(self::$token)
                            ->setLastActive(123);

        $this->mockedInsert->expects($this->once())
                           ->method('values')
                           ->with([
                               SessionMapper::PHP_SESSION_ID => self::$sessionEntity->getSessionId(),
                               SessionMapper::SESSION_USER   => self::$sessionEntity->getSessionUser(),
                               SessionMapper::TOKEN          => self::$sessionEntity->getToken(),
                               SessionMapper::LIFETIME       => new Literal(self::$sessionEntity->getLifetime()),
                               SessionMapper::ACCESSED       => new Literal(self::$sessionEntity->getLastActive())
                           ])
        ;

        $this->mockedInsert->expects($this->once())
                           ->method('into')
                           ->with(SessionMapper::TABLE);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->with($this->mockedInsert)
                        ->willReturn($this->mockedStatement);

        $result = $this->dao->write([self::$sessionEntity]);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Exception
     */
    public function testClearThrowsExceptionOnEmptyToken(): void
    {
        $dao = new AuthenticateStorageDAO($this->mockedSql, new SessionEntity());

        $this->mockedDelete->expects($this->never())
                           ->method('from')
                           ->with(SessionMapper::TABLE);

        $dao->clear();
    }

    public function testClearAssertsDelete(): void
    {
        self::$sessionEntity->setToken(self::$token);

        $this->mockedDelete->expects($this->once())
                           ->method('from')
                           ->with(SessionMapper::TABLE);

        $where = new Where();
        $where->equalTo(SessionMapper::TOKEN, self::$sessionEntity->getToken());

        $this->mockedDelete->expects($this->once())
                           ->method('where')
                           ->with($where);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->with($this->mockedDelete)
                        ->willReturn($this->mockedStatement);

        $result = $this->dao->clear();

        $this->assertTrue($result);
    }

    public function testClearSessionRecordClearsRecord(): void
    {
        self::$sessionEntity->setToken(self::$token);

        $this->mockedResult->expects($this->any())
                           ->method('current')
                           ->willReturn([]);

        $this->mockedDelete->expects($this->once())
                           ->method('from')
                           ->with(SessionMapper::TABLE);

        $where = new Where();
        $where->equalTo(SessionMapper::TOKEN, self::$sessionEntity->getToken());

        $this->mockedDelete->expects($this->once())
                           ->method('where')
                           ->with($where);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->willReturnCallback(function(/** @noinspection PhpUnusedParameterInspection */
                            AbstractPreparableSql $param) {
                            return $this->mockedStatement;
                        });

        self::$vo->setToken(self::$sessionEntity->getToken());

        $result = $this->dao->clearSessionRecord(self::$vo);

        $this->assertTrue($result);
    }

    public function testClearSessionRecordReturnsFalseIfUserIsNotLoggedOn(): void
    {
        $this->mockedSelect->expects($this->any())
                           ->method('columns')
                           ->willThrowException(new MissingSessionException(self::$sessionEntity, __FUNCTION__));

        $this->mockedDelete->expects($this->never())
                           ->method('from');

        $result = $this->dao->clearSessionRecord(self::$vo);

        $this->assertFalse($result);
    }

    public function testRefreshUpdatesTokenAccessTime(): void
    {
        $this->mockedUpdate->expects($this->once())
                           ->method('table')
                           ->with(SessionMapper::TABLE);

        $this->mockedUpdate->expects($this->once())
                           ->method('set');

        $where = new Where();

        $where->equalTo(SessionMapper::TOKEN, self::$token);

        $this->mockedUpdate->expects($this->once())
                           ->method('where')
                           ->with($where);

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->willReturnCallback(function(/** @noinspection PhpUnusedParameterInspection */
                            AbstractPreparableSql $param) {
                            return $this->mockedStatement;
                        });

        $result = $this->dao->refresh(self::$token);

        $this->assertTrue($result);
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidStorage(): array
    {
        return [
            ['this is not right'],
            [true],
            [null],
            [[]],
            [new stdClass()],
            [function() {}],
            [1],
            [1.1],
            [[0, 1]],
            [[5]],
        ];
    }

    /**
     *
     * @return string[]
     */
    private function getExpectedReadColumns(): array
    {
        return [
            SessionMapper::SESSION_RECORD_ID => '1',
            SessionMapper::PHP_SESSION_ID    => 'foo',
            SessionMapper::SESSION_USER      => 'bar',
            SessionMapper::TOKEN             => str_repeat('a', AuthenticateMapper::TOKEN_LENGTH),
            SessionMapper::LIFETIME          => '135',
            SessionMapper::ACCESSED          => '456'
        ];
    }
}
