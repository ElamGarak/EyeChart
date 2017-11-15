<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/15/2017
 * (c) 2017
 */


namespace EyeChart\VO;

use EyeChart\Exception\InvalidDynamicSettingException;


/**
 * Class AbstractVO
 * @package EyeChart\VO
 */
class AbstractVO implements VOInterface
{
    /**
     * @return VOInterface
     */
    public static function build(): VOInterface
    {
        return new self;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws InvalidDynamicSettingException
     */
    public function __set(string $name, $value): void
    {
        throw new InvalidDynamicSettingException();
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
