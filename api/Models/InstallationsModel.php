<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class InstallationsModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'client', 'page', 'limit');
        $attributes = ['name', 'client'];
        parent::__construct('installations', $params, $attributes, $role);
    }
}