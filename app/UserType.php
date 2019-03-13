<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected static $ADMIN_ID = 1;
    public static function getAdminId()
    {
        return $this->ADMIN_ID;
    }
}
