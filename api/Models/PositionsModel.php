<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class PositionsModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'location', 'element', 'page', 'limit');
        $attributes = ['name', 'element', 'point', 'type', 'fase', 'voltage', 'location'];
        parent::__construct('positions', $params, $attributes, $role);
    }
}