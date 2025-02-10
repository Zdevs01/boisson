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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - <?php echo $production_code ?></title>
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
        }

        .card-header h4 {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            margin: 0;
        }

        .text-info {
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .grand-total {
            font-weight: bold;
            font-size: 1.2em;
        }

        /* Styles pour l'impression */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            .card-footer {
                display: none;
            }

            .table th {
                background-color: #ccc !important;
                color: black !important;
            }

            hr {
                border: 1px solid black;
            }

            .logo-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .logo-header img {
                max-width: 160px;
                height: auto;
            }

            .logo-header div {
                text-align: center;
                font-size: 12px;
                line-height: 1.5;
            }
        }
    </style>
</head>

<body>
    <div class="card card-outline card-primary">


        <div class="card-header">
            <h4>Registre des ventesFF - <?php echo $production_code ?></h4>
        </div>
        <div class="card-body" id="print_out">
            <div class="container-fluid">
                <div class="logo-header">
                    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Logo" />
                    <div>
                        <h4>COMMERCE GENERAL-IMPORT-EXPORT-TRANSPORT</h4>
                        <p>VENTE CEREALES ET PRODUITS D ELEVAGE</p>
                        <p>Tel:(237)681063474/690537380, Situé à FOUGEROLE (Après Entrée AMITY)</p>
                        <p>N° cont: P0400173833275, RCCM: RC/YAE/2022/A/2723</p>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Code Vente</label>
                        <div><?php echo isset($production_code) ? $production_code : '' ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="client" class="control-label text-info">Nom du client</label>
                            <div><?php echo isset($client) ? $client : '' ?></div>
                        </div>
                        <div class="form-group">
                            <label for="num" class="control-label text-info">Numéro de téléphone</label>
                            <div><?php echo isset($num) ? $num : '' ?></div>
                        </div>
                    </div>
                </div>

                <h4 class="text-info">Articles</h4>
                <table class="table table-striped table-bordered" id="list">
                    <thead>
                        <tr>
                            <th>Nombre de TONNE</th>
                            <th>Quantité</th>
                            <th>(SAC/KG)</th>
                            <th>Désignation</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $qry = $conn->query("SELECT s.*, i.name, i.description FROM `stock_list` s INNER JOIN item_list i ON s.item_id = i.id WHERE s.id IN ({$stock_ids})");
                        while($row = $qry->fetch_assoc()):
                            $total += $row['total'];
                        ?>
                        <tr>
                            <td><?php echo number_format($row['quantity']) ?></td>
                            <td><?php echo ($row['unit']) ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['description'] ?></td>
                            <td><?php echo number_format($row['price']) ?></td>
                            <td><?php echo number_format($row['total']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th colspan="2"><?php echo number_format($total, 2) ?> XFA</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">Montant en lettres</th>
                            <th colspan="2">________________________</th>
                        </tr>
                    </tfoot>
                </table>

                <div class="row">
                    <div class="col-md-6">
                        <label class="text-info">Remarques</label>
                        <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                    </div>
                </div>
            </div>
        </div>


        <br><br>




        <div class="card-header">
            <h4>Registre des ventes - <?php echo $production_code ?></h4>
        </div>
        <div class="card-body" id="print_out">
            <div class="container-fluid">
                <div class="logo-header">
                    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Logo" />
                    <div>
                        <h4>COMMERCE GENERAL-IMPORT-EXPORT-TRANSPORT</h4>
                        <p>VENTE CEREALES ET PRODUITS D ELEVAGE</p>
                        <p>Tel:(237)681063474/690537380, Situé à FOUGEROLE (Après Entrée AMITY)</p>
                        <p>N° cont: P0400173833275, RCCM: RC/YAE/2022/A/2723</p>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Code Vente</label>
                        <div><?php echo isset($production_code) ? $production_code : '' ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="client" class="control-label text-info">Nom du client</label>
                            <div><?php echo isset($client) ? $client : '' ?></div>
                        </div>
                        <div class="form-group">
                            <label for="num" class="control-label text-info">Numéro de téléphone</label>
                            <div><?php echo isset($num) ? $num : '' ?></div>
                        </div>
                    </div>
                </div>

                <h4 class="text-info">Articles</h4>
                <table class="table table-striped table-bordered" id="list">
                    <thead>
                        <tr>
                            <th>Nombre de TONNE</th>
                            <th>Quantité</th>
                            <th>(SAC/KG)</th>
                            <th>Désignation</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $qry = $conn->query("SELECT s.*, i.name, i.description FROM `stock_list` s INNER JOIN item_list i ON s.item_id = i.id WHERE s.id IN ({$stock_ids})");
                        while($row = $qry->fetch_assoc()):
                            $total += $row['total'];
                        ?>
                        <tr>
                            <td><?php echo number_format($row['quantity']) ?></td>
                            <td><?php echo ($row['unit']) ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['description'] ?></td>
                            <td><?php echo number_format($row['price']) ?></td>
                            <td><?php echo number_format($row['total']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th colspan="2"><?php echo number_format($total, 2) ?> XFA</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-right">Montant en lettres</th>
                            <th colspan="2">________________________</th>
                        </tr>
                    </tfoot>
                </table>

                <div class="row">
                    <div class="col-md-6">
                        <label class="text-info">Remarques</label>
                        <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                    </div>
                </div>
            </div>
        </div>




        
        <div class="card-footer text-center">
            <button class="btn btn-success" id="print">Imprimer</button>
            <a class="btn btn-primary" href="<?php echo base_url.'/admin?page=production/manage_sale&id='.(isset($id) ? $id : '') ?>">Modifier</a>
            <a class="btn btn-dark" href="<?php echo base_url.'/admin?page=production' ?>">Retour à la liste</a>
        </div>
    </div>

    <script>
        $(function(){
            $('#print').click(function(){
                var _el = $('<div>');
                var _head = $('head').clone();
                _head.find('title').text("Facture - Impression");
                var p = $('#print_out').clone();
                _el.append(_head);
                _el.append(p.html());
                var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes");
                nw.document.write(_el.html());
                nw.document.close();
                setTimeout(() => {
                    nw.print();
                    nw.close();
                }, 500);
            });
        });
    </script>
</body>

</html>
