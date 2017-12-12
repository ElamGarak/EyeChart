<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 7/24/2017
 * (c) 2017
 */

namespace EyeChart\Entity;

use EyeChart\Exception\InvalidDataSourceException;
use EyeChart\VO\VOInterface;

/**
 * Class AbstractEntity
 * @package EyeChart\Entity
 */
abstract class AbstractEntity implements EntityInterface
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
        $this->initializeByArray($vo->toArray());
    }

    /**
     * @param mixed[] $dataSource
     */
    public function initializeByArray(array $dataSource): void
    {
        foreach ($dataSource as $key => $value) {
            if (! property_exists($this, $key)) {
                continue;
            }

            $setter = "set" . ucfirst($key);
            $this->$setter($value);
        }
    }

    /**
     * @param mixed[] $dataSource
     */
    public function hydrateFromDataBase(array $dataSource): void
    {
        foreach ($dataSource as $key => $value) {
            $property = lcfirst($key);

            if (! property_exists($this, $property)) {
                continue;
            }

            $setter = "set" . ucfirst($key);

            $this->$setter($value);
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
