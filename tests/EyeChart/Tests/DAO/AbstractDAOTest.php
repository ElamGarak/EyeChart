<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/11/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\DAO;

use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\Mappers\AuthenticateMapper;
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
 * Class AbstractDAOTest
 * @package EyeChart\Tests\DAO
 */
class AbstractDAOTest extends TestCase
{
    /** @var ResultInterface|PHPUnit_Framework_MockObject_MockObject */
    private $mockedResult;

    /** @var Result|PHPUnit_Framework_MockObject_MockObject */
    private $mockedStatement;

    /** @var Select|PHPUnit_Framework_MockObject_MockObject */
    private $mockedSelect;

    /** @var Sql|PHPUnit_Framework_MockObject_MockObject */
    private $mockedSql;

    /** @var AuthenticateDAO */
    private $dao;

    /** @var AuthenticationVO */
    private static $vo;

    public static function setUpBeforeClass(): void
    {
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

        $this->mockedSql = $this->getMockBuilder(Sql::class)->disableOriginalConstructor()->getMock();

        $this->mockedSql->expects($this->any())
                        ->method('prepareStatementForSqlObject')
                        ->with($this->mockedSelect)
                        ->willReturn($this->mockedStatement);

        $this->mockedSql->expects($this->any())
                        ->method('select')
                        ->willReturn($this->mockedSelect);

        $this->dao = new AuthenticateDAO($this->mockedSql);
    }

    public function testCheckCredentialsDoesNotThrowUserCredentialSDoNotMatchException(): void
    {
        $this->mockedResult->expects($this->any())
                           ->method('current')
                           ->willReturn(['foo' => 'bar']);

        $result = $this->dao->checkCredentials(self::$vo);

        $this->assertNull($result);
    }

    public function testParseDataTypesInAbstractDAO(): void
    {
        $this->mockedResult->expects($this->any())
                           ->method('current')
                           ->willReturn([AuthenticateMapper::IS_ACTIVE => "1.1"]);

        $result = $this->dao->isUserActive(self::$vo);

        $this->assertTrue($result);
    }
}
