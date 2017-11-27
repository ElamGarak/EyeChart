<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace EyeChart\Tests\Fixtures\ValueTesting;

use Assert\InvalidArgumentException;
use ReflectionClass;

/**
 * Class EntityValuesFixture
 * @package Fixtures\ValuesGenerator
 */
final class EntityValuesFixture extends AbstractValuesGenerator
{

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
     * @param string $key
     * @param string $defaultValue
     *
     * @return mixed
     */
    protected function fetchGoodValues(string $key, $defaultValue)
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
                    if (method_exists($entity, $setterMethod)) {
                        $entity->$setterMethod($defaultValue);
                    }
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
            default:
        }

        return $value;
    }

    /**
     * Build up values
     * @param mixed[] $values
     *
     * @throws \UnexpectedValueException
     */
    protected function setDataValues(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->defaultValues[$key] = $value;
            $this->goodData[$key]      = $this->fetchGoodValues($key, $value);
            $this->invalidData[$key]   = $this->fetchInvalidValues($value);
        }
    }
}
