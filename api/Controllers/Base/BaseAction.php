<?php

namespace Api\Controllers\Base;

abstract class BaseAction
{
    protected string $method;
    protected string $model;
    protected string $function;
    protected array $args = [];

    /**
     * Constructor de la clase BaseAction
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $folderPath = dirname($_SERVER['SCRIPT_NAME']);
        $urlPath = $_SERVER['REQUEST_URI'];
        $url = substr($urlPath, strlen($folderPath));
        $url = explode('?', $url)[0];
        $urlArray = explode('/', $url);
        if ($urlArray[0] !== '') {
            $this->model = mb_strtolower(array_shift($urlArray));
            if (count($urlArray) !== 0) {
                $this->function = mb_strtolower(array_shift($urlArray));
            } else {
                $this->function = '';
            }
        } else {
            $this->model = '';
        }
    }
}