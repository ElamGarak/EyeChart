<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Handlers\Email;

use EyeChart\Command\Commands\EmailCommand;
use EyeChart\Model\Email\EmailModel;

/**
 * Class EmailHandler
 * @package EyeChart\Command\Handlers\Email
 */
final class EmailHandler
{
    /** @var EmailCommand */
    private $emailModel;

    /**
     * EmailHandler constructor.
     * @param EmailModel $emailModel
     */
    public function __construct(EmailModel $emailModel)
    {
        $this->emailModel = $emailModel;
    }

    /**
     * @param EmailCommand $command
     */
    public function handle(EmailCommand $command): void
    {
        $this->emailModel->send($command->getEmailVO());
    }
}
