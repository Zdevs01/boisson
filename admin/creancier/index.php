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
    <title>Gestion des Créanciers - Entrepôt de Boissons</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 30px;
        }
        h1 {
            text-align: center;
            color: #2C3E50;
            margin-bottom: 20px;
        }
        form {
            background-color: #ECF0F1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #BDC3C7;
            border-radius: 6px;
        }
        button {
            background-color: #2980B9;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #1F618D;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
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
        th {
            background-color: #3498DB;
            color: white;
        }
        .action-buttons button {
            margin: 5px;
            background-color: #2ECC71;
            border-radius: 6px;
        }
        .action-buttons button:hover {
            background-color: #27AE60;
        }
        .soldee {
            background-color: #2ECC71;
            color: white;
            font-weight: bold;
        }
        .en_attente {
            background-color: #E74C3C;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fas fa-warehouse"></i> Gestion des Créanciers</h1>
    <form method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="numero_telephone" placeholder="Numéro de téléphone" required>
        <input type="number" name="montant" placeholder="Montant total (€)" required>
        <input type="text" name="motif" placeholder="Motif" required>
        <textarea name="notes" placeholder="Notes (facultatif)"></textarea>
        <button type="submit" name="add_creancier"><i class="fas fa-plus"></i> Ajouter Créancier</button>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Montant (€)</th>
                    <th>Avance (€)</th>
                    <th>Reste (€)</th>
                    <th>Motif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamique PHP pour afficher les créanciers -->
          

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