<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Chauffeurs</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Styles généraux */
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
        h1, h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        /* Formulaire */
        .form-container {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .form-container label {
            font-weight: bold;
            margin: 10px 0;
            display: block;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container button:hover {
            background-color: #45a049;
        }

        /* Tableau */
        .table-container {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Animation d'apparition */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .container, .form-container, table {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Chauffeurs</h1>

        <!-- Formulaire d'enregistrement -->
        <div class="form-container">
            <form action="" method="POST">
                <label for="nom">Nom du Chauffeur</label>
                <input type="text" id="nom" name="nom" required>

                <label for="numero_telephone">Numéro de Téléphone</label>
                <input type="text" id="numero_telephone" name="numero_telephone" required pattern="6[0-9]{8}" title="Doit commencer par 6 et contenir 9 chiffres">

                <label for="deuxieme_numero">Deuxième Téléphone (optionnel)</label>
                <input type="text" id="deuxieme_numero" name="deuxieme_numero" pattern="6[0-9]{8}" title="Doit commencer par 6 et contenir 9 chiffres">

                <label for="type_engin">Type d'Engin</label>
                <input type="text" id="type_engin" name="type_engin" required>

                <label for="matricule_vehicule">Matricule du Véhicule</label>
                <input type="text" id="matricule_vehicule" name="matricule_vehicule" required>

                <button type="submit" name="submit">Enregistrer</button>
            </form>
        </div>

        <!-- Tableau des chauffeurs -->
        <h2>Liste des Chauffeurs</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Deuxième Téléphone</th>
                        <th>Type d'Engin</th>
                        <th>Matricule</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // Connexion à la base de données
                        $pdo = new PDO("mysql:host=localhost;dbname=sms_db", "root", "");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Insertion des données si le formulaire est soumis
                        if (isset($_POST['submit'])) {
                            $nom = $_POST['nom'];
                            $numero_telephone = $_POST['numero_telephone'];
                            $deuxieme_numero = $_POST['deuxieme_numero'] ?? null;
                            $type_engin = $_POST['type_engin'];
                            $matricule_vehicule = $_POST['matricule_vehicule'];

                            $sql = "INSERT INTO chauffeur (nom, numero_telephone, deuxieme_numero, type_engin, matricule_vehicule) 
                                    VALUES (?, ?, ?, ?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$nom, $numero_telephone, $deuxieme_numero, $type_engin, $matricule_vehicule]);

                            echo "<p style='color: green; text-align: center;'>Chauffeur enregistré avec succès !</p>";
                        }

                        // Récupération des données des chauffeurs
                        $stmt = $pdo->query("SELECT * FROM chauffeur");

                        foreach ($stmt as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_telephone']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['deuxieme_numero'] ?? 'N/A') . "</td>";
                            echo "<td>" . htmlspecialchars($row['type_engin']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['matricule_vehicule']) . "</td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<p style='color: red; text-align: center;'>Erreur : " . $e->getMessage() . "</p>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
