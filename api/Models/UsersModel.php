<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class UsersModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('username', 'name', 'rol', 'page', 'limit');
        $attributes = ['username', 'password', 'rol'];
        parent::__construct('users', $params, $attributes, $role);
    }
}