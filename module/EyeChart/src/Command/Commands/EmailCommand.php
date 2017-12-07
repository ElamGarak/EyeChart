<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Command\Commands;

use EyeChart\VO\EmailVO;

/**
 * Class EmailCommand
 * @package EyeChart\Command\Commands
 */
final class EmailCommand extends AbstractCommand
{
    /** @var EmailVO */
    private $emailVO;

    /**
     * EmailCommand constructor.
     * @param EmailVO $emailVO
     */
    public function __construct(EmailVO $emailVO)
    {
        $this->emailVO = $emailVO;
    }

    /**
     * @return EmailVO
     * @codeCoverageIgnore
     */
    public function getEmailVO(): EmailVO
    {
        return $this->emailVO;
    }
}
