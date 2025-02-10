<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sms_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insérer des données à partir du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom_personne = $_POST['nom_personne'];
        $numero_telephone = $_POST['numero_telephone'];
        $motif = $_POST['motif'];
        $somme = $_POST['somme'];
        $avance = $_POST['avance'];
        $reste = $somme - $avance;
        $date_prevue_livraison = $_POST['date_prevue_livraison'];
        $livre = 0;

        $stmt = $conn->prepare("INSERT INTO approvisionnement (nom_personne, numero_telephone, motif, somme, reste, date_prevue_livraison, livre)
                                VALUES (:nom_personne, :numero_telephone, :motif, :somme, :reste, :date_prevue_livraison, :livre)");
        $stmt->execute(compact('nom_personne', 'numero_telephone', 'motif', 'somme', 'reste', 'date_prevue_livraison', 'livre'));
    }

    // Marquer comme livrée
    if (isset($_GET['livre_id'])) {
        $livre_id = $_GET['livre_id'];
        $stmt = $conn->prepare("UPDATE approvisionnement SET livre = 1 WHERE id = :id");
        $stmt->execute(['id' => $livre_id]);
    }

    // Récupérer les approvisionnements
    $approvisionnements = $conn->query("SELECT * FROM approvisionnement ORDER BY date_prevue_livraison ASC")->fetchAll();
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$conn = null;

// Fonction pour le décompte des jours
function calculateCountdown($dateLivraison) {
    $now = new DateTime();
    $dateLivraison = new DateTime($dateLivraison);
    $interval = $now->diff($dateLivraison);
    return $interval->invert ? "Livraison due" : $interval->days . " jours restants";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Approvisionnements</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            width: 90%;
            margin: auto;
        }
        .form-container, .table-container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        button {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f0f0f0;
            text-align: left;
        }
        .livree {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Formulaire -->
    <div class="form-container">
        <h2>Ajouter un Approvisionnement</h2>
        <form method="POST">
            <label for="nom_personne">Nom</label>
            <input type="text" name="nom_personne" id="nom_personne" required>

            <label for="numero_telephone">Téléphone</label>
            <input type="text" name="numero_telephone" id="numero_telephone" required pattern="6[0-9]{8}" title="Doit commencer par 6 et contenir 9 chiffres">

            <label for="motif">Motif</label>
            <input type="text" name="motif" id="motif" required>

            <label for="somme">Somme Totale (FCFA)</label>
            <input type="number" name="somme" id="somme" step="0.01" required>

            <label for="avance">Avance Versée (FCFA)</label>
            <input type="number" name="avance" id="avance" step="0.01" required oninput="calculateRemaining()">

            <label for="reste">Reste à Payer (FCFA)</label>
            <input type="number" name="reste" id="reste" step="0.01" readonly>

            <label for="date_prevue_livraison">Date Prévue de Livraison</label>
            <input type="date" name="date_prevue_livraison" id="date_prevue_livraison" required>

            <button type="submit">Enregistrer</button>
        </form>
    </div>

    <!-- Tableau -->
    <h2>Tableau des Approvisionnements</h2>
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Motif</th>
                <th>Somme (FCFA)</th>
                <th>Avance (FCFA)</th>
                <th>Reste (FCFA)</th>
                <th>Date Livraison</th>
                <th>Décompte</th>
                <th>Livraison</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($approvisionnements as $appro): ?>
                <tr class="<?= $appro['livre'] ? 'livree' : ''; ?>">
                    <td><?= htmlspecialchars($appro['nom_personne']); ?></td>
                    <td><?= htmlspecialchars($appro['numero_telephone']); ?></td>
                    <td><?= htmlspecialchars($appro['motif']); ?></td>
                    <td><?= number_format($appro['somme'], 2); ?> FCFA</td>
                    <td><?= number_format($appro['somme'] - $appro['reste'], 2); ?> FCFA</td>
                    <td><?= number_format($appro['reste'], 2); ?> FCFA</td>
                    <td><?= htmlspecialchars($appro['date_prevue_livraison']); ?></td>
                    <td><?= calculateCountdown($appro['date_prevue_livraison']); ?></td>
                    <td>
                        <?php if (!$appro['livre']): ?>
                            <button class="marquer-livree" data-id="<?= $appro['id']; ?>">Marquer comme livrée</button>
                        <?php else: ?>
                            <span class="livre"><i class="fas fa-check-circle"></i> Livrée</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function calculateRemaining() {
        let somme = parseFloat(document.getElementById('somme').value) || 0;
        let avance = parseFloat(document.getElementById('avance').value) || 0;
        document.getElementById('reste').value = (somme - avance).toFixed(2);
    }

    document.querySelectorAll('.marquer-livree').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            fetch(`?livre_id=${id}`).then(() => location.reload());
        });
    });
</script>
</body>
</html>

