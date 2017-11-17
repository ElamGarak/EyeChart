<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 7/24/2017
 * (c) 2017
 */

namespace EyeChart\Entity;

use Assert\Assertion;
use Exception;
use EyeChart\Exception\InvalidDataSourceException;
use EyeChart\Exception\UndefinedSetterException;
use EyeChart\VO\VOInterface;

/**
 * Class AbstractEntity
 * @package EyeChart\Entity
 */
class AbstractEntity implements EntityInterface
{
    /**
     * @param mixed $dataSource
     * @throws InvalidDataSourceException
     */
    public function initialize($dataSource): void
    {
        switch (true) {
            case is_array($dataSource):
                $this->initializeByArray($dataSource);
                break;
            case ($dataSource instanceof VOInterface):
                $this->initializeByVO($dataSource);
                break;
            default:
                throw new InvalidDataSourceException(__METHOD__);
                break;
        }
    }

    /**
     * @param VOInterface $vo
     */
    public function initializeByVO(VOInterface $vo): void
    {
        Assertion::isInstanceOf($vo, VOInterface::class, "Invalid Value Object Passed to " . __METHOD__);

        $this->initializeByArray($vo->toArray());
    }

    /**
     * @param mixed[] $dataSource
     * @throws UndefinedSetterException
     */
    public function initializeByArray(array $dataSource): void
    {
        try {
            foreach ($dataSource as $key => $value) {
                if (! property_exists($this, $key)) {
                    throw new Exception($key);
                }

                $setter = "set" . ucfirst($key);
                $this->$setter($value);
            }
        } catch (Exception $exception) {
            throw new UndefinedSetterException($exception->getMessage(),__CLASS__);
        }
    }

    /**
     * @param mixed[] $dataSource
     * @throws UndefinedSetterException
     */
    public function hydrateFromDataBase(array $dataSource): void
    {
        try {
            foreach ($dataSource as $key => $value) {
                $property = lcfirst($key);

                if (! property_exists($this, $property)) {
                    throw new Exception($property);
                }

                $setter = "set" . ucfirst($key);

                $this->$setter($value);
            }
        } catch (Exception $exception) {
            throw new UndefinedSetterException($exception->getMessage(),__CLASS__);
        }
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        $values = [];

        foreach ($this as $propertyName => $value) {
            $values[$propertyName] = $value;
        }

        return $values;
    }
}
