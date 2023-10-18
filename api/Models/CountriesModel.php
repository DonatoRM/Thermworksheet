<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class CountriesModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'page', 'limit');
        $attributes = ['name'];
        parent::__construct('countries', $params, $attributes, $role);
    }
}