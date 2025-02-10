<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=sms_db', 'root', '');

// Variables pour les messages
$message = '';
$message_type = ''; // success ou danger

$versements = [];

// Insertion d'un versement bancaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_versement'])) {
    $nom_banque = $_POST['nom_banque'];
    $numero_compte = $_POST['numero_compte'];
    $montant = $_POST['montant'];
    $date_versement = $_POST['date_versement'];
    $mode_paiement = $_POST['mode_paiement'];
    $description = $_POST['description'];

    try {
        $stmt = $pdo->prepare("INSERT INTO versement_bancaire (nom_banque, numero_compte, montant, date_versement, mode_paiement, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom_banque, $numero_compte, $montant, $date_versement, $mode_paiement, $description]);

        // Si l'insertion est réussie
        $message = "Le versement bancaire a été enregistré avec succès.";
        $message_type = 'success';
    } catch (Exception $e) {
        // Si une erreur survient
        $message = "Erreur lors de l'enregistrement du versement bancaire : " . $e->getMessage();
        $message_type = 'danger';
    }
}

// Récupération des versements bancaires
$stmt = $pdo->query("SELECT * FROM versement_bancaire ORDER BY date_creation DESC");
if ($stmt) {
    $versements = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Versements Bancaires</title>
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
    <h1 class="text-center text-primary mb-4">Ajouter un Versement Bancaire</h1>
    <form method="POST" class="mb-5">
        <div class="mb-3">
            <input type="text" name="nom_banque" class="form-control" placeholder="Nom de la Banque" required>
        </div>
        <div class="mb-3">
            <input type="text" name="numero_compte" class="form-control" placeholder="Numéro de Compte Bancaire" required>
        </div>
        <div class="mb-3">
            <input type="number" step="0.01" name="montant" class="form-control" placeholder="Montant" required>
        </div>
        <div class="mb-3">
            <input type="date" name="date_versement" class="form-control" placeholder="Date du Versement" required>
        </div>
        <div class="mb-3">
            <select name="mode_paiement" class="form-control" required>
                <option value="">Mode de Paiement</option>
                <option value="Chèque">Chèque</option>
                <option value="Virement">Virement</option>
                <option value="Espèces">Espèces</option>
                <option value="Autre">Autre</option>
            </select>
        </div>
        <div class="mb-3">
            <textarea name="description" class="form-control" placeholder="Description (facultatif)"></textarea>
        </div>
        <button type="submit" name="add_versement" class="btn btn-primary w-100">Ajouter Versement</button>
    </form>

    <!-- Tableau des versements -->
    <h1 class="text-center text-primary mb-4">Liste des Versements Bancaires</h1>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nom Banque</th>
                <th>Numéro Compte</th>
                <th>Montant</th>
                <th>Date Versement</th>
                <th>Mode Paiement</th>
                <th>Description</th>
                <th>Date Création</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($versements)): ?>
                <?php $counter = 1; ?>
                <?php foreach ($versements as $versement): ?>
                    <tr>
                        <td><?= $counter++; ?></td>
                        <td><?= htmlspecialchars($versement['nom_banque']); ?></td>
                        <td><?= htmlspecialchars($versement['numero_compte']); ?></td>
                        <td><?= number_format($versement['montant'], 2, ',', ' '); ?> XAF</td>
                        <td><?= htmlspecialchars($versement['date_versement']); ?></td>
                        <td><?= htmlspecialchars($versement['mode_paiement']); ?></td>
                        <td><?= htmlspecialchars($versement['description']); ?></td>
                        <td><?= htmlspecialchars($versement['date_creation']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Aucun versement bancaire enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Totaux par banque -->
    <div class="tablet">
        <h2 class="text-center text-success">Totaux Bancaires par Banque</h2>
        <div id="totals"></div>
    </div>

    <!-- Graphique des versements -->
    <div class="chart-container mt-5">
        <h2 class="text-center text-info">Graphique des Versements par Mode de Paiement</h2>
        <canvas id="paymentChart"></canvas>
    </div>
</div>

<script>
    const versements = <?= json_encode($versements); ?>;

    // Totaux par banque
    function updateTotals() {
        const totals = {};
        versements.forEach(v => {
            totals[v.nom_banque] = (totals[v.nom_banque] || 0) + parseFloat(v.montant);
        });

        const totalsContainer = document.getElementById('totals');
        totalsContainer.innerHTML = '';
        Object.keys(totals).forEach(bank => {
            totalsContainer.innerHTML += `
                <div class="month">
                    <span>${bank}</span>
                    <span class="total">${totals[bank].toLocaleString('fr-FR', { style: 'currency', currency: 'XAF' })}</span>
                </div>`;
        });
    }

    // Graphique des modes de paiement
    function loadChart() {
        const paymentData = {};
        versements.forEach(v => {
            paymentData[v.mode_paiement] = (paymentData[v.mode_paiement] || 0) + parseFloat(v.montant);
        });

        const labels = Object.keys(paymentData);
        const data = Object.values(paymentData);

        const ctx = document.getElementById('paymentChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Montant par Mode de Paiement (XAF)',
                    data: data,
                    backgroundColor: ['#4CAF50', '#FF9800', '#2196F3', '#9C27B0'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true
            }
        });
    }

    updateTotals();
    loadChart();
</script>
</body>
</html>
