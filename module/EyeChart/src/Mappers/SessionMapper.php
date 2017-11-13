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
    public const TABLE  = 'SESSION';
    public const ALIAS  = 'STORAGE';

    public const ID       = 'ID';
    public const NAME     = 'NAME';
    public const MODIFIED = 'MODIFIED';
    public const DATA     = 'DATA';
    public const LIFETIME = 'LIFETIME';

    public const MODIFIED_TIME  = 'modifiedTime';
    public const SYS_TIME       = 'systemTime';
    public const EXPIRED        = 'expired';
    public const REMAINING      = 'remaining';
    public const THRESHOLD      = 'threshold';
    public const ACTIVE_CHECK   = 'activeCheck';
}
