<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class RolesModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'page', 'limit');
        $attributes = ['rol'];
        parent::__construct('roles', $params, $attributes, $role);
    }
}