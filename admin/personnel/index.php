<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=sms_db', 'root', '');

// Initialisation des messages
$message = '';
$message_type = '';

// Gestion de l'ajout d'un employé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employe'])) {
    $nom_complet = trim($_POST['nom_complet']);
    $date_jour = $_POST['date_jour'];
    if (!empty($nom_complet)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO employes_presences (nom_complet, date_jour) 
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE nom_complet = VALUES(nom_complet)
            ");
            $stmt->execute([$nom_complet, $date_jour]);
            $message = "Employé ajouté pour le jour $date_jour.";
            $message_type = 'success';
        } catch (Exception $e) {
            $message = "Erreur lors de l'ajout : " . $e->getMessage();
            $message_type = 'danger';
        }
    } else {
        $message = "Le nom complet est obligatoire.";
        $message_type = 'danger';
    }
}

// Gestion de la mise à jour des présences
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_presence'])) {
    $presence_id = $_POST['update_presence'];
    $heure_arrivee = $_POST['heure_arrivee'][$presence_id] ?? null;
    $heure_depart = $_POST['heure_depart'][$presence_id] ?? null;
    $heure_depart_pause = $_POST['heure_depart_pause'][$presence_id] ?? null;
    $heure_retour_pause = $_POST['heure_retour_pause'][$presence_id] ?? null;

    // Calcul des statuts
    $retard = (strtotime($heure_arrivee) > strtotime('08:30:00') && strtotime($heure_arrivee) <= strtotime('11:00:00')) ? 'oui' : 'non';
    $absence = strtotime($heure_arrivee) > strtotime('11:00:00') ? 'oui' : 'non';
    $pause_longue = (strtotime($heure_retour_pause) - strtotime($heure_depart_pause)) > 5400 ? 'oui' : 'non'; // Pause > 1h30
    $heures_supp = (strtotime($heure_depart) > strtotime('18:30:00')) ? (strtotime($heure_depart) - strtotime('18:30:00')) / 3600 : 0;

    try {
        $stmt = $pdo->prepare("
            UPDATE employes_presences 
            SET heure_arrivee = ?, heure_depart = ?, heure_depart_pause = ?, heure_retour_pause = ?, retard = ?, absence = ?, pause_longue = ?, heures_supp = ?
            WHERE id = ?
        ");
        $stmt->execute([$heure_arrivee, $heure_depart, $heure_depart_pause, $heure_retour_pause, $retard, $absence, $pause_longue, $heures_supp, $presence_id]);
        $message = "Présence mise à jour avec succès.";
        $message_type = 'success';
    } catch (Exception $e) {
        $message = "Erreur lors de la mise à jour : " . $e->getMessage();
        $message_type = 'danger';
    }
}

// Filtrer par date
$date_filter = $_GET['date_filter'] ?? date('Y-m-d');

// Récupérer les présences du jour
$presences = $pdo->prepare("
    SELECT * FROM employes_presences WHERE date_jour = ?
");
$presences->execute([$date_filter]);
$presences = $presences->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les statistiques globales
$stats = $pdo->prepare("
    SELECT nom_complet,
           SUM(retard = 'oui') AS total_retards,
           SUM(absence = 'oui') AS total_absences,
           SUM(pause_longue = 'oui') AS total_pauses_longues,
           SUM(heures_supp) AS total_heures_supp
    FROM employes_presences
    WHERE date_jour = ?
    GROUP BY nom_complet
");
$stats->execute([$date_filter]);
$stats = $stats->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Présence</title>
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        /* Formulaires */
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-container h2 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            background: #4CAF50;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background: #45a049;
        }

        /* Tableaux */
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .table-container h2 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Messages */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Message -->
    <?php if (isset($message) && $message): ?>
        <div class="alert alert-<?= $message_type; ?>"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <div class="form-container">
        <h2>Ajouter un Employé</h2>
        <form method="POST">
            <input type="text" name="nom_complet" placeholder="Nom Complet" required>
            <input type="date" name="date_jour" value="<?= date('Y-m-d'); ?>" required>
            <button type="submit" name="add_employe">Ajouter</button>
        </form>
    </div>

    <!-- Présences -->
    <div class="table-container">
        <h2>Présences du <?= htmlspecialchars($date_filter); ?></h2>
        <form method="POST">
            <table>
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Pause Départ</th>
                    <th>Pause Retour</th>
                    <th>Retard</th>
                    <th>Absence</th>
                    <th>Pause Longue</th>
                    <th>Heures Supp.</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($presences as $presence): ?>
                    <tr>
                        <td><?= htmlspecialchars($presence['nom_complet']); ?></td>
                        <td><input type="time" name="heure_arrivee[<?= $presence['id']; ?>]" value="<?= $presence['heure_arrivee']; ?>"></td>
                        <td><input type="time" name="heure_depart[<?= $presence['id']; ?>]" value="<?= $presence['heure_depart']; ?>"></td>
                        <td><input type="time" name="heure_depart_pause[<?= $presence['id']; ?>]" value="<?= $presence['heure_depart_pause']; ?>"></td>
                        <td><input type="time" name="heure_retour_pause[<?= $presence['id']; ?>]" value="<?= $presence['heure_retour_pause']; ?>"></td>
                        <td><?= $presence['retard'] === 'oui' ? 'Oui' : 'Non'; ?></td>
                        <td><?= $presence['absence'] === 'oui' ? 'Oui' : 'Non'; ?></td>
                        <td><?= $presence['pause_longue'] === 'oui' ? 'Oui' : 'Non'; ?></td>
                        <td><?= number_format($presence['heures_supp'], 2); ?> h</td>
                        <td><button type="submit" name="update_presence" value="<?= $presence['id']; ?>">Mettre à jour</button></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>

    <!-- Statistiques -->
    <div class="table-container">
    <h2>Statistiques Globales - <?= htmlspecialchars($date_filter); ?></h2>
    <form method="GET">
        <!-- Ajout du paramètre `page` dans le formulaire -->
        <input type="hidden" name="page" value="personnel">
        <input type="date" name="date_filter" value="<?= htmlspecialchars($date_filter); ?>">
        <button type="submit">Changer de jour</button>
    </form>
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Total Retards</th>
            <th>Total Absences</th>
            <th>Total Pauses Longues</th>
            <th>Total Heures Supp.</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($stats as $stat): ?>
            <tr>
                <td><?= htmlspecialchars($stat['nom_complet']); ?></td>
                <td><?= $stat['total_retards']; ?></td>
                <td><?= $stat['total_absences']; ?></td>
                <td><?= $stat['total_pauses_longues']; ?></td>
                <td><?= number_format($stat['total_heures_supp'], 2); ?> h</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function (event) {
                const inputs = form.querySelectorAll('input[type="time"]');
                let valid = true;

                inputs.forEach(input => {
                    if (input.value === '') {
                        valid = false;
                    }
                });

                if (!valid) {
                    alert("Veuillez renseigner toutes les heures avant de soumettre.");
                    event.preventDefault();
                }
            });
        });
    });
</script>
</body>
</html>
