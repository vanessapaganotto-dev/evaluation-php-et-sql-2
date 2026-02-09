<?php
class Database {
    private $host = 'localhost';
    private $db = 'booking_db';
    private $user = 'root';
    private $pass = 'Ubuntu1412';
    private $pdo;
    private static $instance;

    private function __construct() {       // Création objet PDO
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8", 
                                 $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getInstance() {     // singleton
        if(!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}