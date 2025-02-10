<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=sms_db', 'root', '');

// Variables pour les messages
$message = '';
$message_type = ''; // success ou danger

// Insertion d'un bordereau de livraison
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_bordereau'])) {
    $nombre_produits = $_POST['nombre_produits'];
    $tonnage = $_POST['tonnage'];
    $montant_total = $_POST['montant_total'];
    $nom_fournisseur = $_POST['nom_fournisseur'];
    $telephone_fournisseur = $_POST['telephone_fournisseur'];
    $adresse_fournisseur = $_POST['adresse_fournisseur'];
    $date_livraison = $_POST['date_livraison'];
    $details = $_POST['details'];
    $reference_bordereau = $_POST['reference_bordereau'];

    try {
        $stmt = $pdo->prepare("INSERT INTO bordereau_livraison (nombre_produits, tonnage, montant_total, nom_fournisseur, telephone_fournisseur, adresse_fournisseur, date_livraison, details, reference_bordereau) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre_produits, $tonnage, $montant_total, $nom_fournisseur, $telephone_fournisseur, $adresse_fournisseur, $date_livraison, $details, $reference_bordereau]);

        $message = "Le bordereau de livraison a été enregistré avec succès.";
        $message_type = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
        $message_type = 'danger';
    }
}

// Récupération des bordereaux de livraison
$bordereaux = $pdo->query("SELECT * FROM bordereau_livraison ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Bordereaux de Livraison</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container my-4">
    <!-- Message d'alerte -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message_type; ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <h1 class="text-center text-primary mb-4">Ajouter un Bordereau de Livraison</h1>
    <form method="POST" class="mb-5">
        <div class="mb-3">
            <input type="number" name="nombre_produits" class="form-control" placeholder="Nombre de Produits" required>
        </div>
        <div class="mb-3">
            <input type="number" step="0.01" name="tonnage" class="form-control" placeholder="Tonnage (en tonnes)" required>
        </div>
        <div class="mb-3">
            <input type="number" step="0.01" name="montant_total" class="form-control" placeholder="Montant Total (FCFA)" required>
        </div>
        <div class="mb-3">
            <input type="text" name="nom_fournisseur" class="form-control" placeholder="Nom du Fournisseur" required>
        </div>
        <div class="mb-3">
            <input type="text" name="telephone_fournisseur" class="form-control" placeholder="Téléphone du Fournisseur" required>
        </div>
        <div class="mb-3">
            <input type="text" name="adresse_fournisseur" class="form-control" placeholder="Adresse du Fournisseur">
        </div>
        <div class="mb-3">
            <input type="date" name="date_livraison" class="form-control" required>
        </div>
        <div class="mb-3">
            <textarea name="details" class="form-control" placeholder="Détails ou Remarques"></textarea>
        </div>
        <div class="mb-3">
            <input type="text" name="reference_bordereau" class="form-control" placeholder="Référence du Bordereau">
        </div>
        <button type="submit" name="add_bordereau" class="btn btn-primary w-100">Ajouter Bordereau</button>
    </form>

    <!-- Tableau des bordereaux -->
    <h1 class="text-center text-primary mb-4">Liste des Bordereaux de Livraison</h1>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre Produits</th>
                <th>Tonnage</th>
                <th>Montant Total</th>
                <th>Nom Fournisseur</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Date Livraison</th>
                <th>Détails</th>
                <th>Référence</th>
                <th>Date Création</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bordereaux)): ?>
                <?php $counter = 1; ?>
                <?php foreach ($bordereaux as $bordereau): ?>
                    <tr>
                        <td><?= $counter++; ?></td>
                        <td><?= htmlspecialchars($bordereau['nombre_produits']); ?></td>
                        <td><?= number_format($bordereau['tonnage'], 2, ',', ' '); ?> T</td>
                        <td><?= number_format($bordereau['montant_total'], 2, ',', ' '); ?> FCFA</td>
                        <td><?= htmlspecialchars($bordereau['nom_fournisseur']); ?></td>
                        <td><?= htmlspecialchars($bordereau['telephone_fournisseur']); ?></td>
                        <td><?= htmlspecialchars($bordereau['adresse_fournisseur']); ?></td>
                        <td><?= htmlspecialchars($bordereau['date_livraison']); ?></td>
                        <td><?= htmlspecialchars($bordereau['details']); ?></td>
                        <td><?= htmlspecialchars($bordereau['reference_bordereau']); ?></td>
                        <td><?= htmlspecialchars($bordereau['date_creation']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center">Aucun bordereau enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Graphique des bordereaux -->
    <div class="chart-container mt-5">
        <h2 class="text-center text-info">Graphique des Bordereaux par Fournisseur</h2>
        <canvas id="bordereauChart"></canvas>
    </div>
</div>

<script>
    const bordereaux = <?= json_encode($bordereaux); ?>;

    // Graphique des fournisseurs
    function loadChart() {
        const fournisseurData = {};
        bordereaux.forEach(b => {
            fournisseurData[b.nom_fournisseur] = (fournisseurData[b.nom_fournisseur] || 0) + parseFloat(b.montant_total);
        });

        const labels = Object.keys(fournisseurData);
        const data = Object.values(fournisseurData);

        const ctx = document.getElementById('bordereauChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Montant Total (FCFA)',
                    data: data,
                    backgroundColor: '#4CAF50',
                    borderColor: '#388E3C',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    loadChart();
</script>
</body>
</html>
