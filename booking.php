<?php
class Booking {
    private $pdo;
    public $client_id;
    public $chambre_id;
    public $date_debut;
    public $date_fin;
    public $date_creation;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->date_creation = date('Y-m-d H:i:s');
    }

    // verifie si chambre dispo
    public function isChambreDispo($chambre_id, $date_debut, $date_fin) {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE chambre_id = ? AND NOT (date_fin <= ? OR date_debut >= ?)");
        $stmt->execute([$chambre_id, $date_debut, $date_fin]);
        return $stmt->rowCount() === 0; // dispo si pas de conflit
    }

    // trouve 1ere chambre dispo 
    public function findFirstAvailableChambre($hotel_id, $date_debut, $date_fin) {
        $stmt = $this->pdo->prepare("SELECT id FROM chambres WHERE hotel_id = ?");
        $stmt->execute([$hotel_id]);
        $chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($chambres as $chambre) {
            if ($this->isChambreDispo($chambre['id'], $date_debut, $date_fin)) {
                return $chambre['id'];
            }
        }
        return null; // aucune dispo
    }

    // Enregistre 
    public function save($client_id, $chambre_id, $date_debut, $date_fin) {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (client_id, chambre_id, date_debut, date_fin, date_creation) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$client_id, $chambre_id, $date_debut, $date_fin, $this->date_creation]);
    }
}