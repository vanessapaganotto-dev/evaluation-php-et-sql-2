<?php
class Client {
    private $pdo;
    private $id;
    public $nom;
    public $email;

    public function __construct($nom, $email) {
        $this->pdo = Database::getInstance()->getConnection();
        $this->nom = $nom;
        $this->email = $email;
    }

    public function save() {
        // Vérifier si client existe 
        $stmt = $this->pdo->prepare("SELECT id FROM clients WHERE email = ?");
        $stmt->execute([$this->email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($client) {
            $this->id = $client['id'];
            return $this->id;
        }

        // Sinon insérer
        $stmt = $this->pdo->prepare("INSERT INTO clients (nom, email) VALUES (?, ?)");
        $stmt->execute([$this->nom, $this->email]);
        $this->id = $this->pdo->lastInsertId();
        return $this->id;
    }
}