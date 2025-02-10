<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=sms_db', 'root', '');

// Insertion d'un versement fournisseur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_versement'])) {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $lieu = $_POST['lieu'];
    $montant = $_POST['montant'];
    $date_versement = $_POST['date_versement'];
    $mode_paiement = $_POST['mode_paiement'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO versement_fournisseur (nom, telephone, lieu, montant, date_versement, mode_paiement, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $telephone, $lieu, $montant, $date_versement, $mode_paiement, $description]);
}

// Récupération des versements
$versements = $pdo->query("SELECT * FROM versement_fournisseur ORDER BY date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Versements Fournisseurs</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
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
        input, select, textarea {
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
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons button {
            background-color: #008CBA;
        }
        .action-buttons button:hover {
            background-color: #006f8e;
        }
        .finalized {
            background-color: #e8f5e9;
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Ajouter un Versement Fournisseur</h1>
    <form method="POST">
        <input type="text" name="nom" placeholder="Nom du fournisseur" required>
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="text" name="lieu" placeholder="Lieu" required>
        <input type="number" step="0.01" name="montant" placeholder="Montant" required>
        <input type="date" name="date_versement" placeholder="Date du versement" required>
        <select name="mode_paiement" required>
            <option value="">Mode de paiement</option>
            <option value="Espèces">Espèces</option>
            <option value="Chèque">Chèque</option>
            <option value="Virement">Virement</option>
            <option value="Autre">Autre</option>
        </select>
        <textarea name="description" placeholder="Description (facultatif)"></textarea>
        <button type="submit" name="add_versement">Ajouter Versement</button>
    </form>

    <h1>Liste des Versements</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Lieu</th>
                <th>Montant</th>
                <th>Date Versement</th>
                <th>Mode de Paiement</th>
                <th>Description</th>
                <th>Date Création</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($versements as $versement): ?>
                <tr>
                    <td><?= htmlspecialchars($versement['nom']); ?></td>
                    <td><?= htmlspecialchars($versement['telephone']); ?></td>
                    <td><?= htmlspecialchars($versement['lieu']); ?></td>
                    <td><?= number_format($versement['montant'], 2, ',', ' '); ?></td>
                    <td><?= htmlspecialchars($versement['date_versement']); ?></td>
                    <td><?= htmlspecialchars($versement['mode_paiement']); ?></td>
                    <td><?= htmlspecialchars($versement['description']); ?></td>
                    <td><?= htmlspecialchars($versement['date_creation']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
