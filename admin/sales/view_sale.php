<?php
// Vérifier si une session est déjà démarrée avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$qry = $conn->query("SELECT * FROM sales_list r WHERE id = '{$_GET['id']}'");
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
        <h4 class="card-title">Facture de Vente - <?php echo $sales_code ?></h4>
    </div>
    <div class="card-body" id="print_out">
    <div class="container-fluid">
    <div class="row print-info" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="col-md-4" style="flex: 1; text-align: left;">
            <label class="control-label text-info">Code Vente :</label>
            <span><?php echo isset($sales_code) ? $sales_code : '' ?></span>
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
                    <th class="text-center py-1 px-2" >TONNE</th>
                    <th class="text-center py-1 px-2" >Quantité</th>
                    <th class="text-center py-1 px-2" >(Littre)</th>
                    <th class="text-center py-1 px-2" >Désignation</th>
                    <th class="text-center py-1 px-2" >Prix Unitaire</th>
                    <th class="text-center py-1 px-2" >Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                $qry = $conn->query("SELECT s.*, i.name, i.description FROM stock_list s INNER JOIN item_list i ON s.item_id = i.id WHERE s.id IN ({$stock_ids})");
                while($row = $qry->fetch_assoc()):
                    // Calcul de la quantité affichée en fonction de l'unité
                    $quantity = ($row['unit'] == 'kg') ? $row['quantity2'] : $row['quantity'];
                    $total_row = $quantity * $row['price'];
                    $total += $total_row;
                ?>
                <tr>
                    <td class="py-1 px-2  text-center" ></td>
                    <td class="py-1 px-2  text-center" ><b><?php echo number_format($quantity) ?></td>
                    <td class="py-1 px-2  text-center" ><b><?php echo ($row['unit']) ?></b></td>
                    <td class="py-1 px-2" >
                       <b> <?php echo $row['name'] ?></b> <br>
                        <?php echo $row['description'] ?></b>
                    </td>
                    <td class="py-1 px-2  text-right"><b><?php echo number_format($row['price']) ?></b></td>
                    <td class="py-1 px-2  text-right"><b><?php echo number_format($total_row) ?></b></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right py-1 px-2"  colspan="5">Total</th>
                    <th class="text-right py-1 px-2  grand-total"><?php echo number_format($total, 2) ?> XFA</th>
                </tr>
                <tr>
                    <th class="text-right py-1 px-2"  colspan="5">Montant en lettres</th>
                    <th class="text-right py-1 px-2  grand-total"></th>
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
        <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="text-info control-label">REMARQUE(S)</label>
                        <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                    </div>
                </div>
            </div>
    </div>
</div>


        <!-- Separator for cutting -->
        <div class="separator" style="border-top: 2px dashed black; margin: 20px 0;"></div>

        <!-- Duplicate content for business record -->
        <div class="container-fluid">
    <div class="row print-info" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="col-md-4" style="flex: 1; text-align: left;">
            <label class="control-label text-info">Code Vente :</label>
            <span><?php echo isset($sales_code) ? $sales_code : '' ?></span>
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
                    <th class="text-center py-1 px-2" >TONNE</th>
                    <th class="text-center py-1 px-2" >Quantité</th>
                    <th class="text-center py-1 px-2" >(Littre)</th>
                    <th class="text-center py-1 px-2" >Désignation</th>
                    <th class="text-center py-1 px-2" >Prix Unitaire</th>
                    <th class="text-center py-1 px-2" >Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0;
                $qry = $conn->query("SELECT s.*, i.name, i.description FROM stock_list s INNER JOIN item_list i ON s.item_id = i.id WHERE s.id IN ({$stock_ids})");
                while($row = $qry->fetch_assoc()):
                    // Calcul de la quantité affichée en fonction de l'unité
                    $quantity = ($row['unit'] == 'kg') ? $row['quantity2'] : $row['quantity'];
                    $total_row = $quantity * $row['price'];
                    $total += $total_row;
                ?>
                <tr>
                    <td class="py-1 px-2  text-center" ></td>
                    <td class="py-1 px-2  text-center" ><b><?php echo number_format($quantity) ?></b></td>
                    <td class="py-1 px-2  text-center" ><b><?php echo ($row['unit']) ?></b></td>
                    <td class="py-1 px-2" >
                       <b> <?php echo $row['name'] ?></b>
                       
                    </td>
                    <td class="py-1 px-2  text-right"><b><?php echo number_format($row['price']) ?></b></td>
                    <td class="py-1 px-2  text-right"><b><?php echo number_format($total_row) ?></b></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right py-1 px-2"  colspan="5">Total</th>
                    <th class="text-right py-1 px-2  grand-total"><?php echo number_format($total, 2) ?> XFA</th>
                </tr>
                <tr>
                    <th class="text-right py-1 px-2"  colspan="5">Montant en lettres</th>
                    <th class="text-right py-1 px-2  grand-total"></th>
                </tr>
                <tr class="signature-row">
                    <td colspan="3" class="signature-cell signature-container">
                        <span class="signature-label"><b>Client:</b></span>
                        <div style="height: 60px; border-top: 1px solid #000;"></div>
                    </td>
                    <td colspan="3" class="signature-cell signature-container">
                        <span class="signature-label"><b>Responsable:</b></span>
                        <div style="height: 60px; border-top: 1px solid #000;"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="text-info control-label"><b>REMARQUE(S)</b></label>
                        <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                    </div>
                </div>
            </div>
    </div>
</div>

    </div>

    <div class="card-footer py-1 text-center" >
        <button class="btn btn-flat btn-success" type="button" id="print">Imprimer</button>
        <a class="btn btn-flat btn-primary" href="<?php echo base_url.'/admin?page=sales/manage_sale&id='.(isset($id) ? $id : '') ?>">Modifier</a>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin?page=sales' ?>">Retour à la liste</a>
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
                      '<h4><strong>Application de gestion d’entrepôt </strong></h4>'+
                      '<p>.........................</p>'+
                      '<hr style="border: 1px solid black;" />'+
                      '<p><strong>Tél:</strong>.................</p>'+
                      '<p><strong> Situé .......................</strong> </p>'+
                      '<p><strong> </strong> </strong> </p>'+
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
