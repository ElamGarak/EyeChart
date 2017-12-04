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
use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\Authentication\CredentialsVO;
use EyeChart\VO\VOInterface;
use PHPUnit\Framework\TestCase;
use Zend\Db\Adapter\Adapter;

/**
 * Class AuthenticateDAOTest
 * @package EyeChart\Tests\Model\Authenticate
 */
class AuthenticateDAOTest extends TestCase
{
    /** @var AuthenticateDAO */
    private $dao;

    /** @var AuthenticationVO */
    private static $vo;

    public static function setUpBeforeClass(): void
    {
        self::$vo = VOInterface::build();
    }

    public function setUp(): void
    {
        parent::setUp();

        /** @var Adapter $mockedAdapter */
        $mockedAdapter = $this->getMockBuilder(Adapter::class)->disableOriginalConstructor()->getMock();

        $this->dao = new AuthenticateDAO($mockedAdapter);
    }

    public function testCheckCredentialsReturnsResult(): void
    {
        $credentialsVO = new CredentialsVO();
        self::$vo->setUsername('foo')->setDerivedCredentials($credentialsVO);

        $this->dao->checkCredentials(self::$vo);
    }
}
