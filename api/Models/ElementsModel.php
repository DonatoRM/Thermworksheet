<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class ElementsModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'page', 'limit');
        $attributes = ['id', 'name'];
        parent::__construct('elements', $params, $attributes, $role);
    }
}