<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/4/2017
 * (c) 2017
 */

namespace API\Tests\Repository\Authentication;

use EyeChart\Entity\SessionEntity;
use EyeChart\Exception\NoResultsFoundException;
use EyeChart\Exception\UnableToAuthenticateException;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Mappers\SessionMapper;
use EyeChart\Model\Authenticate\AuthenticateModel;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;
use EyeChart\Model\Employee\EmployeeModel;
use EyeChart\Repository\Authentication\AuthenticationRepository;
use EyeChart\Service\Authenticate\AuthenticateAdapter;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\Authentication\CredentialsVO;
use EyeChart\VO\TokenVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Authentication\AuthenticationService as ZendAuthentication;
use Zend\Authentication\Result;

/**
 * Class AuthenticationRepositoryTest
 * @package API\Tests\Repository\Authentication
 */
class AuthenticationRepositoryTest extends TestCase
{
    /** @var AuthenticateModel|PHPUnit_Framework_MockObject_MockObject */
    private $mockedAuthenticateModel;

    /** @var AuthenticateStorageModel|PHPUnit_Framework_MockObject_MockObject */
    private $mockedAuthenticateStorageModel;

    /** @var AuthenticateAdapter|PHPUnit_Framework_MockObject_MockObject */
    private $mockedAuthenticateAdapter;

    /** @var ZendAuthentication|PHPUnit_Framework_MockObject_MockObject */
    private $mockedZendAuthenticationService;

    /** @var EmployeeModel|PHPUnit_Framework_MockObject_MockObject */
    private $mockedEmployeeModel;

    /** @var AuthenticationRepository */
    private $repository;

    /** @var string */
    private static $validCode = '';

    public static function setUpBeforeClass(): void
    {
        self::$validCode  = 'def10000def50200279ce24cccc729b59d342226b09a27aea8a19b1d8ee63b9bd556f3c90746b9a54b7b91a0f';
        self::$validCode .= '4e6c84ca0e5f32b1788513b22f1f0f8b1a537ee7873b14bb72151ff082703818ff174eff1dbd8ee80ccff634e';
        self::$validCode .= 'e21db37c23d42da171552e427329aa755b0cb3053fff4a9d5a253e06f7ec2da0048d41f10ce91f56e4ce4024a';
        self::$validCode .= '58d3f65a57ad9a214b27bd9585ce0dbae044d16676828ff046c8a1b3bfdf8f1842ba3d6edf03fd207ac18d039';
        self::$validCode .= '414668862f104ca9b33277e4e147856046b3bcdfdc20f07d25ddb0318bb98d8ca93ee6076344e077a5ef77e5a';
        self::$validCode .= 'dcd6ee24ad4a73dfcf158b57cfabcc8fc72d274850c8a84acfac3e9aaf3f4a74534';
    }

