<?php
declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/12/2017
 * (c) Eye Chart
 */

namespace EyeChart\Service\Email;

use EyeChart\Model\Email\EmailModel;
use EyeChart\VO\EmailVO;

/**
 * Class EmailService
 * @package EyeChart\Service\Email
 */
final class EmailService
{
    /** @var EmailModel */
    private $emailModel;

    /**
     * EmailService constructor.
     * @param EmailModel $emailModel
     */
    public function __construct(EmailModel $emailModel)
    {
        $this->emailModel = $emailModel;
    }

    /**
     * @param EmailVO $emailVO
     */
    public function send(EmailVO $emailVO): void
    {
        $this->emailModel->send($emailVO);
    }
}
