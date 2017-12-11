<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Commands;

use EyeChart\Exception\ForbiddenMagicSettingException;

/**
 * Class AbstractCommand
 * @package EyeChart\Command\Commands
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @param string $field
     * @param $value
     * @throws ForbiddenMagicSettingException
     */
    public function __set(string $field, $value): void
    {
        throw new ForbiddenMagicSettingException();
    }
}
