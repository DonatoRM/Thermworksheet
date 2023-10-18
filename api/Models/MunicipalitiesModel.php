<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class MunicipalitiesModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'province', 'page', 'limit');
        $attributes = ['name', 'province'];
        parent::__construct('municipalities', $params, $attributes, $role);
    }
}