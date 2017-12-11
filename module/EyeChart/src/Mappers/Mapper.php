<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Mappers;

/**
 * Class AbstractMapper
 * @package EyeChart\Mappers
 */
class Mapper implements MapperInterface
{
    /**
     * @return self
     * @codeCoverageIgnore
     */
    public static function build(): self
    {
        return new self;
    }

    /**
     * @return mixed[]
     * @codeCoverageIgnore
     */
    public function getConstants(): array
    {
        $constantClass = new \ReflectionClass(__CLASS__);

        return $constantClass->getConstants();
    }
}
