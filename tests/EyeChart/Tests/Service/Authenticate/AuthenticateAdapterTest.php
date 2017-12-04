<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/1/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Service\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\DAO\Authenticate\AuthenticateStorageDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\SessionEntity;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\SessionMapper;
use EyeChart\Service\Authenticate\AuthenticateAdapter;
use EyeChart\VO\Authentication\AuthenticationVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Authentication\Result;
use Zend\Session\SessionManager;

/**
 * Class AuthenticateAdapterTest
 * @package EyeChart\Tests\Service\Authenticate
 */
class AuthenticateAdapterTest extends TestCase
{
    /** @var SessionManager|PHPUnit_Framework_MockObject_MockObject */
    private $mockedSessionManager;

    /** @var AuthenticateDAO|PHPUnit_Framework_MockObject_MockObject  */
    private $mockedAuthenticateDao;

    /** @var AuthenticateStorageDAO|PHPUnit_Framework_MockObject_MockObject */
    private $mockedAuthenticateStorageDao;

    /** @var AuthenticateAdapter */
    private $adapter;

    /** @var SessionEntity */
    private static $sessionEntity;

    /** @var AuthenticateEntity */
    private static $authenticateEntity;

    public static function setUpBeforeClass(): void
    {
        self::$sessionEntity      = new SessionEntity();
        self::$authenticateEntity = new AuthenticateEntity();
    }

    public function setUp(): void
    {
        $this->mockedSessionManager = $this->getMockByNameSpace(SessionManager::class);

        $this->mockedSessionManager->expects($this->once())
            ->method('start');

        $this->mockedAuthenticateDao = $this->getMockByNameSpace(AuthenticateDAO::class);

        $this->mockedAuthenticateStorageDao = $this->getMockByNameSpace(AuthenticateStorageDAO::class);

        self::$authenticateEntity->setToken(str_repeat('a', AuthenticateMapper::TOKEN_LENGTH));

        $this->adapter = new AuthenticateAdapter(
            $this->mockedSessionManager,
            self::$sessionEntity,
            self::$authenticateEntity,
            $this->mockedAuthenticateDao,
            $this->mockedAuthenticateStorageDao
        );
    }

    public function testAuthenticateThrowsAmbiguousFailure(): void
    {
        $result = $this->adapter->authenticate();

        $this->assertEquals($result->getCode(), Result::FAILURE_IDENTITY_AMBIGUOUS);
        $this->assertEquals($result->getIdentity(), SessionMapper::SESSION_RECORD_ID);
        $this->assertEquals($result->getMessages(), [SessionMapper::MESSAGE_SESSION_NOT_FOUND]);
    }

    /**
     * @depends testAuthenticateThrowsAmbiguousFailure
     */
    public function testAuthenticateThrowsNotFoundFailure(): void
    {
        $expectedSessionId   = uniqid('id');
        $expectedSessionName = uniqid('name');

        $this->mockedSessionManager->expects($this->once())
            ->method('getId')
            ->willReturn($expectedSessionId);

        $this->mockedSessionManager->expects($this->once())
            ->method('getName')
            ->willReturn($expectedSessionName);

        self::$sessionEntity->setSessionId($expectedSessionId)->setPhpSessionId($expectedSessionName);

        $this->mockedAuthenticateStorageDao->expects($this->once())
            ->method('read');

        $result = $this->adapter->authenticate();

        $this->assertEquals($result->getCode(), Result::FAILURE_IDENTITY_NOT_FOUND);
        $this->assertEquals($result->getIdentity(), AuthenticateMapper::TOKEN);
        $this->assertEquals($result->getMessages(), [AuthenticateMapper::MESSAGE_ACCESS_TOKEN_RECORD_NOT_FOUND]);
    }

    /**
     * @depends testAuthenticateThrowsNotFoundFailure
     */
    public function testAuthenticationThrowsInvalidCredentialFailure(): void
    {
        $expectedSessionId   = uniqid('id');
        $expectedSessionName = uniqid('name');

        $this->mockedSessionManager->expects($this->once())
            ->method('getId')
            ->willReturn($expectedSessionId);

        $this->mockedSessionManager->expects($this->once())
            ->method('getName')
            ->willReturn($expectedSessionName);

        self::$sessionEntity->setSessionId($expectedSessionId)
            ->setPhpSessionId($expectedSessionName)
            ->setSessionRecordId(1);

        $this->mockedAuthenticateStorageDao->expects($this->once())
            ->method('read');

        self::$sessionEntity->setSessionUser('foo');

        $authenticateVO = AuthenticationVO::build()->setUsername(self::$sessionEntity->getSessionUser());

        $this->mockedAuthenticateDao->expects($this->once())
            ->method('isUserActive')
            ->with($authenticateVO)
            ->willReturn(false);

        $result = $this->adapter->authenticate();

        $this->assertEquals($result->getCode(), Result::FAILURE_CREDENTIAL_INVALID);
        $this->assertEquals($result->getIdentity(), AuthenticateMapper::TOKEN);
        $this->assertEquals($result->getMessages(), [AuthenticateMapper::MESSAGE_USER_NOT_ACTIVE]);
    }

    public function testAuthenticate(): void
    {
        $expectedSessionId   = uniqid('id');
        $expectedSessionName = uniqid('name');

        $this->mockedSessionManager->expects($this->once())
            ->method('getId')
            ->willReturn($expectedSessionId);

        $this->mockedSessionManager->expects($this->once())
            ->method('getName')
            ->willReturn($expectedSessionName);

        self::$sessionEntity->setSessionId($expectedSessionId)
            ->setPhpSessionId($expectedSessionName)
            ->setSessionRecordId(1);

        $this->mockedAuthenticateStorageDao->expects($this->once())
            ->method('read');

        $authenticateVO = AuthenticationVO::build()->setUsername(self::$sessionEntity->getSessionUser());

        $this->mockedAuthenticateDao->expects($this->once())
            ->method('isUserActive')
            ->with($authenticateVO)
            ->willReturn(true);

        $result = $this->adapter->authenticate();

        $this->assertEquals($result->getCode(), Result::SUCCESS);
        $this->assertEquals($result->getIdentity(), self::$authenticateEntity->getToken());
        $this->assertEquals($result->getMessages(), [AuthenticateMapper::MESSAGE_USER_IS_VALID]);
    }

    /**
     * @param string $nameSpace
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockByNameSpace(string $nameSpace): PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockBuilder($nameSpace)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
