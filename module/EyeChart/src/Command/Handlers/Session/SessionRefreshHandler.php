<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Handlers\Session;

use EyeChart\Command\Commands\SessionRefreshCommand;
use EyeChart\Entity\Authenticate\AuthenticateEntity;
use EyeChart\Model\Authenticate\AuthenticateStorageModel;

/**
 * Class SessionRefreshHandler
 * @package EyeChart\Command\Handlers\Session
 */
final class SessionRefreshHandler
{
    /** @var AuthenticateStorageModel */
    private $authenticateStorageModel;

    /** @var AuthenticateEntity */
    private $authenticateEntity;

    /**
     * SessionRefreshHandler constructor.
     *
     * @param AuthenticateStorageModel $authenticateStorageModel
     * @param AuthenticateEntity $authenticateEntity
     */
    public function __construct(
        AuthenticateStorageModel $authenticateStorageModel,
        AuthenticateEntity $authenticateEntity
    ) {
        $this->authenticateStorageModel = $authenticateStorageModel;
        $this->authenticateEntity       = $authenticateEntity;
    }

    /**
     * @param SessionRefreshCommand $command
     */
    public function handle(SessionRefreshCommand $command): void
    {
        $this->authenticateStorageModel->refresh();
    }
}
