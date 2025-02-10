<?php
require 'includes/db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $poids_sac = floatval($_POST['poids_sac']);
    $quantite_kg = floatval($_POST['quantite_kg']);
    $prix_achat = floatval($_POST['prix_achat']);
    $prix_vente = floatval($_POST['prix_vente']);

    if ($nom === '' || $poids_sac <= 0 || $quantite_kg < 0 || $prix_achat <= 0 || $prix_vente <= 0) {
        $message = "Erreur : Veuillez remplir tous les champs correctement.";
        $message_type = 'danger';
    } else {
        $sql = "INSERT INTO produits (nom, poids_sac, quantite_kg, prix_achat, prix_vente)
                VALUES (:nom, :poids_sac, :quantite_kg, :prix_achat, :prix_vente)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'poids_sac' => $poids_sac,
            'quantite_kg' => $quantite_kg,
            'prix_achat' => $prix_achat,
            'prix_vente' => $prix_vente
        ]);

        $message = "Produit ajouté avec succès.";
        $message_type = 'success';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit</title>
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
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
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
    <h1 class="form-title text-center my-4">Ajouter un Produit</h1>
    <form action="" method="POST" class="card p-4">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom du Produit :</label>
            <input type="text" id="nom" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="poids_sac" class="form-label">Poids d'un Sac (kg) :</label>
            <input type="number" step="0.01" id="poids_sac" name="poids_sac" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="quantite_kg" class="form-label">Quantité en Stock (kg) :</label>
            <input type="number" step="0.01" id="quantite_kg" name="quantite_kg" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="prix_achat" class="form-label">Prix d'Achat (FCFA) :</label>
            <input type="number" step="0.01" id="prix_achat" name="prix_achat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="prix_vente" class="form-label">Prix de Vente (FCFA) :</label>
            <input type="number" step="0.01" id="prix_vente" name="prix_vente" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Ajouter le Produit</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
