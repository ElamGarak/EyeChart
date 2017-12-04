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
     * @expectedException \EyeChart\Exception\UserCredentialsInvalidException
     */
    public function testCheckCredentialsThrowsUserCredentialsInvalidException(): void
    {
        $vo = AuthenticationVO::build()->setDerivedCredentials(
            CredentialsVO::build()->setCredentials(str_repeat('1', 512))
        );

        $vo->setStoredCredentials(
            CredentialsVO::build()->setCredentials(str_repeat('2', 512))
        );

        $this->model->checkCredentials($vo);
    }

    public function testCheckCredentialsThrowsUnableToAuthenticateExceptionOnCryptoException(): void
    {
        $this->markTestIncomplete();
    }

    public function testCheckCredentialsThrowsUnableToAuthenticateExceptionOnUserCredentialsDoNotMatchException(): void
    {
        $this->markTestIncomplete();
    }

    public function checkCredentialsSetsAuthenticateEntityEntityTrue(): void
    {
        $this->markTestIncomplete();
    }

    public function testCheckCredentialsSetsAuthenticateEntityEntityTrue(): void
    {
        $this->markTestIncomplete();
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
