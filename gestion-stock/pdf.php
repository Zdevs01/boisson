<?php
require_once("php/Class/Sale.php");
if (isset($_GET['num_com'])) {
    extract($_GET);
    $sale = Sale::displaySaleWithPr($num_com);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - Entrepôt de Boissons</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .content {
            width: 750px;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 120px;
        }

        .header h2 {
            color: #333;
            font-size: 24px;
        }

        .main p {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
        }

        .right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border-bottom: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .total-row {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="header">
            <img src="assets/img/logo.png" alt="Logo de l'entreprise">
            <h2>Facture</h2>
        </div>
        <div class="main">
            <p>
                Bonjour, <?= $sale[0]['nom'] . " " . $sale[0]['prenom'] ?>.<br>
                Merci pour votre commande chez nous.
            </p>
            <p class="right">
                <strong>Référence :</strong> <?= $sale[0]['num_com'] ?><br>
                <strong>Date :</strong> <?= date("d-m-Y", strtotime($sale[0]['date_com'])) ?>
            </p>
        </div>

        <table>
            <tr>
                <th>Produit</th>
                <th>Prix Unitaire (€)</th>
                <th>Quantité</th>
                <th>Total (€)</th>
            </tr>
            <?php
            $somme = 0;
            foreach ($sale as $item):
                $totalLigne = $item['qte_pr'] * $item['prix_vente'];
                $somme += $totalLigne;
            ?>
            <tr>
                <td><?= $item['lib_pr'] ?></td>
                <td><?= number_format($item['prix_vente'], 2, ',', ' ') ?> €</td>
                <td><?= $item['qte_pr'] ?></td>
                <td><?= number_format($totalLigne, 2, ',', ' ') ?> €</td>
            </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="3">Total Général</td>
                <td><?= number_format($somme, 2, ',', ' ') ?> €</td>
            </tr>
        </table>

        <div class="footer">
            <p>Merci pour votre confiance. À bientôt !</p>
        </div>
    </div>
</body>

</html>
