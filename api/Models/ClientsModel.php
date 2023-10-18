<?php

namespace Api\Models;

use Api\Models\Base\Orm;

class ClientsModel extends Orm
{
    public function __construct(int $role = 0)
    {
        $this->query = "select clients.id as id,clients.name as name,clients.address as address,clients.cp as cp,
       p.name as country,pr.name as province,m.name as municipality,clients.location as 
           location,clients.phone as phone,clients.email as email from clients inner join countries p on 
               clients.country=p.id inner join provinces pr on clients.province=pr.id inner join 
               municipalities m on clients.municipality =m.id";
        $params = array('id', 'name', 'page', 'limit');
        $attributes = ['name', 'address', 'cp', 'country', 'province', 'municipality', 'location', 'phone', 'email'];
        parent::__construct('clients', $params, $attributes, $role);
    }
}