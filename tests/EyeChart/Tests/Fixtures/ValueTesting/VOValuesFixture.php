<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 10/30/2017
 * (c) 2017 Swift Transportation
 */

namespace EyeChart\Tests\Fixtures\ValueTesting;

use ReflectionClass;
use ReflectionMethod;

/**
 * Class VOValuesFixture
 * @package Fixtures\ValuesGenerator
 */
final class VOValuesFixture extends AbstractValuesGenerator
{
    /** @var ReflectionClass  */
    protected $reflectedObject;

    /**
     * ValuesGenerator constructor.
     * This is used to create incoming values and set them with good (valid) data and invalid data.
     *
     * @param ReflectionClass $reflectedObject
     */
    public function __construct(ReflectionClass $reflectedObject)
    {
        $this->reflectedObject = $reflectedObject;

        $this->setDataValues();
    }

    /**
     * @return string[]
     */
    private function setDataValues(): array
    {
        $className = $this->reflectedObject->getName();

        $types = [];
        foreach ($this->reflectedObject->getProperties() as $property) {
            $property->setAccessible(true);

            $method     = new ReflectionMethod($className, "get" . ucfirst($property->getName()));
            $returnType = $method->getReturnType()->getName();

            $property->setValue($this->reflectedObject, $this->fetchGoodValues($returnType));

            $this->defaultValues[$property->getName()] = $this->fetchGoodValues($returnType); // For Convenience Getter
            $this->goodData[$property->getName()]      = $this->fetchGoodValues($returnType); // For Convenience Getter
            $this->invalidData[$property->getName()]   = $this->fetchInvalidValues(
                $this->goodData[$property->getName()]
            );
        }

        return $types;
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    protected function fetchGoodValues(string $type)
    {
        $value = null;
        switch ($type) {
            case 'string':
                $value = uniqid();

                break;
            case 'int':
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
}
