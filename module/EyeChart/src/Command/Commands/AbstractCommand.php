<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Commands;

/**
 * Class AbstractCommand
 * @package EyeChart\Command\Commands
 */
class AbstractCommand implements CommandInterface
{
    /**
     * @param string $field
     * @param $value
     */
    public function __set(string $field, $value): void
    {
        // Prevent dynamic setting
    }
}
