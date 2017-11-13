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
 * Class AuthenticateMapper
 * @package EyeChart\Entity\Authenticate
 */
final class AuthenticateMapper extends AbstractMapper
{
    public const TOKEN    = 'token';
    public const MESSAGES = 'messages';
    public const IS_VALID = 'isValid';
    public const HEADER   = 'X-Authentication';
}