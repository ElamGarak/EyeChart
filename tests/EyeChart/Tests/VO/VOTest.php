<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 11/28/2017
 * (c) 2017 Swift Transportation
 */

namespace EyeChart\Tests\VO;

use EyeChart\Mappers\AuthenticateMapper;
use EyeChart\Tests\Fixtures\ValueTesting\VOValuesFixture;
use EyeChart\VO\TokenVO;
use EyeChart\VO\VOInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class VOTest
 * @package EyeChart\Tests\VO
 */
class VOTest extends TestCase
{

    /** @var string */
    protected static $subjectVONameSpace = TokenVO::class;

    /** @var array[]  */
    protected static $validAssertionValues = [];

    /** @var array[]  */
    protected static $invalidAssertionValues = [];

    /** @var mixed[] */
    private static $expectedPayload = [];

    /** @var mixed[] */
    private static $invalidPayload = [];

    /** @var VOInterface|TokenVO */
    private $subjectVO;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $voReflected     = new ReflectionClass(self::$subjectVONameSpace);
        $valuesGenerator = new VOValuesFixture($voReflected);

        self::$expectedPayload = $valuesGenerator->getValidExpectationsAsArray();
        self::$invalidPayload  = $valuesGenerator->getInValidExpectationsAsArray();

        self::setDefaultTokenAssertionValues();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->subjectVO  = new self::$subjectVONameSpace();
    }

    public function testValidSetting(): void
    {
        foreach (self::$validAssertionValues as $propertyName => $validValues) {
            $setter = 'set' . ucfirst($propertyName);
            $getter = 'get' . ucfirst($propertyName);

            foreach ($validValues as $validValue) {
                $vo = $this->subjectVO::build()->{$setter}($validValue);

                $this->assertEquals($validValue, $vo->{$getter}());
            }
        }
    }

    /**
     * @expectedException \TypeError
     */
    public function testDependencyTypeHintWasSet(): void
    {
        foreach (self::$invalidPayload as $propertyName => $value) {
            $setter = 'set' . ucfirst($propertyName);

            $this->subjectVO::build()->{$setter}($value);
        }
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     */
    public function testAssertionsThrown(): void
    {
        foreach (self::$invalidAssertionValues as $propertyName => $invalidValues) {
            $setter = 'set' . ucfirst($propertyName);

            foreach ($invalidValues as $invalidValue) {
                $this->subjectVO::build()->{$setter}($invalidValue);
            }
        }
    }

    private static function setDefaultTokenAssertionValues(): void
    {
        if (self::$subjectVONameSpace !== TokenVO::class) {
            return;
        }

        self::$invalidAssertionValues = [
            AuthenticateMapper::TOKEN => ['']
        ];

        self::$validAssertionValues = [
            AuthenticateMapper::TOKEN => [str_repeat('a', AuthenticateMapper::TOKEN_LENGTH)]
        ];
    }
}
