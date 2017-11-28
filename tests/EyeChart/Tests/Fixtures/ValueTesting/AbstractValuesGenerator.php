<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 10/24/2017
 * (c) 2017
 */

namespace EyeChart\Tests\Fixtures\ValueTesting;

use Assert\AssertionFailedException;
use EyeChart\Exception\InvalidDateValueException;
use ReflectionClass;
use stdClass;

/**
 * Class AbstractValuesGenerator
 * @package Fixtures\ValuesGenerator
 */
class AbstractValuesGenerator
{
    /** @var ReflectionClass  */
    protected $reflectedObject;

    /** @var mixed[]  */
    protected $defaultValues = [];

    /** @var mixed[]  */
    protected $goodData = [];

    /** @var mixed[]  */
    protected $invalidData = [];

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
     * @param mixed  $value
     */
    public function setValidValue(string $key, $value): void
    {
        $this->goodData[$key] = $value;
    }

    /**
     * @param mixed $defaultValue
     *
     * @return mixed
     */
    protected function fetchInvalidValues($defaultValue)
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
            default:
        }

        return $value;
    }

    /**
     * @param AssertionFailedException $exception
     *
     * @return string
     * @throws InvalidDateValueException
     */
    protected function getPropertyFormattedDateString(AssertionFailedException $exception): string
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
