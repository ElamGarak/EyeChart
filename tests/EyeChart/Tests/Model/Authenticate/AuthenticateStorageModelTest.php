<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pachjo <joshua.pacheco@gmail.com>
 * Date: 12/8/2017
 * (c) Eye Chart
 */

namespace API\Tests\Model\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\SessionEntity;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\SessionMapper;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\TokenVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Config\Config;

/**
 * Class AuthenticateStorageModelTest
 * @package API\Tests\Model\Authenticate
 */
class AuthenticateStorageModelTest extends TestCase
{
    /** @var AuthenticateStorageDAO|PHPUnit_Framework_MockObject_MockObject */
    private $mockedAuthenticateStorageDAO;

    /** @var AuthenticateStorageModel */
    private $model;

    /** @var AuthenticateEntity */
    private static $authenticateEntity;

    /** @var SessionEntity */
    private static $sessionEntity;

    /** @var Config */
    private static $config;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$authenticateEntity = new AuthenticateEntity();
        self::$sessionEntity      = new SessionEntity();
        self::$config             = new Config([
            'timeoutWarningThreshold' => 5,
            'activeSessionCheck'      => true,
        ]);

        self::$authenticateEntity->setToken(str_repeat('a', AuthenticateMapper::TOKEN_LENGTH));
    }

    public function setUp(): void
    {
        $this->mockedAuthenticateStorageDAO = $this->getMockBuilder(AuthenticateStorageDAO::class)
                                            ->disableOriginalConstructor()
                                            ->getMock();

        $this->model = new AuthenticateStorageModel(
            $this->mockedAuthenticateStorageDAO,
            self::$authenticateEntity,
            self::$sessionEntity,
            self::$config
        );
    }

    public function testClearSessionStatusReturnsIfTokenHasExpired(): void
    {
        $vo = AuthenticationVO::build()->setToken(self::$authenticateEntity->getToken());

        $this->mockedAuthenticateStorageDAO->expects($this->once())
                                           ->method('read')
                                           ->willReturn([
                                               SessionMapper::ACCESSED => 1
                                           ]);

        $this->mockedAuthenticateStorageDAO->expects($this->once())
                                           ->method('clearSessionRecord')
                                           ->with($vo)
                                           ->willReturn(true);

        $this->model->checkSessionStatus($vo);

        $this->assertFalse(self::$authenticateEntity->getIsValid());
    }

    public function testClearSessionStatusRefreshesIfTokenHasNotExpired(): void
    {
        $vo = AuthenticationVO::build()->setToken(self::$authenticateEntity->getToken());

        $this->mockedAuthenticateStorageDAO->expects($this->once())
                                           ->method('read')
                                           ->willReturn([
                                               SessionMapper::ACCESSED => 9999999999,
                                               SessionMapper::TOKEN    => self::$authenticateEntity->getToken()
                                           ]);

        $this->mockedAuthenticateStorageDAO->expects($this->never())
                                           ->method('clearSessionRecord');

        $this->mockedAuthenticateStorageDAO->expects($this->once())
                                           ->method('refresh')
                                           ->with(self::$authenticateEntity->getToken());

        $this->model->checkSessionStatus($vo);

        $this->assertFalse(self::$authenticateEntity->getIsValid());
    }

    public function testGetUserSessionStatusReturnsSessionRecord(): void
    {
        $vo = TokenVO::build()->setToken(self::$authenticateEntity->getToken());

        $sessionRecord = [
            SessionMapper::ACCESSED => 9999999999,
            SessionMapper::TOKEN    => self::$authenticateEntity->getToken()
        ];

        $this->mockedAuthenticateStorageDAO->expects($this->once())
            ->method('read')
            ->willReturn($sessionRecord);

        $results = $this->model->getUserSessionStatus($vo);

        $this->assertInternalType('array', $results);
        $this->assertTrue($results[SessionMapper::ACTIVE_CHECK]);
        $this->assertFalse($results[SessionMapper::EXPIRED]);
        $this->assertLessThan(0, $results[SessionMapper::REMAINING]);
        $this->assertGreaterThan(0, $results[SessionMapper::SYS_TIME]);
        $this->assertEquals(self::$config->get('timeoutWarningThreshold'), $results[SessionMapper::THRESHOLD]);
    }

    /**
     * @expectedException \EyeChart\Exception\SettingNotFoundException
     */
    public function testActiveSessionCheckThrowsExceptionIfActiveSessionCheckIsNotSet(): void
    {
        $vo = TokenVO::build()->setToken(self::$authenticateEntity->getToken());

        $sessionRecord = [
            SessionMapper::ACCESSED => 9999999999,
            SessionMapper::TOKEN    => self::$authenticateEntity->getToken()
        ];

        $this->mockedAuthenticateStorageDAO->expects($this->once())
                                           ->method('read')
                                           ->willReturn($sessionRecord);

        $model = new AuthenticateStorageModel(
            $this->mockedAuthenticateStorageDAO,
            self::$authenticateEntity,
            self::$sessionEntity,
            new Config([
                'timeoutWarningThreshold' => 5
            ])
        );

        $model->getUserSessionStatus($vo);
    }

    /**
     * @expectedException \EyeChart\Exception\SettingNotFoundException
     */
    public function testActiveSessionCheckThrowsExceptionIfSessionTimeoutIsNotSet(): void
    {
        $vo = TokenVO::build()->setToken(self::$authenticateEntity->getToken());

        $sessionRecord = [
            SessionMapper::ACCESSED => 9999999999,
            SessionMapper::TOKEN    => self::$authenticateEntity->getToken()
        ];

        $this->mockedAuthenticateStorageDAO->expects($this->once())
                                           ->method('read')
                                           ->willReturn($sessionRecord);

        $model = new AuthenticateStorageModel(
            $this->mockedAuthenticateStorageDAO,
            self::$authenticateEntity,
            self::$sessionEntity,
            new Config([
                'activeSessionCheck' => true,
            ])
        );

        $model->getUserSessionStatus($vo);
    }
}
