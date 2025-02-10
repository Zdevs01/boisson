<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=sms_db', 'root', '');

// Insertion d'un créancier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_creancier'])) {
    $nom = $_POST['nom'];
    $numero_telephone = $_POST['numero_telephone'];
    $montant = $_POST['montant'];
    $motif = $_POST['motif'];
    $notes = $_POST['notes'];

    $stmt = $pdo->prepare("INSERT INTO creancier (nom, numero_telephone, montant, motif, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $numero_telephone, $montant, $motif, $notes]);
}

// Récupération des créanciers
$creanciers = $pdo->query("SELECT * FROM creancier")->fetchAll(PDO::FETCH_ASSOC);

// Action pour finaliser ou ajouter une avance
if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action == 'finalize') {
        // Finaliser la dette en la réglant
        $stmt = $pdo->prepare("UPDATE creancier SET avance = montant, notes = 'Dette solde' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action == 'avance' && isset($_POST['avance'])) {
        // Ajouter une avance
        $avance = $_POST['avance'];
        if ($avance > 0) {
            $stmt = $pdo->prepare("UPDATE creancier SET avance = avance + ? WHERE id = ?");
            $stmt->execute([$avance, $id]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Créanciers</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-top: 0;
            font-weight: 500;
        }
        form {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .action-buttons button {
            margin: 5px;
            background-color: #008CBA;
            border-radius: 6px;
        }
        .action-buttons button:hover {
            background-color: #006f8e;
        }

        /* Ajout de la couleur pour les créances finalisées */
        .soldee {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .en_attente {
            background-color: #ffcc00;
            color: white;
            font-weight: bold;
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Ajouter un Créancier</h1>

    <!-- Formulaire d'ajout de créancier -->
    <form method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="numero_telephone" placeholder="Numéro de téléphone" required>
        <input type="number" name="montant" placeholder="Montant total" required>
        <input type="text" name="motif" placeholder="Motif" required>
        <textarea name="notes" placeholder="Notes (facultatif)"></textarea>
        <button type="submit" name="add_creancier">Ajouter Créancier</button>
    </form>

    <!-- Tableau des créanciers -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Montant Total</th>
                    <th>Avance</th>
                    <th>Reste</th>
                    <th>Motif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($creanciers as $creancier): ?>
                <tr class="<?= ($creancier['montant'] - $creancier['avance'] == 0) ? 'soldee' : 'en_attente'; ?>">
                    <td><?= htmlspecialchars($creancier['nom']); ?></td>
                    <td><?= htmlspecialchars($creancier['numero_telephone']); ?></td>
                    <td><?= number_format($creancier['montant'], 2, ',', ' '); ?></td>
                    <td><?= number_format($creancier['avance'], 2, ',', ' '); ?></td>
                    <td><?= number_format($creancier['montant'] - $creancier['avance'], 2, ',', ' '); ?></td>
                    <td><?= htmlspecialchars($creancier['motif']); ?></td>
                    <td class="action-buttons">
                        <!-- Finaliser -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $creancier['id']; ?>">
                            <button type="submit" name="action" value="finalize" <?= ($creancier['montant'] - $creancier['avance'] == 0) ? 'disabled' : ''; ?>>Finaliser</button>
                        </form>
                        
                        <!-- Ajouter Avance -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $creancier['id']; ?>">
                            <input type="number" name="avance" placeholder="Montant Avance" required <?= ($creancier['montant'] - $creancier['avance'] == 0) ? 'disabled' : ''; ?>>
                            <button type="submit" name="action" value="avance" <?= ($creancier['montant'] - $creancier['avance'] == 0) ? 'disabled' : ''; ?>>Ajouter Avance</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
