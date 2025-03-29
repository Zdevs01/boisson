<?php 
$qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id  where p.id = '{$_GET['id']}'");
if($qry->num_rows > 0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>


<style>
    .card-body {
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.print-info {
    border-bottom: 1px solid #ddd;
    margin-bottom: 15px;
    padding-bottom: 15px;
}

.print-info .control-label {
    font-weight: bold;
}

.print-info span {
    font-size: 16px;
    color: #333;
}

</style>




<div class="card card-outline card-primary">
<div class="card-header">
    <h4 class="card-title">Détails de la Commande - <?php echo $po_code ?></h4>
</div>
<div class="card-body" id="print_out">
    <div class="container-fluid" id="sales-content">
        <!-- Information Alignée en une ligne -->
        <div class="row print-info" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="col-md-4" style="flex: 1; text-align: left; padding: 10px;">
                <label class="control-label text-info" style="font-weight: 600; font-size: 16px; color: #007bff;">Code de Commande :</label>
                <span style="font-size: 16px; font-weight: 500;"><?php echo isset($po_code) ? $po_code : '' ?></span>
            </div>
            <div class="col-md-4" style="flex: 1; text-align: center; padding: 10px;">
                <label class="control-label text-info" style="font-weight: 600; font-size: 16px; color: #007bff;">Fournisseur :</label>
                <span style="font-size: 16px; font-weight: 500;"><?php echo isset($supplier) ? $supplier : '' ?></span>
            </div>
            <div class="col-md-4" style="flex: 1; text-align: center; padding: 10px;">
                <label class="control-label text-info" style="font-weight: 600; font-size: 16px; color: #007bff;">Client :</label>
                <span style="font-size: 16px; font-weight: 500;"><?php echo isset($client) ? $client : '' ?></span>
            </div>
            <div class="col-md-4" style="flex: 1; text-align: right; padding: 10px;">
                <label class="control-label text-info" style="font-weight: 600; font-size: 16px; color: #007bff;">Téléphone :</label>
                <span style="font-size: 16px; font-weight: 500;"><?php echo isset($num) ? $num : '' ?></span>
            </div>
            <div class="col-md-4" style="flex: 1; text-align: right; padding: 10px;">
                <label class="control-label text-info" style="font-weight: 600; font-size: 16px; color: #007bff;">Date de permemtion :</label>
                <span style="font-size: 16px; font-weight: 500;"><?php echo isset($date) ? $date : '' ?></span>
            </div>
        </div>
    </div>
</div>



            <h4 class="text-info">Articles Commandés</h4>
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
                        <th class="text-center py-1 px-2"></th>
                        <th class="text-center py-1 px-2">Quantité</th>
                        <th class="text-center py-1 px-2">(Type Unite)</th>
                        <th class="text-center py-1 px-2">Désignation</th>
                        <th class="text-center py-1 px-2">Prix Unitaire</th>
                        <th class="text-center py-1 px-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    $qry = $conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join item_list i on p.item_id = i.id where p.po_id = '{$id}'");
                    while($row = $qry->fetch_assoc()):
                        $total += $row['total']
                    ?>
                    <tr>
                    <td class="py-1 px-2 text-center">    </td>
                        <td class="py-1 px-2 text-center"><?php echo number_format($row['quantity'],2) ?></td>
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
                        <th class="text-right py-1 px-2" colspan="4">Sous-total</th>
                        <th class="text-right py-1 px-2 sub-total"><?php echo number_format($total,2)  ?></th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Remise <?php echo isset($discount_perc) ? $discount_perc : 0 ?>%</th>
                        <th class="text-right py-1 px-2 discount"><?php echo isset($discount) ? number_format($discount,2) : 0 ?></th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Taxe <?php echo isset($tax_perc) ? $tax_perc : 0 ?>%</th>
                        <th class="text-right py-1 px-2 tax"><?php echo isset($tax) ? number_format($tax,2) : 0 ?></th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Total</th>
                        <th class="text-right py-1 px-2 grand-total"><?php echo isset($amount) ? number_format($amount,2) : 0 ?></th>
                    </tr>
                </tfoot>
            </table>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="text-info control-label">Remarques</label>
                        <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                    </div>
                </div>
                <?php if($status > 0): ?>
                <div class="col-md-6">
                    <span class="text-info"><?php echo ($status == 2)? "REÇU" : "PARTIELLEMENT REÇU" ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

    

    </div>




    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-success" type="button" id="print">Imprimer</button>
        <a class="btn btn-flat btn-primary" href="<?php echo base_url.'/admin?page=purchase_order/manage_po&id='.(isset($id) ? $id : '') ?>">Modifier</a>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin?page=purchase_order' ?>">Retour à la liste</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 text-center qty">
            <span class="visible"></span>
            <input type="hidden" name="item_id[]">
            <input type="hidden" name="unit[]">
            <input type="hidden" name="qty[]">
            <input type="hidden" name="price[]">
            <input type="hidden" name="total[]">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 item">
        </td>
        <td class="py-1 px-2 text-right cost">
        </td>
        <td class="py-1 px-2 text-right total">
        </td>
    </tr>
</table>
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