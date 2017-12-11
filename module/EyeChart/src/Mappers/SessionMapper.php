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

    public const SESSION_RECORD_ID = 'SessionId';
    public const PHP_SESSION_ID    = 'PHPSessionId';
    public const SESSION_USER      = 'SessionUser';
    public const TOKEN             = 'Token';
    public const LIFETIME          = 'Lifetime';
    public const ACCESSED          = 'AccessTimestamp';

    public const MODIFIED_TIME  = 'modifiedTime';
    public const SYS_TIME       = 'systemTime';
    public const EXPIRED        = 'expired';
    public const REMAINING      = 'remaining';
    public const THRESHOLD      = 'threshold';
    public const ACTIVE_CHECK   = 'activeCheck';

    public const MESSAGE_SESSION_NOT_FOUND = 'PHP Session ID was not found';
}
