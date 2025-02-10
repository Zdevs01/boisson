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


<style>
@media print {
    .card {
        border: none;
        box-shadow: none;
    }
    .card-header {
        display: none;
    }
    .print-info {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .print-info .col-md-4 {
        font-weight: bold;
        display: inline-block;
        width: 32%; /* Ajustement pour avoir trois éléments alignés sur une ligne */
        padding-left: 15px;
    }
    .separator {
        display: block;
        width: 100%;
        border-top: 2px dashed black;
        margin-top: 20px;
        margin-bottom: 20px;
    }
}
</style>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Facture production - <?php echo $production_code ?></h4>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid" id="production-content">
            <!-- Information Alignée en une ligne -->
            <div class="row print-info" style="display: flex; justify-content: space-between; align-items: center;">
    <div class="col-md-4" style="flex: 1; text-align: left;">
        <label class="control-label text-info">Code Vente :</label>
        <span><?php echo isset($production_code) ? $production_code : '' ?></span>
    </div>
    <div class="col-md-4" style="flex: 1; text-align: center;">
        <label class="control-label text-info">Client :</label>
        <span><?php echo isset($client) ? $client : '' ?></span>
    </div>
    <div class="col-md-4" style="flex: 1; text-align: right;">
        <label class="control-label text-info">Téléphone :</label>
        <span><?php echo isset($num) ? $num : '' ?></span>
    </div>
</div>


            <h4 class="text-info">Détails des Articles</h4>
            <div class="container">
            <strong> 
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
                    <td class="py-1 px-2 text-center"></td>
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
                    <th class="text-right py-1 px-2 grand-total"></th>
                </tr>
                <tr class="signature-row">
                    <td colspan="3" class="signature-cell signature-container">
                        <span class="signature-label">Client:</span>
                        <div style="height: 60px; border-top: 1px solid #000;"></div>
                    </td>
                    <td colspan="3" class="signature-cell signature-container">
                        <span class="signature-label">Responsable:</span>
                        <div style="height: 60px; border-top: 1px solid #000;"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
        </strong> 
    </div>  

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
            <div class="row print-info" style="display: flex; justify-content: space-between; align-items: center;">
    <div class="col-md-4" style="flex: 1; text-align: left;">
        <label class="control-label text-info">Code Vente :</label>
        <span><?php echo isset($production_code) ? $production_code : '' ?></span>
    </div>
    <div class="col-md-4" style="flex: 1; text-align: center;">
        <label class="control-label text-info">Client :</label>
        <span><?php echo isset($client) ? $client : '' ?></span>
    </div>
    <div class="col-md-4" style="flex: 1; text-align: right;">
        <label class="control-label text-info">Téléphone :</label>
        <span><?php echo isset($num) ? $num : '' ?></span>
    </div>
</div>

            <div class="container">
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
                    <td class="py-1 px-2 text-center"></td>
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
                    <th class="text-right py-1 px-2 grand-total"></th>
                </tr>
                <tr class="signature-row">
                    <td colspan="3" class="signature-cell signature-container">
                        <span class="signature-label">Client:</span>
                        <div style="height: 60px; border-top: 1px solid #000;"></div>
                    </td>
                    <td colspan="3" class="signature-cell signature-container">
                        <span class="signature-label">Responsable:</span>
                        <div style="height: 60px; border-top: 1px solid #000;"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
        </div>
    </div>

    <div class="card-footer py-1 text-center" >
        <button class="btn btn-flat btn-success" type="button" id="print">Imprimer</button>
        <a class="btn btn-flat btn-primary" href="<?php echo base_url.'/admin?page=production/manage_sale&id='.(isset($id) ? $id : '') ?>">Modifier</a>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin?page=production' ?>">Retour à la liste</a>
    </div>
</div>
<script>
    $(function(){
        $('#print').click(function(){
            start_loader();
            var _el = $('<div>');
            var _head = $('head').clone();
            _head.find('title').text("Facture de Vente - Impression");

            // Create header section for print
            var p = $('#print_out').clone();
            p.find('tr.text-light').removeClass("text-light bg-navy");

            _el.append(_head);
            _el.append(
                '<div class="container" style="background:#808384FF;" >'+
                  '<div class="d-flex justify-content-center mb-4">'+
                    '<div class="col-2 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="120px" height="200px" />'+
                    '</div>'+
                    '<div class="col-8 text-center">'+
                      '<h4><strong>COMMERCE GENERAL-IMPORT-EXPORT-TRANSPORT </strong></h4>'+
                      '<p>Vente Céréales et Produits d\'Élevage</p>'+
                      '<hr style="border: 1px solid black;" />'+
                      '<p><strong>Tél:</strong> (237) 681063474 / 679596403</p>'+
                      '<p><strong> Situé à FOUGEROLE (Après Entrée AMITY)</strong> </p>'+
                      '<p><strong>N° cont:</strong> P0400173833275 | <strong>RCCM:</strong> RC/YAE/2022/A/2723</p>'+
                    '</div>'+
                    '<div class="col-2"></div>'+
                  '</div>'+
                  '<hr style="border-top: 2px solid black;"/>'+
                '</div>'
            );

            _el.append('<strong><div class="container mt-4">'+
                       '<p style="white-space: nowrap;">' + p.html() + '</p></div></strong> ');

            var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes");
            nw.document.write(_el.html());
            nw.document.close();
            setTimeout(() => {
                nw.print();
                setTimeout(() => {
                    nw.close();
                    end_loader();
                }, 200);
            }, 500);
        });
    });
</script>
