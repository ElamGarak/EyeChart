<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/4/2017
 * (c) 2017
 */

namespace API\Tests\Model\Authenticate;

use EyeChart\DAO\Authenticate\AuthenticateDAO;
use EyeChart\Entity\AuthenticateEntity;
use EyeChart\Entity\SessionEntity;
use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Model\Authenticate\AuthenticateModel;
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\Authentication\CredentialsVO;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class AuthenticateModelTest
 * @package API\Tests\Model\Authenticate
 */
final class AuthenticateModelTest extends TestCase
{
    /** @var AuthenticateDAO|PHPUnit_Framework_MockObject_MockObject */
    private $mockedDao;

    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /** @var SessionEntity */
    private $sessionEntity;

    /** @var AuthenticateModel */
    private $model;

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
        parent::setUp();

        $this->mockedDao = $this->getMockBuilder(AuthenticateDAO::class)
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->authenticateEntity = new AuthenticateEntity();
        $this->sessionEntity      = new SessionEntity();

        $this->model = new AuthenticateModel($this->mockedDao, $this->authenticateEntity, $this->sessionEntity);
    }

    /**
     * @expectedException \EyeChart\Exception\UnableToAuthenticateException
     */
    public function testCheckCredentialsThrowsUnableToAuthenticateExceptionOnCryptoException(): void
    {
        $vo = AuthenticationVO::build()->setDerivedCredentials(
            CredentialsVO::build()->setCredentials(str_repeat('1', 512))
        );

        $vo->setStoredCredentials(
            CredentialsVO::build()->setCredentials(str_repeat('1', 512))
        );

        $this->model->checkCredentials($vo);
    }

    public function testCheckCredentialsSetsAuthenticateEntityEntityTrue(): void
    {
        $vo = AuthenticationVO::build()->setDerivedCredentials(
            CredentialsVO::build()->setCredentials(self::$validCode)
        );

        $vo->setStoredCredentials(
            CredentialsVO::build()->setCredentials(self::$validCode)
        );

        $vo->setPassword('foo');

        $this->model->checkCredentials($vo);

        $this->assertTrue($this->authenticateEntity->getIsValid());
    }

    /**
     * @expectedException \EyeChart\Exception\UserNotFoundException
     */
    public function testGetUsersStoredCredentialsThrowsUserNotFoundException(): void
    {
        $daoResults = [
            AuthenticateMapper::IS_ACTIVE => 1,
        ];

        $this->mockedDao->expects($this->once())
                        ->method('getUsersStoredCredentials')
                        ->willReturn($daoResults);

        $this->model->getUsersStoredCredentials(AuthenticationVO::build());
    }

    /**
     * @expectedException \EyeChart\Exception\UserNotActiveException
     */
    public function testGetUsersStoredCredentialsThrowsUserNotActiveException(): void
    {
        $daoResults = [
            AuthenticateMapper::CREDENTIALS => uniqid(),
            AuthenticateMapper::IS_ACTIVE   => 0,
        ];

        $this->mockedDao->expects($this->once())
                        ->method('getUsersStoredCredentials')
                        ->willReturn($daoResults);

        $this->model->getUsersStoredCredentials(AuthenticationVO::build());
    }

    public function testGetUsersStoredCredentials(): void
    {
        $daoResults = [
            AuthenticateMapper::CREDENTIALS => uniqid(),
            AuthenticateMapper::IS_ACTIVE   => 1,
        ];

        $this->mockedDao->expects($this->once())
                        ->method('getUsersStoredCredentials')
                        ->willReturn($daoResults);

        $results = $this->model->getUsersStoredCredentials(AuthenticationVO::build());

        $this->assertEquals($daoResults[AuthenticateMapper::CREDENTIALS], $results);
    }
}
