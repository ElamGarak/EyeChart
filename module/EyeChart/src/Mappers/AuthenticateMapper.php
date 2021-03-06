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
final class AuthenticateMapper extends Mapper
{
    public const TABLE = 'users';
    public const ALIAS = 'u';

    public const USER_IDENTITY_ID = 'UserIdentityId';
    public const USER_NAME        = 'UserName';
    public const CREDENTIALS      = 'Credentials';
    public const PASSWORD         = 'Password';
    public const IS_ACTIVE        = 'IsActive';

    public const TOKEN        = 'token';
    public const TOKEN_LENGTH = 36;
    public const MESSAGES     = 'messages';
    public const IS_VALID     = 'isValid';
    public const HEADER       = 'X-Authentication';

    public const DERIVED_CREDENTIALS = 'derivedCredentials';
    public const STORED_CREDENTIALS  = 'storedCredentials';

    public const SESSION_EXPIRED_MESSAGE               = 'Your session has expired';
    public const SESSION_ENDED_MESSAGE                 = 'Your session has ended';
    public const MESSAGE_ACCESS_TOKEN_RECORD_NOT_FOUND = 'Access token record was not found';
    public const MESSAGE_USER_NOT_ACTIVE               = 'User is not active on this system';
    public const MESSAGE_USER_IS_VALID                 = 'User is Valid';
}
