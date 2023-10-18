<?php

namespace Models\Base;

use Api\Helpers\Logs;
use PDO, PDOException;

abstract class Connection
{
    protected PDO $connection;
    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DBNAME'] . ";port=" . $_ENV['DB_PORT'] . ";charset=" . $_ENV['DB_CHARSET'];
        try {
            $this->connection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            Logs::logger('Error al conectar con la BD', 'debug');
            die('Error al conectar con la BD. Mensaje: ' . $ex->getMessage());
        }
    }
}