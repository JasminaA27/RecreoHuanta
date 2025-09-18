<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . BD_HOST . ";dbname=" . BD_NAME . ";charset=" . BD_CHARSET;
            $this->pdo = new PDO($dsn, BD_USER, BD_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Método para ejecutar consultas SELECT
    public function select($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en select: " . $e->getMessage());
            return false;
        }
    }

    // Método para ejecutar consultas SELECT que devuelven un solo registro
    public function selectOne($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en selectOne: " . $e->getMessage());
            return false;
        }
    }

    // Método para ejecutar INSERT, UPDATE, DELETE
    public function execute($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error en execute: " . $e->getMessage());
            return false;
        }
    }

    // Método para obtener el último ID insertado
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    // Método para contar registros
    public function count($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en count: " . $e->getMessage());
            return 0;
        }
    }
}
?>