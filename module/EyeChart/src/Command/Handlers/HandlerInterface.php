<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */
namespace EyeChart\Command\Handlers;

use EyeChart\Command\Commands\CommandInterface;

/**
 * Interface HandlerInterface
 * @package EyeChart\Command\Handlers
 */
interface HandlerInterface
{
    /**
     * @param CommandInterface $command
     */
    public function handle(CommandInterface $command): void;
}
