<?php
require '../includes/db.php';
require '../includes/header.php';

$sql = "SELECT * FROM commandes";
$stmt = $pdo->query($sql);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Liste des Commandes</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Total (Kg)</th>
            <th>Montant Total (FCFA)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($commandes as $commande): ?>
            <tr>
                <td><?= $commande['id'] ?></td>
                <td><?= $commande['date_commande'] ?></td>
                <td><?= number_format($commande['total_kg'], 2) ?> kg</td>
                <td><?= number_format($commande['montant_total'], 2) ?> FCFA</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

