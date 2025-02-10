<?php
// Vérifier si une session est déjà démarrée avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$qry = $conn->query("SELECT * FROM production_list r WHERE id = '{$_GET['id']}'");
if($qry->num_rows > 0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture de Vente - <?php echo $production_code ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            background-color: #fff;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            font-size: 18px;
        }
        .card-body {
            margin: 20px 0;
        }
        .container-fluid {
            padding: 0;
        }
        .row {
            margin: 0;
        }
        .text-info {
            color: #17a2b8;
        }
        .text-light {
            color: #f8f9fa;
        }
        .bg-navy {
            background-color: #003366;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #000;
        }
        .text-right {
            text-align: right;
        }
        .grand-total {
            font-weight: bold;
        }
        .separator {
            border-top: 2px dashed black;
            margin: 20px 0;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-success {
            background-color: #28a745;
            color: #fff;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }
        .btn-dark {
            background-color: #343a40;
            color: #fff;
        }
        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }
        .header-logo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header-logo img {
            max-height: 60px;
        }
        .header-info {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Facture de Vente - <?php echo $production_code ?></h4>
    </div>
    <div class="card-body" id="print_out">
        <!-- En-tête avec Logo et Informations -->
        <div class="header-logo">
            <img src="path/to/your/logo.png" alt="Logo">
            <div class="header-info">
                <h3>Nom de l'Entreprise</h3>
                <p>Adresse de l'Entreprise</p>
                <p>Téléphone : 123-456-789</p>
                <p>Email : info@entreprise.com</p>
            </div>
        </div>
        <div class="container-fluid" id="sales-content">
            <!-- Information Alignée en une ligne -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label class="control-label text-info">Code Vente :</label>
                    <span><?php echo isset($production_code) ? $production_code : '' ?></span>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-info">Client :</label>
                    <span><?php echo isset($client) ? $client : '' ?></span>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-info">Téléphone :</label>
                    <span><?php echo isset($num) ? $num : '' ?></span>
                </div>
            </div>
            <h4 class="text-info">Détails des Articles</h4>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="30%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2">TONNE</th>
                        <th class="text-center py-1 px-2">Quantité</th>
                        <th class="text-center py-1 px-2">(SAC/KG)</th>
                        <th class="text-center py-1 px-2">Désignation</th>
                        <th class="text-center py-1 px-2">Prix Unitaire</th>
                        <th class="text-center py-1 px-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    $qry = $conn->query("SELECT s.*, i.name, i.description FROM stock_list s INNER JOIN item_list i ON s.item_id = i.id WHERE s.id IN ({$stock_ids})");
                    while($row = $qry->fetch_assoc()):
                        $total += $row['total'];
                    ?>
                    <tr>
                        <td class="py-1 px-2 text-center">________________________</td>
                        <td class="py-1 px-2 text-center"><?php echo number_format($row['quantity']) ?></td>
                        <td class="py-1 px-2 text-center"><?php echo ($row['unit']) ?></td>
                        <td class="py-1 px-2">
                            <?php echo $row['name'] ?> <br>
                            <?php echo $row['description'] ?>
                        </td>
                        <td class="py-1 px-2 text-right"><?php echo number_format($row['price']) ?></td>
                        <td class="py-1 px-2 text-right"><?php echo number_format($row['total']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Total</th>
                        <th class="text-right py-1 px-2 grand-total"><?php echo number_format($total, 2) ?> XFA</th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Montant en lettres</th>
                        <th class="text-right py-1 px-2 grand-total">________________________</th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Responsable:</th>
                        <th class="text-right py-1 px-2 grand-total">________________________</th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Client:</th>
                        <th class="text-right py-1 px-2 grand-total">________________________</th>
                    </tr>
                </tfoot>
            </table>

            <!-- Remarques section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <label for="remarks" class="text-info control-label">Remarques :</label>
                    <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                </div>
            </div>
        </div>

        <!-- Separator for cutting -->
        <div class="separator" style="border-top: 2px dashed black; margin: 20px 0;"></div>

        <!-- Duplicate content for business record -->
        <div class="container-fluid">
            <!-- Duplicate invoice for company records -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label class="control-label text-info">Code Vente :</label>
                    <span><?php echo isset($production_code) ? $production_code : '' ?></span>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-info">Client :</label>
                    <span><?php echo isset($client) ? $client : '' ?></span>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-info">Téléphone :</label>
                    <span><?php echo isset($num) ? $num : '' ?></span>
                </div>
            </div>
            <h4 class="text-info">Détails des Articles</h4>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="30%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2">TONNE</th>
                        <th class="text-center py-1 px-2">Quantité</th>
                        <th class="text-center py-1 px-2">(SAC/KG)</th>
                        <th class="text-center py-1 px-2">Désignation</th>
                        <th class="text-center py-1 px-2">Prix Unitaire</th>
                        <th class="text-center py-1 px-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    $qry = $conn->query("SELECT s.*, i.name, i.description FROM stock_list s INNER JOIN item_list i ON s.item_id = i.id WHERE s.id IN ({$stock_ids})");
                    while($row = $qry->fetch_assoc()):
                        $total += $row['total'];
                    ?>
                    <tr>
                        <td class="py-1 px-2 text-center">________________________</td>
                        <td class="py-1 px-2 text-center"><?php echo number_format($row['quantity']) ?></td>
                        <td class="py-1 px-2 text-center"><?php echo ($row['unit']) ?></td>
                        <td class="py-1 px-2">
                            <?php echo $row['name'] ?> <br>
                            <?php echo $row['description'] ?>
                        </td>
                        <td class="py-1 px-2 text-right"><?php echo number_format($row['price']) ?></td>
                        <td class="py-1 px-2 text-right"><?php echo number_format($row['total']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Total</th>
                        <th class="text-right py-1 px-2 grand-total"><?php echo number_format($total, 2) ?> XFA</th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Montant en lettres</th>
                        <th class="text-right py-1 px-2 grand-total">________________________</th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Responsable:</th>
                        <th class="text-right py-1 px-2 grand-total">________________________</th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Client:</th>
                        <th class="text-right py-1 px-2 grand-total">________________________</th>
                    </tr>
                </tfoot>
            </table>
            <div class="row mt-4">
                <div class="col-md-12">
                    <label for="remarks" class="text-info control-label">Remarques :</label>
                    <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Boutons d'action -->
    <div class="card-footer">
        <button class="btn btn-success" onclick="printInvoice()">Imprimer</button>
        <a class="btn btn-primary" href="edit_invoice.php?id=<?php echo $production_code ?>">Modifier</a>
        <a class="btn btn-danger" href="delete_invoice.php?id=<?php echo $production_code ?>">Supprimer</a>
        <a class="btn btn-dark" href="production_list.php">Retour</a>
    </div>
</div>

<script>
function printInvoice() {
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Imprimer la Facture</title>');
    printWindow.document.write('</head><body >');
    printWindow.document.write(document.getElementById('print_out').innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>
</body>
</html>
