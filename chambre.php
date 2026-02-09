<?php
require_once 'Database.php';

class Chambre {
    private $pdo;
    public $id;
    public $hotel_id;
    public $numero_chambre;

    public function __construct($hotel_id, $numero_chambre, $id = null) {
        $this->pdo = Database::getInstance()->getConnection();
        $this->hotel_id = $hotel_id;
        $this->numero_chambre = $numero_chambre;
        $this->id = $id;
    }

    public function save() {
        if ($this->id) {
            // Ici tu pourrais faire un update si tu veux gérer la modif
            return $this->id;
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO chambres (hotel_id, numero_chambre) VALUES (?, ?)");
            $stmt->execute([$this->hotel_id, $this->numero_chambre]);
            $this->id = $this->pdo->lastInsertId();
            return $this->id;
        }
    }

    // méthode pour récupérer les chambres par hotel
    public static function getByHotelId($hotel_id) {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM chambres WHERE hotel_id = ?");
        $stmt->execute([$hotel_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}