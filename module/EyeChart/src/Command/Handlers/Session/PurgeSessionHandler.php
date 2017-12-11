<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * Author: Joshua M Pacheco <joshua.pacheco@gmail.com>
 * Date: 12/10/2017
 * (c) 2017
 */

namespace EyeChart\Command\Handlers\Session;

use EyeChart\Command\Commands\PurgeSessionCommand;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;

/**
 * Class PurgeSessionHandler
 * @package EyeChart\Command\Handlers\Session
 */
final class PurgeSessionHandler
{
    /** @var AuthenticateStorageModel */
    private $authenticateStorageModel;

    /**
     * SessionRefreshHandler constructor.
     *
     * @param AuthenticateStorageModel $authenticateStorageModel
     */
    public function __construct(AuthenticateStorageModel $authenticateStorageModel)
    {
        $this->authenticateStorageModel = $authenticateStorageModel;
    }

    /**
     * @param PurgeSessionCommand $command
     */
    public function handle(PurgeSessionCommand $command): void
    {
        $this->authenticateStorageModel->purge($command);
    }
}