    public function setUp(): void
    {
        $this->mockedAuthenticateModel = $this->getMockBuilder(AuthenticateModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedAuthenticateStorageModel = $this->getMockBuilder(AuthenticateStorageModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedAuthenticateAdapter = $this->getMockBuilder(AuthenticateAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedZendAuthenticationService = $this->getMockBuilder(ZendAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedEmployeeModel = $this->getMockBuilder(EmployeeModel::class)
            ->disableOriginalConstructor()
            ->getMock();


        $this->repository = new AuthenticationRepository(
            $this->mockedAuthenticateModel,
            $this->mockedAuthenticateStorageModel,
            $this->mockedAuthenticateAdapter,
            $this->mockedZendAuthenticationService,
            $this->mockedEmployeeModel
        );
    }

    public function testPruneSessionEndedMessageIfClearsSessionSucceeds(): void
    {
        $vo = new AuthenticationVO();
        $this->mockedAuthenticateStorageModel->expects($this->once())
            ->method('clearSessionRecord')
            ->with()
            ->willReturn(true);

        $this->mockedAuthenticateModel->addMessage(AuthenticateMapper::SESSION_ENDED_MESSAGE);

        $this->repository->prune($vo);
    }

    public function testPruneSessionExpiredMessageIfClearSessionFails(): void
    {
        $vo = new AuthenticationVO();
        $this->mockedAuthenticateStorageModel->expects($this->once())
                                      ->method('clearSessionRecord')
                                      ->with(new AuthenticationVO())
                                      ->willReturn(false);

        $this->mockedAuthenticateModel->addMessage(AuthenticateMapper::SESSION_EXPIRED_MESSAGE);

        $this->repository->prune($vo);
    }

    /**
     * @expectedException \EyeChart\Exception\UnauthorizedException
     */
    public function testLoginThrowsUnauthorizedExceptionForNoResultsFound(): void
    {
        $this->mockedAuthenticateModel->expects($this->once())
            ->method('getEncoded')
            ->willThrowException(new NoResultsFoundException());

        $this->mockedAuthenticateModel->expects($this->never())
            ->method('getUsersStoredCredentials');

        $this->repository->login(AuthenticationVO::build());
    }

    /**
     * @expectedException \EyeChart\Exception\UnauthorizedException
     */
    public function testLoginThrowsUnauthorizedExceptionForUnableToAuthenticate(): void
    {
        $this->mockedAuthenticateModel->expects($this->once())
            ->method('getEncoded')
            ->willThrowException(new UnableToAuthenticateException(AuthenticationVO::build()));

        $this->mockedAuthenticateModel->expects($this->never())
            ->method('getUsersStoredCredentials');

        $this->repository->login(AuthenticationVO::build());
    }

    public function testLoginReturnsSessionToken(): void
    {
        $vo = AuthenticationVO::build()->setPassword('foo');

        $this->mockedAuthenticateModel->expects($this->once())
            ->method('getEncoded')
            ->with($vo->getPassword())
            ->willReturn(self::$validCode);

        $vo->setDerivedCredentials(CredentialsVO::build()->setCredentials(self::$validCode));
        $this->mockedAuthenticateModel->expects($this->once())
            ->method('getUsersStoredCredentials')
            ->with($vo)
            ->willReturn(self::$validCode);

        $sessionEntity = new SessionEntity();
        $sessionEntity->setToken(str_repeat('a', AuthenticateMapper::TOKEN_LENGTH));

        $this->mockedAuthenticateModel->expects($this->once())
            ->method('generateSessionEntity')
            ->with($vo)
            ->willReturn($sessionEntity);

        $this->mockedAuthenticateStorageModel->expects($this->once())
            ->method('write')
            ->with([$sessionEntity]);

        $this->mockedZendAuthenticationService->expects($this->once())
            ->method('setStorage')
            ->with($this->mockedAuthenticateStorageModel);

        $actual = $this->repository->login($vo);

        $this->assertEquals($sessionEntity->getToken(), $actual);
    }

    public function testAuthenticateUserReturnsValidIfResultIsValid(): void
    {
        $vo = AuthenticationVO::build();

        $this->mockedAuthenticateModel->expects($this->once())
                                      ->method('setTokenToAuthenticate')
                                      ->with($vo);

        $this->mockedZendAuthenticationService->expects($this->once())
                                              ->method('setStorage')
                                              ->with($this->mockedAuthenticateStorageModel);

        $result = new Result(1, 'foo');

        $this->mockedZendAuthenticationService->expects($this->once())
                                              ->method('authenticate')
                                              ->with($this->mockedAuthenticateAdapter)
                                              ->willReturn($result);

        $result = $this->repository->authenticateUser($vo);

        $this->assertTrue($result);
    }

    public function testAuthenticateUserReturnsInValidIfResultIsInValid(): void
    {
        $vo = AuthenticationVO::build();

        $this->mockedAuthenticateModel->expects($this->once())
                                      ->method('setTokenToAuthenticate')
                                      ->with($vo);

        $this->mockedZendAuthenticationService->expects($this->once())
                                              ->method('setStorage')
                                              ->with($this->mockedAuthenticateStorageModel);

        $result = new Result(0, 'foo');

        $this->mockedZendAuthenticationService->expects($this->once())
                                              ->method('authenticate')
                                              ->with($this->mockedAuthenticateAdapter)
                                              ->willReturn($result);

        $this->mockedAuthenticateStorageModel->expects($this->once())
                                      ->method('clearSessionRecord')
                                      ->with($vo);

        $this->mockedAuthenticateModel->expects($this->once())
                                      ->method('getMessages')
                                      ->willReturn([]);

        $result = $this->repository->authenticateUser($vo);

        $this->assertFalse($result);
    }

    public function testGetUserSessionStatusClearsUponExpired(): void
    {
        $vo = TokenVO::build()->setToken(str_repeat('a', AuthenticateMapper::TOKEN_LENGTH));
        $this->mockedAuthenticateStorageModel->expects($this->once())
                                             ->method('getUserSessionStatus')
                                             ->with($vo)
                                             ->willReturn([
                                                 SessionMapper::EXPIRED => true
                                             ]);

        $this->mockedAuthenticateStorageModel->expects($this->once())
                                             ->method('clearSessionRecord');

        $results = $this->repository->getUserSessionStatus($vo);

        $this->assertTrue($results[SessionMapper::EXPIRED]);
    }

    public function testGetUserSessionStatusDoesNotClearSessionIfStillActive(): void
    {
        $vo = TokenVO::build()->setToken(str_repeat('a', AuthenticateMapper::TOKEN_LENGTH));
        $this->mockedAuthenticateStorageModel->expects($this->once())
                                             ->method('getUserSessionStatus')
                                             ->with($vo)
                                             ->willReturn([
                                                 SessionMapper::EXPIRED => false
                                             ]);

        $this->mockedAuthenticateStorageModel->expects($this->never())
                                             ->method('clearSessionRecord');

        $results = $this->repository->getUserSessionStatus($vo);

        $this->assertFalse($results[SessionMapper::EXPIRED]);
    }
}
