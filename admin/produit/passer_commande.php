<?php
require 'includes/db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produits_commandes = $_POST['produits'];
    $total_kg = 0;
    $montant_total = 0;

    foreach ($produits_commandes as $produit) {
        $id_produit = intval($produit['id']);
        $quantite_kg = floatval($produit['quantite_kg']);
        $prix_unitaire = floatval($produit['prix_unitaire']);

        $stmt = $pdo->prepare("SELECT quantite_kg FROM produits WHERE id = :id");
        $stmt->execute(['id' => $id_produit]);
        $stock = $stmt->fetchColumn();

        if ($quantite_kg > $stock) {
            $message = "Erreur : La quantité demandée dépasse le stock disponible.";
            $message_type = 'danger';
            break;
        }

        $nouveau_stock = $stock - $quantite_kg;
        $stmt = $pdo->prepare("UPDATE produits SET quantite_kg = :nouveau_stock WHERE id = :id");
        $stmt->execute(['nouveau_stock' => $nouveau_stock, 'id' => $id_produit]);

        $sous_total = $quantite_kg * $prix_unitaire;
        $montant_total += $sous_total;
        $total_kg += $quantite_kg;
    }

    if (!isset($message)) {
        $stmt = $pdo->prepare("INSERT INTO commandes (total_kg, montant_total) VALUES (:total_kg, :montant_total)");
        $stmt->execute(['total_kg' => $total_kg, 'montant_total' => $montant_total]);

        $message = "Commande enregistrée avec succès.";
        $message_type = 'success';
    }
}

$produits = $pdo->query("SELECT * FROM produits")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-title {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }
        .table thead {
            background-color: #007bff;
            color: #fff;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <!-- Message d'alerte -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message_type; ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <h1 class="form-title text-center my-4">Passer une Commande</h1>
    <form action="" method="POST" class="card p-4">
        <?php foreach ($produits as $produit): ?>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"><?= htmlspecialchars($produit['nom']); ?> (Stock : <?= number_format($produit['quantite_kg'], 2); ?> kg)</label>
                <div class="col-sm-4">
                    <input type="hidden" name="produits[<?= $produit['id'] ?>][id]" value="<?= $produit['id'] ?>">
                    <input type="number" step="0.01" min="0" max="<?= $produit['quantite_kg']; ?>" 
                           name="produits[<?= $produit['id'] ?>][quantite_kg]" 
                           class="form-control" placeholder="Quantité (kg)">
                </div>
                <div class="col-sm-4">
                    <input type="number" step="0.01" name="produits[<?= $produit['id'] ?>][prix_unitaire]" 
                           class="form-control" value="<?= $produit['prix_vente'] ?>" readonly>
                </div>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary w-100 mt-3">Valider la Commande</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
