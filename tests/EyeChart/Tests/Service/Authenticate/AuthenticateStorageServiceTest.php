<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/1/2017
 * (c) Eye Chart
 */

namespace EyeChart\Tests\Service\Authenticate;

use EyeChart\Entity\SessionEntity;
use EyeChart\Repository\Authentication\AuthenticationRepository;
use EyeChart\Service\Authenticate\AuthenticateStorageService;
use EyeChart\Tests\Service\ServiceSetupTest;
use EyeChart\VO\TokenVO;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class AuthenticateStorageServiceTest
 * @package EyeChart\Tests\Service\Authenticate
 */
class AuthenticateStorageServiceTest extends ServiceSetupTest
{
    /**
     * Set up the specific subject under test and pass these values to the parent for service testing
     */
    public function setUp(): void
    {
        /** @var AuthenticationRepository|PHPUnit_Framework_MockObject_MockObject $model */
        $model = $this->getMockBuilder(AuthenticationRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        parent::$service = new AuthenticateStorageService($model);

        parent::$serviceDependency = $model;

        parent::setUp();
    }

    /**
     * Override parent data provider with these service tests
     *
     * @return array
     */
    public function provideServices()
    {
        return [
            [$this->once(), 'isEmpty', 'isEmpty', null, true],
            [$this->once(), 'read', 'read', null, []],
            [$this->once(), 'write', 'write', new SessionEntity(), true],
            [$this->once(), 'clear', 'clear', null, null],
            [$this->once(), 'getUserSessionByToken', 'getUserSessionStatus', new TokenVO(), []],
        ];
    }
}
