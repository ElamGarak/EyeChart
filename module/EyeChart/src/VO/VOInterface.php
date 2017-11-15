<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/14/2017
 * (c) 2017
 */

namespace EyeChart\VO;

/**
 * Interface VOInterface
 * @package EyeChart\VO
 */
interface VOInterface
{
    /**
     * @return mixed[]
     */
    public function toArray(): array;
}
