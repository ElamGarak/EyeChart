<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 7/5/2017
 * (c) 2017 Swift Transportation
 */
namespace DriverManager\Tests\Fixtures;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use DriverManager\Exception\InvalidDateValueException;
use ReflectionClass;
use stdClass;

/**
 * Class ValuesGenerator
 *
 * This is a helper class used to generate various data values to test against.
 *
 * @package Fixtures
 * @deprecated (Use Entity Values Fixture)
 */
final class ValuesGenerator
{
    /** @var ReflectionClass  */
    private $reflectedObject;

    /** @var mixed[]  */
    private $defaultValues = [];

    /** @var mixed[]  */
    private $goodData = [];

    /** @var mixed[]  */
    private $invalidData = [];

    /**
     * ValuesGenerator constructor.
     * This is used to create incoming values and set them with good (valid) data and invalid data.
     *
     * @param ReflectionClass $reflectedObject
     */
    public function __construct(ReflectionClass $reflectedObject)
    {
        $this->reflectedObject = $reflectedObject;

        $values = $this->extractPropertiesFromEntity();

        $this->setDataValues($values);
    }

    /**
     * @return array
     */
    public function getDefaultValuesAsArray(): array
    {
        return $this->defaultValues;
    }

    /**
     * @return stdClass
     */
    public function getDefaultValuesAsObject(): stdClass
    {
        return (object) $this->defaultValues;
    }

    /**
     * @return array
     */
    public function getValidExpectationsAsArray(): array
    {
        return $this->goodData;
    }

    /**
     * @return stdClass
     */
    public function getValidExpectationsAsObject(): stdClass
    {
        return (object) $this->goodData;
    }

    /**
     * @return array
     */
    public function getInValidExpectationsAsArray(): array
    {
        return $this->invalidData;
    }

    /**
     * @return stdClass
     */
    public function getInValidExpectationsAsObject(): stdClass
    {
        return (object) $this->invalidData;
    }

    /**
     * Allow for specific values to be set to map
     *
     * @param string $key
     * @param mixed $value
     */
    public function setValidValue(string $key, $value): void
    {
        $this->goodData[$key] = $value;
    }

    /**
     * Build up values
     * @param mixed[] $values
     * @throws \UnexpectedValueException
     */
    private function setDataValues(array $values): void
    {
        foreach ($values as $key => $value) {
            if (! is_string($key)) {
                throw new \UnexpectedValueException("Array keys must be strings.");
            }

            $this->defaultValues[$key] = $value;
            $this->goodData[$key]      = $this->fetchGoodValues($key, $value);
            $this->invalidData[$key]   = $this->fetchInvalidValues($value);
        }
    }

    /**
     * @param string $key
     * @param string $defaultValue
     * @return mixed
     */
    private function fetchGoodValues(string $key, $defaultValue)
    {
        $type = gettype($defaultValue);

        $value = null;
        switch ($type) {
            case 'string':
                try {
                    // Attempt to ascertain of setter requires a date mask, if so, the Assert\InvalidArgumentException
                    // will be thrown
                    $entityName   = $this->reflectedObject->getName();
                    $entity       = new $entityName($entityName);
                    $setterMethod = "set" . ucfirst($key);
                    $entity->$setterMethod($defaultValue);
                } catch (InvalidArgumentException $exception) {
                    // Assert\InvalidArgumentException was thrown, attempt to format a date
                    $value = $this->getPropertyFormattedDateString($exception);

                    break;
                }

                $value = uniqid();

                break;
            case 'integer':
                $value = 1;

                break;
            case 'boolean':
                $value = true;

                break;
            case 'decimal':
                $value = 1.1;

                break;
            case 'email':
                $value = 'jdoe@foobar.com';

                break;
            case 'GUID':
// TODO This can be modified later to work with UUIDs
//                $value = uniqid();
//
//                break;
            default:
        }

        return $value;
    }

    /**
     * @param mixed $defaultValue
     * @return mixed
     */
    private function fetchInvalidValues($defaultValue)
    {
        $type = gettype($defaultValue);

        $value = null;
        switch ($type) {
            case 'string':
                $value = 1;

                break;
            case 'integer':
                $value = uniqid();

                break;
            case 'boolean':
                $value = [];

                break;
            case 'decimal':
                $value = 1;

                break;
            case 'email':
                $value = 'foo';

                break;
            case 'GUID':
// TODO This can be modified later to work with UUIDs
//                $value = uniqid();
//
//                break;
            default:
        }

        return $value;
    }

    /**
     * Helper.  This will build a map of all members within an entity based on its associated mapper.  This also has the
     * extra benefit of testing that both the mapper and the entity intersect correctly.  Reflection is used since the
     * members within an entity are always private.
     *
     * @return mixed[]
     */
    private function extractPropertiesFromEntity(): array
    {
        $className = $this->reflectedObject->getName();

        $members = [];
        foreach ($this->reflectedObject->getProperties() as $property => $value) {
            $value->setAccessible(true);
            $members[$value->getName()] = $value->getValue(new $className());
        }

        return $members;
    }

    /**
     * @param AssertionFailedException $exception
     * @return string
     * @throws InvalidDateValueException
     */
    private function getPropertyFormattedDateString(AssertionFailedException $exception): string
    {
        $constraints = $exception->getConstraints();

        if (! array_key_exists('format', $constraints)) {
            return "Date Format does not exist";
        }

        $now = date($constraints['format'], strtotime('today'));
        $d   = \DateTime::createFromFormat($constraints['format'], $now);

        if (($d && $d->format($constraints['format']) === $now) === true) {
            return $now;
        }

        throw new InvalidDateValueException();
    }
}
