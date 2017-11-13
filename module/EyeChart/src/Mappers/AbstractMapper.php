<?php
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua_pacheco@swifttrans.com>
 * Date: 11/8/2017
 * (c) 2017 Swift Transportation
 */

namespace EyeChart\Mappers;

/**
 * Class AbstractMapper
 * @package EyeChart\Mappers
 */
class AbstractMapper implements MapperInterface
{
    /**
     * @return self
     */
    public static function build(): self
    {
        return new self;
    }

    /**
     * @return mixed[]
     */
    public function getConstants(): array
    {
        $constantClass = new \ReflectionClass(__CLASS__);

        return $constantClass->getConstants();
    }
}
