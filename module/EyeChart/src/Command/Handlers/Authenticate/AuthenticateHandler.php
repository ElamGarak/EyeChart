<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */
namespace EyeChart\Command\Handlers\Authenticate;

use EyeChart\Command\Commands\AuthenticateCommand;
use EyeChart\Service\Authenticate\AuthenticateListener;

/**
 * Class AuthenticateHandler
 * @package EyeChart\Command\Handlers\Authenticate
 */
class AuthenticateHandler
{

    /** @var AuthenticateListener */
    private $authenticate;

    /**
     * AuthenticateHandler constructor.
     * @param AuthenticateListener $authenticate
     */
    public function __construct(AuthenticateListener $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    /**
     * @param AuthenticateCommand $command
     */
    public function handle(AuthenticateCommand $command): void
    {
        $this->authenticate->checkAuthentication($command->getEvent());
    }
}
