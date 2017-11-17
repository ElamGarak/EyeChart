<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 11/8/2017
 * (c) 2017
 */

namespace EyeChart\Mappers;

/**
 * Class SessionMapper
 * @package EyeChart\Entity\Session
 */
final class SessionMapper extends AbstractMapper
{
    public const SCHEMA = '';
    public const TABLE  = 'session';
    public const ALIAS  = 'storage';

    public const SESSION_ID   = 'SessionId';
    public const PHP_SESS_ID  = 'PHPSessId';
    public const MODIFIED     = 'Modified';
    public const SESSION_USER = 'SessionUser';
    public const LIFETIME     = 'Lifetime';

    public const MODIFIED_TIME  = 'modifiedTime';
    public const SYS_TIME       = 'systemTime';
    public const EXPIRED        = 'expired';
    public const REMAINING      = 'remaining';
    public const THRESHOLD      = 'threshold';
    public const ACTIVE_CHECK   = 'activeCheck';
}
