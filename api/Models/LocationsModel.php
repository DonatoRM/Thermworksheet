<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class LocationsModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'installation', 'page', 'limit');
        $attributes = ['name', 'columns', 'installation'];
        parent::__construct('locations', $params, $attributes, $role);
    }
}