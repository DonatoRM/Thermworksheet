<?php

namespace Api\Models\Base;

use Api\Helpers\Logs;
use Api\Helpers\Services;
use PDO, PDOException;
use Models\Base\Connection;

class Orm extends Connection
{
    protected string $table;
    protected string $query = '';
    private int $role;
    private array $params;
    private array $attributes;
    /**
     * Constructor de la clase
     * @param string $table
     * @param array $params
     * @param array $attributes
     * @param int $role
     */
    public function __construct(string $table, array $params, array $attributes, int $role = 0)
    {
        parent::__construct();
        $this->table = $table;
        $this->role = $role;
        $this->params = $params;
        $this->attributes = $attributes;
    }
    /**
     * Método que compara la constante superglobal $_GET con el array params
     * y si existe algún parámetro que no esté contemplado, corta la ejecución del
     * programa
     * @return void
     */
    private function checkParamsOfSelect(): void
    {
        $count = 0;
        foreach ($_GET as $key => $value) {
            if ($count >= 3) {
                $index = in_array($key, $this->params);
                if ($index === false) Services::servicesMethod();
            }
            $count++;
        }
        if ((isset($_GET['page']) && !isset($_GET['limit'])) || (!isset($_GET['page']) && isset($_GET['limit']))) {
            Services::servicesMethod();
        }
    }
    /**
     * Método que devuelve los registros de una tabla según los parámetros pasados por GET
     * @return array|bool
     */
    public function select(): array|bool
    {
        if ($this->table === 'users' && $this->role !== 3) {
            Services::unauthorizedAccess();
        }
        if ($this->table === 'roles' && $this->role !== 3) {
            Services::unauthorizedAccess();
        }
        try {
            $this->checkParamsOfSelect(); //
            $page = null;
            $limit = 0;
            if (isset($_GET['page']) && isset($_GET['limit'])) {
                $page = intval($_GET['page']);
                unset($_GET['page']);
                $limit = intval($_GET['limit']);
                unset($_GET['limit']);
            }
            $query = "SELECT * FROM $this->table ";
            $queryCount = "SELECT COUNT(*) FROM $this->table ";
            if (count($_GET) > 3) {
                $query .= "WHERE ";
                $queryCount .= "WHERE ";
            }
            $noFirst = false;
            for ($i = 0; $i < count($this->params); $i++) {
                if ($noFirst && isset($_GET["{$this->params[$i]}"])) $query .= ' AND ';
                if (isset($_GET["{$this->params[$i]}"])) {
                    $query .= $this->params[$i] . "=:" . $this->params[$i];
                    $queryCount .= $this->params[$i] . "=:" . $this->params[$i];
                    $noFirst = true;
                }
            }
            if ($page !== null && $limit !== null) {
                $offset = ($page - 1) * $limit;
                $query .= " LIMIT $offset,$limit";
            }
            $stmt = $this->connection->prepare($query);
            foreach ($this->params as $param) {
                if (isset($_GET["$param"])) {
                    $stmt->bindValue(":$param", $_GET["$param"], PDO::PARAM_INT | PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //Se realiza la consulta de datos globales
            $stmt = $this->connection->prepare($queryCount);
            foreach ($this->params as $param) {
                if (isset($_GET["$param"])) {
                    $stmt->bindValue(":$param", $_GET["$param"], PDO::PARAM_INT | PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
            $rows = $res['COUNT(*)'];
            $stmt = null;
            if ($limit !== 0) $pages = ceil($rows / $limit);
            else $pages = 1;
            if ($page === null && $limit === 0) {
                $page = 1;
                $limit = $rows;
                $pages = 1;
            }
            return [
                'data' => $result,
                'page' => $page,
                'limit' => $limit,
                'pages' => $pages,
                'rows' => $rows
            ];
        } catch (PDOException $ex) {
            Logs::logger('Error al buscar en la BD', 'debug');
            die("Failed to search database. Message: " . $ex->getMessage());
        }
    }
    /**
     * Método que comprueba que todos los atributos pasamos por parámetro se corresponderían con todos los
     * campos que posee la tabla menos si ID
     * @param $dataObject
     * @return object|null
     */
    private function areTheAttributesValidForInsertion($dataObject): object|null
    {
        if (count(get_object_vars($dataObject)) !== count($this->attributes)) {
            Services::actionMethod();
        }
        $okAllAttributes = true;
        foreach ($this->attributes as $attribute) {
            if (!property_exists($dataObject, $attribute)) $okAllAttributes = false;
        }
        if (!$okAllAttributes) {
            Services::servicesMethod();
        }
        return $dataObject;
    }
    /**
     * Método que devuelve el Hash de una Cadena de caracteres
     * @param string $pass
     * @return string
     */
    private function getHashFormPassword(string $pass): string
    {
        return hash('sha256', $pass);
    }
    /**
     * Método que inserta dentro de cada una de las tablas, un objeto JSON pasado por POST
     * @return void
     */
    public function insert(): void
    {
        if ($this->role === 2) {
            Services::actionMethod();
        } else {
            if (($this->table === 'users' || $this->table === 'roles') && $this->role !== 3) {
                Services::actionMethod();
            } else {
                try {
                    if ($this->table === 'countries' || $this->table === 'provinces' || $this->table ===
                        'municipalities' || $this->table === 'elements') Services::actionMethod();
                    $JSONData = file_get_contents("php://input");
                    $dataObject = json_decode($JSONData);
                    $dataObject = $this->areTheAttributesValidForInsertion($dataObject);
                    if ($this->role === 3 && $this->table === 'users') {
                        if ($this->checkIfIdExists($dataObject)) Services::undefinedError();
                        $dataObject->password = $this->getHashFormPassword($dataObject->password);
                    }
                    $this->connection->beginTransaction();
                    $sql = "INSERT INTO $this->table(";
                    foreach ($dataObject as $key => $value) {
                        $sql .= "$key,";
                    }
                    $sql = trim($sql, ',');
                    $sql .= ") VALUES(";
                    foreach ($dataObject as $key => $value) {
                        $sql .= ":$key,";
                    }
                    $sql = trim($sql, ',');
                    $sql .= ")";
                    $stmt = $this->connection->prepare($sql);
                    foreach ($dataObject as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }
                    $stmt->execute();
                    $this->connection->commit();
                    Services::insertionOK();

                } catch (PDOException $ex) {
                    Logs::logger('Error al insertar en la BD', 'debug');
                    $this->connection->rollBack();
                    die("Failed to insert database record. Message: " . $ex->getMessage());
                }
            }
        }
    }
    /**
     * Método que verifica que los atributos son válidos para realizar una actualización de datos
     * @param $dataObject
     * @return object|null
     */
    private function areTheAttributesValidForUpdate($dataObject): object|null
    {
        $okAllAttributes = false;
        foreach ($this->attributes as $attribute) {
            if (property_exists($dataObject, $attribute)) $okAllAttributes = true;
        }
        if (!$this->checkIfIdExists($dataObject)) $okAllAttributes = false;
        if ($this->table === 'users' && $this->role === 3) {
            if (!property_exists($dataObject, 'username')) $okAllAttributes = false;
            $this->query = "SELECT * FROM users WHERE username=:username";
        } else {
            if (!property_exists($dataObject, 'id')) $okAllAttributes = false;
            $this->query = "SELECT * FROM $this->table WHERE id=:id";
        }
        if (!$okAllAttributes) {
            Services::servicesMethod();
        }
        return $dataObject;
    }
    /**
     * Método que actualiza un registro pasado en formato JSON por el método PUT
     * @return void
     */
    public function update(): void
    {
        if ($this->role === 2) {
            Services::actionMethod();
        } else {
            if (($this->table === 'users' || $this->table === 'roles') && $this->role !== 3) {
                Services::actionMethod();
            } else {
                try {
                    if ($this->table === 'countries' || $this->table === 'provinces' || $this->table ===
                        'municipalities' || $this->table === 'elements') Services::actionMethod();
                    $JSONData = file_get_contents("php://input");
                    $dataObject = json_decode($JSONData);
                    $dataObject = $this->areTheAttributesValidForUpdate($dataObject);
                    if ($this->role === 3 && $this->table === 'users') {
                        if (isset($dataObject->password)) $dataObject->password = $this->getHashFormPassword($dataObject->password);
                    }
                    $this->connection->beginTransaction();
                    $sql = "UPDATE $this->table SET ";
                    foreach ($dataObject as $key => $value) {
                        if ($key !== 'id' && $key !== 'username') $sql .= "$key=:$key,";
                    }
                    $sql = trim($sql, ',');
                    if ($this->table === 'users' && $this->role === 3) {
                        $sql .= " WHERE username=:username";
                    } else {
                        $sql .= " WHERE id=:id";
                    }
                    $stmt = $this->connection->prepare($sql);
                    foreach ($dataObject as $key => $value) {
                        $stmt->bindValue(":$key", "$value", PDO::PARAM_STR);
                    }
                    $stmt->execute();
                    $this->connection->commit();
                    Services::insertionOK();
                } catch (PDOException $ex) {
                    Logs::logger('Error al actualizar en la BD', 'debug');
                    $this->connection->rollBack();
                    die("Failed to update database record. Message: " . $ex->getMessage());
                }
            }
        }
    }
    /**
     * Método que elimina el registro correspondiente al ID de una tabla
     * @param string $id Identificador del registro
     * @return void
     */
    public function delete(): void
    {
        if ($this->role === 2) {
            Services::actionMethod();
        } else {
            if (($this->table === 'users' || $this->table === 'roles') && $this->role !== 3) {
                Services::actionMethod();
            } else {
                try {
                    if ($this->table === 'countries' || $this->table === 'provinces' || $this->table ===
                        'municipalities' || $this->table === 'elements') Services::actionMethod();
                    $JSONData = file_get_contents("php://input");
                    $dataObject = json_decode($JSONData);
                    if (count(get_object_vars($dataObject)) !== 1) {
                        Services::servicesMethod();
                    }
                    if (!$this->checkIfIdExists($dataObject)) Services::servicesMethod();
                    $param = '';
                    $value = '';
                    $this->connection->beginTransaction();
                    if ($this->table === 'users' && isset($dataObject->username)) {
                        $query = "DELETE FROM $this->table WHERE username=:username";
                        $param = ':username';
                        $value = $dataObject->username;
                    } else if ($this->table !== 'users') {
                        $query = "DELETE FROM $this->table WHERE id=:id";
                        $param = ':id';
                        $value = $dataObject->id;
                    } else {
                        $this->connection->rollBack();
                        Services::servicesMethod();
                    }
                    $stmt = $this->connection->prepare($query);

                    $stmt->bindValue("$param", $value, PDO::PARAM_STR);
                    $stmt->execute();
                    $this->connection->commit();
                    Services::insertionOK();
                } catch (PDOException $ex) {
                    $this->connection->rollBack();
                    Logs::logger('Error al borrar en la BD', 'debug');
                    die("Failed to clear database log. Message: " . $ex->getMessage());
                }
            }
        }
    }
    /**
     * Método que se usa para realizar la autenticación
     * @param string $user
     * @param string $pass
     * @return int
     */
    public function authentication(string $user, string $pass): int
    {
        $passHash = strtoupper(hash('sha256', $pass));
        try {
            $query = "SELECT rol FROM users WHERE username=:u AND password=:p";
            $stmt = $this->connection->prepare($query);
            $stmt->bindValue(':u', $user);
            $stmt->bindValue(':p', $passHash);
            $stmt->execute();
            if ($stmt->rowCount() == 0) return 0;
            $role = $stmt->fetch(PDO::FETCH_OBJ);
            $stmt = null;
            return $role->rol;
        } catch (PDOException $ex) {
            Logs::logger('Error al consultar en la BD', 'debug');
            die('The database query failed. Message: ' . $ex->getMessage());
        }
    }
    /**
     * Método que revisa si existe o no un Id
     * @param object $dataObject
     * @return bool
     */
    private function checkIfIdExists(object $dataObject): bool
    {
        $query = '';
        if ($this->query === '') $query = "SELECT * FROM $this->table";
        else $query = $this->query;
        $value = '';
        if ($this->table === 'users') {
            $query .= " WHERE username=:username";
            $value = $dataObject->username;
        } else {
            $query .= " WHERE " . $this->table . ".id=:id";
            $value = $dataObject->id;
        }
        try {
            $stmt = $this->connection->prepare($query);
            if ($this->table === 'users') {
                $stmt->bindValue(":username", $value, PDO::PARAM_STR);
            } else {
                $stmt->bindValue(":id", $value, PDO::PARAM_INT | PDO::PARAM_STR);
            }
            $stmt->execute();
            $res = true;
            if (count($stmt->fetchAll(PDO::FETCH_ASSOC)) === 0) $res = false;
            $stmt = null;
            return $res;
        } catch (PDOException $ex) {
            Logs::logger('Error indefinido en la BD', 'debug');
            Services::undefinedError();
        }
    }
}