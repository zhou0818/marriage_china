<?php
/**
 * Created by PhpStorm.
 * User: zhou
 * Date: 2018/4/10
 * Time: 15:01
 */

namespace App\Enums;


class UserStatusEnum
{
    const INIT = 0;
    const UNAUDITED = 1;
    const AUDITED = 2;
    const FAIL = -1;
}