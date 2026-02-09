<?php
date_default_timezone_set('Europe/Paris');

session_start();  

require_once 'classes/Database.php';
require_once 'classes/Client.php';
require_once 'classes/Booking.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Erreur : token CSRF invalide.');
    }
}

$errors = array();
$successMessage = '';

$pdo = Database::getInstance()->getConnection();

// recupérer liste hôtels 
$stmt = $pdo->query("SELECT id, nom FROM hotels");
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récuperer id hôtel 
$selectedHotelId = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $hotel_id = isset($_POST['hotel_id']) ? (int)$_POST['hotel_id'] : 0;
    $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : '';
    $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : '';
    $today = date('Y-m-d');

    // Validation 
   if ($nom === '') {
    $errors[] = "Le nom est obligatoire.";
} elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s'-]+$/", $nom)) {
    $errors[] = "Le nom ne doit contenir que des lettres, espaces, apostrophes ou tirets.";
}

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide.";
    }

    if ($date_debut < $today) {
        $errors[] = "La date de début doit être aujourd'hui ou dans le futur.";
    }

    if ($date_fin <= $date_debut) {
        $errors[] = "La date de fin doit être après la date de début.";
    }

    if ($hotel_id === 0) {
        $errors[] = "Veuillez sélectionner un hôtel.";
    }

    // Si pas erreur réservation
    if (empty($errors)) {
        $client = new Client($nom, $email);
        $client_id = $client->save();

        $booking = new Booking();
        $chambre_id = $booking->findFirstAvailableChambre($hotel_id, $date_debut, $date_fin);

        if ($chambre_id) {
            $success = $booking->save($client_id, $chambre_id, $date_debut, $date_fin);
            if ($success) {
                $successMessage = "Réservation confirmée pour la chambre numéro $chambre_id.";
                // reset variables 
                $nom = $email = $date_debut = $date_fin = '';
                $hotel_id = 0;
            } else {
                $errors[] = "Erreur lors de la réservation.";
            }
        } else {
            $errors[] = "Désolé, aucune chambre disponible pour cette période.";
        }
    }
} else {
    // initialiser 
    $nom = '';
    $email = '';
    $date_debut = '';
    $date_fin = '';
    $hotel_id = $selectedHotelId; // preremplissage
}

// token unique pour empêcher envoie malveillant formulaire
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation d'hôtel</title>
<link rel="stylesheet" href="style.css">
<script>                    
// validation coté client - vérif données avant soumission formulaire
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(e) {
        const nom = this.nom.value.trim();
        const email = this.email.value.trim();
        const dateDebut = this.date_debut.value;
        const dateFin = this.date_fin.value;

        let errors = [];

        if (!nom) {
            errors.push("Le nom est obligatoire.");
        }

        // modele recherche email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errors.push("L'email est invalide.");
        }

        if (!dateDebut) {
            errors.push("La date de début est obligatoire.");
        }

        if (!dateFin) {
            errors.push("La date de fin est obligatoire.");
        }

        if (dateDebut && dateFin && dateFin <= dateDebut) {
            errors.push("La date de fin doit être après la date de début.");
        }

        if (errors.length > 0) {
            e.preventDefault(); // Empêche l'envoi
            alert(errors.join("\n"));
        }
    });
});
</script>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h1>Réserver une chambre</h1>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($successMessage !== ''): ?>
    <p class="success"><?php echo htmlspecialchars($successMessage); ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Nom :
        <input type="text" name="nom" required value="<?php echo htmlspecialchars($nom); ?>">
    </label>

    <label>Email :
        <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
    </label>

    <label>Hôtel :
        <select name="hotel_id" required>
            <option value="">-- Choisissez un hôtel --</option>
            <?php foreach ($hotels as $hotel): ?>
                <option value="<?php echo $hotel['id']; ?>" <?php echo ($hotel['id'] == $hotel_id) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($hotel['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Date début :
        <input type="date" name="date_debut" required value="<?php echo htmlspecialchars($date_debut); ?>">
    </label>

    <label>Date fin :
        <input type="date" name="date_fin" required value="<?php echo htmlspecialchars($date_fin); ?>">
    </label>
<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    <button type="submit">Réserver</button>
</form>
    </div>
</body>
</html>