<?php

namespace Api\Controllers;

use Api\Controllers\Base\BaseAction;
use Api\Helpers\Services;

class PageController extends BaseAction
{
    private int $role;
    private array $arrayAuth;
    /**
     * Class constructor
     * @param string $role
     */
    public function __construct(array $arrayAuth)
    {
        parent::__construct();
        $this->role = $arrayAuth['role'];
        $this->arrayAuth=$arrayAuth;
    }

    /**
     * API request selection method
     * @return void
     */
    public function init(): void
    {
        if ($this->model === '') {
            echo json_encode($this->arrayAuth);
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