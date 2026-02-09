<?php
require_once 'Database.php';

class Hotel {
    private $pdo;
    public $id;
    public $nom;
    public $adresse;

    public function __construct($nom, $adresse, $id = null) {
        $this->pdo = Database::getInstance()->getConnection();
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->id = $id;
    }

    public function save() {
        if ($this->id) {
            return $this->id;
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO hotels (nom, adresse) VALUES (?, ?)");
            $stmt->execute([$this->nom, $this->adresse]);
            $this->id = $this->pdo->lastInsertId();
            return $this->id;
        }
    }

    public static function getAll() {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->query("SELECT * FROM hotels");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}