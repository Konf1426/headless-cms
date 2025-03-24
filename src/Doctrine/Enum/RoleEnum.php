<?php

declare(strict_types=1);

namespace App\Doctrine\Enum;

class RoleEnum
{
    /**
     * @var string
     */
    public const USER = 'ROLE_USER';

    /**
     * @var string
     */
    public const IS_GRANTED_USER = "is_granted('ROLE_USER')";

    /**
     * @var string
     */
    public const ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    public const IS_GRANTED_ADMIN = "is_granted('ROLE_ADMIN')";

    /**
     * @var string
     */
    public const IS_AUTHOR_OBJECT = "object.author == user";

    /**
     * @var string
     */
    public const IS_ADMIN_OR_AUTHOR_OBJECT =  "is_granted('ROLE_ADMIN') or object.author == user";

    /**
     * @var string
     */
    public const IS_ADMIN_OR_USER_OBJECT =  "is_granted('ROLE_ADMIN') or object == user";
    /**
     * @var string
     */
    public const IS_GRANTED_ADMIN_AND_AUTHOR = "is_granted('ROLE_ADMIN') or is_granted('ROLE_AUTHOR')";
}
