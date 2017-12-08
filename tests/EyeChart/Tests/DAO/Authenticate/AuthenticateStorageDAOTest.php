<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/4/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Model\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\MissingSessionException;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\SessionMapper;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\Authentication\CredentialsVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Db\Adapter\Driver\Pdo\Result;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Driver\StatementInterface;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

/**
 * Class AuthenticateStorageDAOTest
 * @package EyeChart\Tests\Model\Authenticate
 */
class AuthenticateStorageDAOTest extends TestCase
{
    /** @var ResultInterface|PHPUnit_Framework_MockObject_MockObject */
    private $mockedResult;

    /** @var Result|PHPUnit_Framework_MockObject_MockObject */
    private $mockedStatement;

    /** @var Select|PHPUnit_Framework_MockObject_MockObject */
    private $mockedSelect;

    /** @var Sql|PHPUnit_Framework_MockObject_MockObject */
    private $mockedSql;

    /** @var AuthenticateStorageDAO */
    private $dao;

    /** @var AuthenticationVO */
    private static $vo;

    /** @var SessionEntity */
    private static $sessionEntity;

    public static function setUpBeforeClass(): void
    {
        $credentialsVO = new CredentialsVO();

        self::$vo = AuthenticationVO::build()->setUsername('foo')
            ->setDerivedCredentials($credentialsVO);

        self::$sessionEntity = new SessionEntity();
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

        $this->mockedSql = $this->getMockBuilder(Sql::class)->disableOriginalConstructor()->getMock();

        $this->mockedSql->expects($this->any())
            ->method('prepareStatementForSqlObject')
            ->with($this->mockedSelect)
            ->willReturn($this->mockedStatement);

        $this->mockedSql->expects($this->any())
            ->method('select')
            ->willReturn($this->mockedSelect);

        $this->dao = new AuthenticateStorageDAO($this->mockedSql, self::$sessionEntity);
    }

    public function testIsEmptyReturnsFalseOnException(): void
    {
        $this->mockedResult->expects($this->any())
            ->method('current')
            ->willThrowException(new MissingSessionException(self::$sessionEntity, __METHOD__));

        $result = $this->dao->isEmpty();

        $this->assertFalse($result);
    }

    public function testIsEmptyReturnsFalseOnResultsFoundInRead(): void
    {
        $expected = [
            SessionMapper::SESSION_RECORD_ID => '1',
            SessionMapper::PHP_SESSION_ID    => 'foo',
            SessionMapper::SESSION_USER      => 'foo',
            SessionMapper::TOKEN             => 'foo',
            SessionMapper::LIFETIME          => '456',
            SessionMapper::ACCESSED          => '123'
        ];

        $this->mockedResult->expects($this->any())
            ->method('current')
            ->willReturn($expected);

        $result = $this->dao->isEmpty();

        $this->assertTrue($result);
    }

    public function testRead(): void
    {
        $expected = [
            SessionMapper::SESSION_RECORD_ID => '1',
            SessionMapper::PHP_SESSION_ID    => 'foo',
            SessionMapper::SESSION_USER      => 'foo',
            SessionMapper::TOKEN             => 'foo',
            SessionMapper::LIFETIME          => '456',
            SessionMapper::ACCESSED          => '123'
        ];

        $this->mockedResult->expects($this->any())
            ->method('current')
            ->willReturn($expected);

        $result = $this->dao->read();

        $this->assertInternalType('array', $result);
        $this->assertEquals($expected[SessionMapper::SESSION_USER], self::$sessionEntity->getSessionUser());
        $this->assertEquals($expected[SessionMapper::ACCESSED], (int) self::$sessionEntity->getLastActive());
        $this->assertEquals($expected[SessionMapper::SESSION_RECORD_ID], (int) self::$sessionEntity->getSessionRecordId());
    }

    /**
     * @expectedException \EyeChart\Exception\MissingSessionException
     */
    public function testReadThrowsException(): void
    {
        $this->mockedResult->expects($this->any())
            ->method('current')
            ->willThrowException(new MissingSessionException(self::$sessionEntity, __METHOD__));

        $this->dao->read();
    }
}