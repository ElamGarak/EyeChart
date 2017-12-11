<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/10/2017
 * (c) 2017
 */

namespace EyeChart\Command\Commands;

/**
 * Class PurgeSessionHandler
 * @package EyeChart\Command\Commands
 */
class PurgeSessionCommand extends AbstractCommand
{
    /** @var mixed[]  */
    private $sessionConfig = [];

    /**
     * PurgeSessionHandler constructor.
     * @param mixed[] $sessionConfig
     */
    public function __construct(array $sessionConfig)
    {
        $this->sessionConfig = $sessionConfig;
    }

    /**
     * @return mixed[]
     */
    public function getSessionConfig(): array
    {
        return $this->sessionConfig;
    }
}
