<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 7/10/2017
 * (c) 2017 Swift Transportation
 */

namespace DriverManager\Tests\Fixtures;

use Assert\Assertion;
use DriverManager\Entity\Authenticate\AuthenticateEntity;
use ReflectionClass;

/**
 * This fixture can be used to set up common dependencies required for testing.
 *
 * Class SetUpBeforeClass
 * @package Fixtures
 * @deprecated
 */
final class SetUpBeforeClass
{
    /** @var SetUpBeforeClass */
    private static $instance;

    /** @var AuthenticateEntity  */
    private static $authenticateEntity;

    /**
     * Singleton approach.  The setUpBeforeClass method in an actual unit test is always static.
     *
     * @return SetUpBeforeClass
     */
    public static function initialize(): SetUpBeforeClass
    {
        if (self::$instance === null) {
            self::$instance = new SetUpBeforeClass();
        }

        return self::$instance;
    }

    /**
     * In almost every case, we must have authentication token set.  This can be done once within the setUpBeforeClass
     * method of any given unit test simply by instantiating this class
     *
     * SetUpBeforeClass constructor.
     */
    private function __construct()
    {
        self::$authenticateEntity = new AuthenticateEntity();
        self::$authenticateEntity->setToken(str_repeat('a', 36));
    }

    /**
     * Reflection is necessary to open up an entity and extract every member along with their default values.  These can
     * then be used run various tests as well as the ensuring that every entity is in sync.  Reflection is expensive so
     * it makes sense to use it only when necessary.  The setUpBeforeClass method is an ideal place for this.
     *
     * @param object $entity
     * @return ReflectionClass
     */
    public static function reflectEntity($entity)
    {
        Assertion::isObject($entity);

        $entityReflected = new ReflectionClass($entity);

        return $entityReflected;
    }

    /**
     * Getter for returning the authenticateEntity.  This will allow for setting additional values on a test per test
     * basis.
     *
     * @return AuthenticateEntity
     */
    public static function getAuthenticateEntity(): AuthenticateEntity
    {
        return self::$authenticateEntity;
    }
}
