<?php
require_once 'classes/Database.php';

$pdo = Database::getInstance()->getConnection();

// récupérer hôtels
$stmt = $pdo->query("SELECT * FROM hotels");
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des hôtels</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'menu.php'; ?>

<h1>Liste des hôtels</h1>

<?php foreach ($hotels as $hotel): ?>
    <div class="hotel">
    <h2><?= htmlspecialchars($hotel['nom']) ?></h2>
    <p><?= htmlspecialchars($hotel['adresse']) ?></p>

    <?php
    // Récupérer chambres 
    require_once 'classes/Chambre.php';
        $chambres = Chambre::getByHotelId($hotel['id']);
    ?>

    <ul>
        <?php foreach ($chambres as $chambre): ?>
            <li>Chambre n° <?= htmlspecialchars($chambre['numero_chambre']) ?></li>
        <?php endforeach; ?>
    </ul>

    <a href="reservation.php?hotel_id=<?= $hotel['id'] ?>">Réserver dans cet hôtel</a>
    <hr>
    </div>
<?php endforeach; ?>

</body>
</html>