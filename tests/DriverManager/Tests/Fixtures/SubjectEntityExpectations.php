<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 7/12/2017
 * (c) 2017 Swift Transportation
 */

namespace DriverManager\Tests\Fixtures;

use DriverManager\Exception\UnableToCloneException;
use ReflectionClass;
use stdClass;

/**
 * Class SubjectEntityExpectations
 * @package Fixtures
 * @deprecated (This will be useless once conversion to doctrine is complete)
 */
final class SubjectEntityExpectations
{
    /** @var  SubjectEntityExpectations */
    private static $instance;

    /** @var  ReflectionClass */
    private static $entityReflected;

    /** @var  ValuesGenerator */
    private static $valuesGenerator;

    /** @var stdClass */
    private static $defaultEntityValues;

    /** @var stdClass */
    private static $validEntityValues;

    /** @var stdClass */
    private static $invalidEntityValues;

    /**
     * Though a singleton, we still want to set up the entity class within and perform all needed operations so expected
     * data is available.
     *
     * SubjectEntityExpectations constructor.
     */
    private function __construct()
    {
        $this->setUpEntityClass();
    }

    /**
     * @param ReflectionClass $entityReflected
     * @return SubjectEntityExpectations|null
     */
    public static function initialize(ReflectionClass $entityReflected): SubjectEntityExpectations
    {
        if (self::$instance === null) {
            self::$entityReflected = $entityReflected;

            return new self;
        }

        return null;
    }

    /**
     * Singletons can not be destroyed.  This presents a problem within the testing framework in that this class will
     * remain intact from test to test.  Therefore it should be 'destroyed' in the tearDownAfterSetup method of each
     * test that makes use of it.
     */
    public static function destroy()
    {
        self::$entityReflected = null;
        self::$instance        = null;
    }

    /**
     * Uses the ValuesGenerator fixture to generate expected values
     */
    private function setUpEntityClass(): void
    {
        // The associated entity mapper is required for the values generator to extract all of the correct properties
        // and values
        $entityMapperName = preg_replace('/Entity$/', 'Mapper', self::$entityReflected->getName());

        self::$valuesGenerator = new ValuesGenerator(
            self::$entityReflected,
            new $entityMapperName()
        );

        // Default entity values
        self::$defaultEntityValues = self::$valuesGenerator->getDefaultValuesAsObject();
        // Valid values to test against
        self::$validEntityValues = self::$valuesGenerator->getValidExpectationsAsObject();
        // Invalid to test against
        self::$invalidEntityValues = self::$valuesGenerator->getInValidExpectationsAsObject();
    }

    /**
     * Using the property names within each entity, create an array of method names that can be used for dynamic
     * accessing by a given test.
     *
     * @param string $prefix
     * @return string[]
     */
    public static function getMethodNames(string $prefix = ''): array
    {
        $methodNames = [];
        foreach (self::$defaultEntityValues as $property => $value) {
            $methodPrefix = $prefix;

            // Account for getters that return booleans
            if ($methodPrefix == 'get' && is_bool($value)) {
                $methodPrefix = 'is';
            }

            $methodNames[$property] = $methodPrefix . ucfirst($property);
        }

        return $methodNames;
    }

    /**
     * @throws UnableToCloneException
     */
    private function __clone()
    {
        throw new UnableToCloneException();
    }

    /**
     * @return stdClass
     */
    public static function getDefaultEntityValues(): stdClass
    {
        return self::$defaultEntityValues;
    }

    /**
     * @return stdClass
     */
    public static function getValidEntityValues(): stdClass
    {
        return self::$validEntityValues;
    }

    /**
     * @return stdClass
     */
    public static function getInvalidEntityValues(): stdClass
    {
        return self::$invalidEntityValues;
    }
}
