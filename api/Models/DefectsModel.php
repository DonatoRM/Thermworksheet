<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class DefectsModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $params = array('id', 'name', 'date', 'position', 'page', 'limit');
        $attributes = ['date', 'wind', 'emissivity', 'point_temperature', 'reference_temperature', 'room_temperature',
            'reflected_apparent_temperature', 'maximum_current', 'current', 'feedback', 'thermogram', 'photo', 'position'];
        parent::__construct('defects', $params, $attributes, $role);
    }
}