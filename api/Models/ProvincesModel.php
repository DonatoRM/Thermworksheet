<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class ProvincesModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'country', 'page', 'limit');
        $attributes = ['name', 'country'];
        parent::__construct('provinces', $params, $attributes, $role);
    }
}