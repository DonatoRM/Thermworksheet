<?php

namespace Api\Helpers;

use Api\Models\UsersModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use JetBrains\PhpStorm\NoReturn;

/**
 * Clase de Autenticación de usuarios
 */
class Services
{
    /**
     * Método que inicia la Autenticación
     * @return array
     */
    public static function login(): array
    {
        if($_SERVER['REQUEST_METHOD']==='OPTIONS') {
            die();
    }
        $hasToken=false;
        $ha=null;
        if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $arrayAuth=explode(' ',$_SERVER['HTTP_AUTHORIZATION']);
            if(count($arrayAuth)!==2) {
                self::unauthorizedAccess();
            }
            $typeAuth=$arrayAuth[0];
            $ha=$arrayAuth[1];
            if($typeAuth==='Basic') {
                $ha=base64_decode($ha);
                list($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])=explode(':',$ha);
            } else if($typeAuth==='Bearer') {
                $hasToken=true;
                self::decodeAuthToken($ha);
            } else {
                self::unauthorizedAccess();
            }
        }
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header("WWW-Authenticate: Basic realm='Private data'");
            header("HTTP/1.0 401 Unauthorized");
            die('Sorry. Incorrect Credentials');
        }
        $objUserModel = new UsersModel();
        $role = $objUserModel->authentication($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        if ($role === 0) {
            Logs::logger('Usuario no registrado', 'warning');
            header("HTTP/1.0 401 Unauthorized");
            die('Sorry. Incorrect Credentials');
        }
        if(!$hasToken) $authToken=self::getAuthToken();
        else $authToken=$ha;
        return array(
            'authToken'=>$authToken,
            'role'=>$role
        );
    }
    /**
     * Método que devuelve las cabeceras de CORS
     * @return void
     */
    public static function cors():void {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin,Authorization,X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Allow: GET, POST, PUT, DELETE");
    }

    /**
     * Método que crea un token válido
     * @return string
     */
    private static function getAuthToken():string {
        $payload=array(
            'iss'=>$_ENV['URL_API'],
            'aud'=>$_ENV['URL_FRONT'],
            'iat'=>strtotime('now'),
            'nbf'=>strtotime('now'),
            'exp'=>strtotime('now')+$_ENV['AUTH_TOKEN_EXPIRATION'],
            'username'=>$_SERVER['PHP_AUTH_USER'],
            'password'=>$_SERVER['PHP_AUTH_PW']
        );
        return JWT::encode($payload,$_ENV['AUTH_KEY'],'HS256');
    }
    private static function decodeAuthToken($authToken):void {
        $decode=JWT::decode($authToken,new Key($_ENV['AUTH_KEY'],'HS256'));
        if($decode->exp<strtotime('now')) self::unauthorizedAccess();
        $_SERVER['PHP_AUTH_USER']=$decode->username;
        $_SERVER['PHP_AUTH_PW']=$decode->password;
    }
    /**
     * Método que lanza un error 404 si no está definido el Controller
     * @return void
     */
    #[NoReturn] public static function undefinedController(): void
    {
        Logs::logger('Acceso a controlador erróneo', 'warning');
        header("HTTP/1.0 404 Not Found");
        die('Sorry. Page not found');
    }
    /**
     * Método que lanza un error 404 si no está definido la Función a ejecutar
     * @return void
     */
    #[NoReturn] public static function undefinedFunction(): void
    {
        Logs::logger('Acceso a función errónea', 'warning');
        header("HTTP/1.0 404 Not Found");
        die('Sorry. Page not found');
    }
    /**
     * Método que lanza un error 404 si no está definido el Método a ejecutar
     * @return void
     */
    #[NoReturn] public static function undefinedMethod(): void
    {
        Logs::logger('Acceso a método erróneo', 'warning');
        header("HTTP/1.0 404 Not Found");
        die('Sorry. Page not found');
    }
    /**
     * Método que lanza un error 400 si no existe la acción
     * @return void
     */
    #[NoReturn] public static function actionMethod(): void
    {
        header("HTTP/1.0 400 Bad request");
        die('Sorry. This request is not available');
    }
    /**
     * Método que lanza un error 404 si el servicio es erróneo
     * @return void
     */
    #[NoReturn] public static function servicesMethod(): void
    {
        Logs::logger('Acceso a servicio erróneo', 'warning');
        header("HTTP/1.0 404 Not Found");
        die('Sorry. Service is not exists');
    }
    /**
     * Método que lanza un 201 si la operación es correcta
     * @return void
     */
    #[NoReturn] public static function insertionOK(): void
    {
        header("HTTP/1.0 201 Created");
        echo('Congratulations. The operation was successful');
    }
    /**
     * Método que lanza un error 400 si existe un error indefinido
     * @return void
     */
    #[NoReturn] public static function undefinedError(): void
    {
        header("HTTP/1.0 400 Bad request");
        die('Sorry. Wrong query');
    }
    /**
     * Método que lanza un error 401 si no está permitido el acceso
     * @return void
     */
    #[NoReturn] public static function unauthorizedAccess(): void
    {
        Logs::logger('Usuario no tiene acceso', 'warning');
        header("HTTP/1.0 401 Unauthorized");
        die('Sorry. Incorrect Credentials');
    }
}