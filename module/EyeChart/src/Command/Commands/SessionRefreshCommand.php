<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Commands;

use EyeChart\VO\Authentication\AuthenticationVO;
use EyeChart\VO\VOInterface;

/**
 * Class SessionRefreshCommand
 * @package EyeChart\Command\Commands
 */
final class SessionRefreshCommand extends AbstractCommand
{
    /** @var VOInterface|AuthenticationVO */
    private $authenticationVO;

    /**
     * SessionRefreshCommand constructor.
     * @param VOInterface $authenticationVO
     */
    public function __construct(VOInterface $authenticationVO)
    {
        $this->authenticationVO = $authenticationVO;
    }

    /**
     * @return AuthenticationVO
     */
    public function getAuthenticationVO(): AuthenticationVO
    {
        return $this->authenticationVO;
    }
}
