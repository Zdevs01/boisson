<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Versements Journaliers</title>
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <script src="js/chart.min.js"></script>
    <style>
        @font-face {
            font-family: 'Roboto';
            src: url('fonts/roboto-regular.woff2') format('woff2'),
                 url('fonts/roboto-regular.woff') format('woff');
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 30px;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input, textarea {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        button {
            background: #4CAF50;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .description-cell {
            max-width: 250px;
            word-wrap: break-word;
        }
        .chart-container {
            margin-top: 40px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .tablet {
            margin-top: 40px;
            background: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .tablet h2 {
            text-align: center;
            color: #333;
            font-size: 20px;
        }
        .tablet .month {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .month span {
            font-size: 16px;
            color: #555;
        }
        .month .total {
            font-weight: bold;
            color: #4CAF50;
            font-size: 18px;
        }
    </style>
</head>
<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=boisson', 'root', '');

// Variables pour les messages
$message = '';
$message_type = ''; // success ou danger

$versements = [];

// Insertion d'un versement journalier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_versement'])) {
    $montant_total = $_POST['montant_total'];
    $recouvrement = !empty($_POST['recouvrement']) ? $_POST['recouvrement'] : 0;
    $approvisionnement = !empty($_POST['approvisionnement']) ? $_POST['approvisionnement'] : 0;
    $depense = !empty($_POST['depense']) ? $_POST['depense'] : 0;
    $nombre_transactions = $_POST['nombre_transactions'];
    $date_versement = $_POST['date_versement'];
    $details = $_POST['details'];
    $responsable = $_POST['responsable'];

    try {
        $stmt = $pdo->prepare("INSERT INTO versement_journalier (montant_total, recouvrement, approvisionnement, depense, nombre_transactions, date_versement, details, responsable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$montant_total, $recouvrement, $approvisionnement, $depense, $nombre_transactions, $date_versement, $details, $responsable]);

        $message = "Le versement a été enregistré avec succès.";
        $message_type = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de l'enregistrement du versement : " . $e->getMessage();
        $message_type = 'danger';
    }
}

// Récupération des versements journaliers
$stmt = $pdo->query("SELECT * FROM versement_journalier ORDER BY date_creation DESC");
if ($stmt) {
    $versements = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calcul des totaux des versements et des 10% par mois
$totals_by_month = [];
foreach ($versements as $versement) {
    $month = date('Y-m', strtotime($versement['date_versement'])); // Groupement par mois
    if (!isset($totals_by_month[$month])) {
        $totals_by_month[$month] = ['total' => 0, 'percent_10' => 0];
    }
    // Ajout des montants au total du mois
    $totals_by_month[$month]['total'] += floatval($versement['montant_total']);
    // Calcul des 10%
    $totals_by_month[$month]['percent_10'] = $totals_by_month[$month]['total'] * 0.10;
}

?>


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
    <h1 class="text-center text-success mb-4"><i class="fas fa-plus-circle"></i> Ajouter un Versement</h1>
    <form method="POST">
        <div class="mb-3">
            <input type="number" step="0.01" name="montant_total" class="form-control" placeholder="Montant Total" required>
        </div>
        <div class="mb-3">
            <input type="number" step="0.01" name="recouvrement" class="form-control" placeholder="Montant Recouvré (optionnel)">
        </div>
        <div class="mb-3">
            <input type="number" step="0.01" name="approvisionnement" class="form-control" placeholder="Montant Approvisionné (optionnel)">
        </div>
        <div class="mb-3">
            <input type="number" step="0.01" name="depense" class="form-control" placeholder="Montant depensé (optionnel)">
        </div>
        <div class="mb-3">
            <input type="number" name="nombre_transactions" class="form-control" placeholder="Nombre de Transactions" required>
        </div>
        <div class="mb-3">
            <input type="date" name="date_versement" class="form-control" placeholder="Date du Versement" required>
        </div>
        <div class="mb-3">
            <textarea name="details" class="form-control" placeholder="Détails ou Notes (facultatif)"></textarea>
        </div>
        <div class="mb-3">
            <input type="text" name="responsable" class="form-control" placeholder="Responsable" required>
        </div>
        <button type="submit" name="add_versement" class="btn btn-success w-100">Ajouter Versement</button>
    </form>
</div>


    <h1><i class="fas fa-list-alt icon"></i> Liste des Versements Journaliers</h1>
    <table class="table table-striped table-hover">
    <thead class="table-success">
        <tr>
            <th>#</th> <!-- Colonne pour le décompte -->
            <th>Montant Total</th>
            <th>Recouvrement</th>
            <th>Approvisionnement</th>
            <th>Depense</th>
            <th>Transactions</th>
            <th>Date Versement</th>
            <th>Détails</th>
            <th>Responsable</th>
            <th>Date Création</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($versements)): ?>
        <?php $counter = 1; // Compteur pour chaque ligne ?>
        <?php foreach ($versements as $versement): ?>
            <tr>
                <td><?= $counter++; ?></td> <!-- Incrémentation du compteur -->
                <td><?= number_format($versement['montant_total'], 2, ',', ' '); ?> XAF</td>
                <td><?= number_format($versement['recouvrement'], 2, ',', ' '); ?> XAF</td>
                <td><?= number_format($versement['approvisionnement'], 2, ',', ' '); ?> XAF</td>
                <td><?= number_format($versement['depense'], 2, ',', ' '); ?> XAF</td>
                <td><?= htmlspecialchars($versement['nombre_transactions']); ?></td>
                <td><?= htmlspecialchars($versement['date_versement']); ?></td>
                <td><?= htmlspecialchars($versement['details']); ?></td>
                <td><?= htmlspecialchars($versement['responsable']); ?></td>
                <td><?= htmlspecialchars($versement['date_creation']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="9" class="text-center">Aucun versement enregistré.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>


</div>
<div class="container">
  

    <div class="chart-container">
        <h1><i class="fas fa-chart-bar"></i> Historique des Versements</h1>
        <canvas id="paymentChart"></canvas>
        <p id="no-data" style="text-align: center; color: #888; display: none;">Aucun versement enregistré ou connexion Internet indisponible.</p>
    </div>

    <div class="tablet">
    <h2>Totaux des Versements par Mois</h2>
    <div id="totals">
        <?php foreach ($totals_by_month as $month => $data): ?>
            <div class="month">
                <span><strong><?= date('F Y', strtotime($month . '-01')); ?></strong></span>
                <span class="total">Total: <?= number_format($data['total'], 0, ',', ' '); ?> XAF</span>
                <span class="percent-10">10%: <?= number_format($data['percent_10'], 0, ',', ' '); ?> XAF</span>
            </div>
        <?php endforeach; ?>
    </div>
</div>



</div>
<style>


.month {
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    display: flex;
    justify-content: space-between;
    font-size: 16px;
}
.month span {
    font-weight: bold;
}







    .tablet .month {
    margin: 10px 0;
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}
   
.tablet .month span {
    font-size: 16px;
    color: #555;
}

.tablet .month .total {
    font-weight: bold;
    color: #4CAF50;
}

.tablet .month .percent-10 {
    font-weight: bold;
    color: #FF5722; /* Couleur différente pour les 10% */
}

</style>
<script>
    const versements = <?= json_encode($versements); ?>;

    function updateTotals() {
        const totals = {};
        versements.forEach(v => {
            const month = new Date(v.date_versement).toLocaleString('fr-FR', { month: 'long', year: 'numeric' });
            totals[month] = (totals[month] || 0) + parseFloat(v.montant_total);
        });

        const totalsContainer = document.getElementById('totals');
        totalsContainer.innerHTML = '';
        Object.keys(totals).forEach(month => {
            totalsContainer.innerHTML += `
                <div class="month">
                    <span>${month}</span>
                    <span class="total">${totals[month].toLocaleString('fr-FR', { style: 'currency', currency: 'XAF', minimumFractionDigits: 0 })}</span>
                </div>`;
        });
    }

    function loadChart() {
        if (navigator.onLine && versements.length > 0) {
            const monthlyData = {};
            versements.forEach(v => {
                const month = new Date(v.date_versement).toLocaleString('fr-FR', { month: 'short', year: 'numeric' });
                monthlyData[month] = (monthlyData[month] || 0) + parseFloat(v.montant_total);
            });

            const labels = Object.keys(monthlyData);
            const data = Object.values(monthlyData);

            const ctx = document.getElementById('paymentChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Montant Total (XAF)',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Montant : ${context.raw.toLocaleString('fr-FR', { style: 'currency', currency: 'XAF', minimumFractionDigits: 0 })}`;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            document.getElementById('no-data').style.display = 'block';
        }
    }

    updateTotals();
    loadChart();
</script>

</body>
</html>