<?php

namespace Api\Controllers;

use Api\Controllers\Base\BaseAction;
use Api\Helpers\Services;

class PageController extends BaseAction
{
    private string $role;
    /**
     * Class constructor
     * @param string $role
     */
    public function __construct(string $role)
    {
        parent::__construct();
        $this->role = $role;
    }

    /**
     * API request selection method
     * @return void
     */
    public function init(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->model === '') {
            echo $this->role;
        } else {
            switch ($this->method) {
                case 'GET':
                    echo json_encode($this->configurationController()->select());
                    break;
                case 'POST':
                    $this->configurationController()->insert();
                    break;
                case 'PUT':
                    $this->configurationController()->update();
                    break;
                case 'DELETE':
                    $this->configurationController()->delete();
                    break;
                default:
                    Services::undefinedMethod();
            }
        }
    }
    /**
     * Method that returns an instance of the model class
     * @return mixed|void
     */
    private function configurationController() {
        $nameClass = "Api\Models\\" . ucfirst(mb_strtolower($this->model)) . 'Model';
        if (class_exists($nameClass)) {
            $objQuery = new $nameClass($this->role);
            header('content-type: application/json; charset=utf-8');
            return $objQuery;
        } else {
            Services::undefinedController();
        }
    }
}