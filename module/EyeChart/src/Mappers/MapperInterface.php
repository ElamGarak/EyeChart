<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Mappers;
/**
 * Interface MapperInterface
 * @package EyeChart\Mappers
 */
interface MapperInterface
{
    /** @return self */
    public static function build();

    /** @return mixed[] */
    public function getConstants(): array;
}
