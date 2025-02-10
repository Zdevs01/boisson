<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT p.*, s.name as supplier FROM purchase_order_list p INNER JOIN supplier_list s ON p.supplier_id = s.id WHERE p.id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
        }
    }
}
?>
<style>
    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }
</style>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title"><?php echo isset($id) ? "Détails de la Commande - " . $po_code : 'Créer une nouvelle Commande' ?></h4>
    </div>
    <div class="card-body">
        <form action="" id="po-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Code de la Commande</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($po_code) ? $po_code : '' ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id" class="control-label text-info">Fournisseur</label>
                            <select name="supplier_id" id="supplier_id" class="custom-select select2">
                                <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
                                <?php 
                                $supplier = $conn->query("SELECT * FROM supplier_list WHERE status = 1 ORDER BY name ASC");
                                while($row = $supplier->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>


                    </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="client" class="control-label text-info">Nom complet du client</label>
                                <input type="text" name="client" class="form-control form-control-sm rounded-0" value="<?php echo isset($client) ? $client : 'Diane' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="num" class="control-label text-info">Numéro de téléphone</label>
                                <input type="text" id="num" name="num" class="form-control form-control-sm rounded-0" placeholder="693481655" value="<?php echo isset($num) ? $num : '693481655'; ?>" maxlength="9" pattern="\d{9}" title="Le numéro de téléphone doit contenir exactement 9 chiffres">
                                <small class="form-text text-muted">Le numéro de téléphone doit contenir exactement 9 chiffres.</small>
                            </div>
                        </div>
                </div>
                <hr>
                <fieldset>
                    <legend class="text-info">Formulaire d'Article</legend>
                    <div class="row justify-content-center align-items-end">
                            <?php 
                                $item_arr = array();
                                $cost_arr = array();
                                $item = $conn->query("SELECT * FROM item_list WHERE status = 1 ORDER BY name ASC");
                                while($row = $item->fetch_assoc()):
                                    $item_arr[$row['supplier_id']][$row['id']] = $row;
                                    $cost_arr[$row['id']] = $row['cost'];
                                endwhile;
                            ?>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label for="item_id" class="control-label">Choisir le produit en question</label>
                                <select id="item_id" class="custom-select ">
                                    <option disabled selected></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                                    <label for="unit" class="control-label">Type d'Unité (Littre)</label>
                                    <select id="unit" class="custom-select select2">
                                        <option disabled selected></option>
                                        
                                            <option value="littre">Littre</option>
                                            
                                       
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="qty" class="control-label">Quantité</label>
                                <input type="number" step="any" class="form-control rounded-0" id="qty">
                            </div>
                        </div>
                        <div class="col-md-3">
                                <div class="form-group">
                                    <label for="price" class="control-label">Prix Unitaire</label>
                                    <input type="number" class="form-control rounded-0" id="price" placeholder="prix">
                                </div>
                            </div>
                        <div class="col-md-2 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Ajouter à la Liste</button>
                            </div>
                        </div>
                </fieldset>
                <hr>
                <table class="table table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="10%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2"></th>
                                <th class="text-center py-1 px-2">Quantité</th>
                                <th class="text-center py-1 px-2">(littre)</th>
                                <th class="text-center py-1 px-2">Produit</th>
                                <th class="text-center py-1 px-2">Prix Unitaire</th>
                                <th class="text-center py-1 px-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        if(isset($id)):
                        $qry = $conn->query("SELECT p.*, i.name, i.description FROM po_items p INNER JOIN item_list i ON p.item_id = i.id WHERE p.po_id = '{$id}'");
                        while($row = $qry->fetch_assoc()):
                            $total += $row['total']
                        ?>
                        <tr>
                            <td class="py-1 px-2 text-center">
                                <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                            </td>
                            <td class="py-1 px-2 text-center qty">
                                <span class="visible"><?php echo number_format($row['quantity']); ?></span>
                                <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                                <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                                <input type="hidden" name="qty[]" value="<?php echo $row['quantity']; ?>">
                                <input type="hidden" name="price[]" value="<?php echo $row['price']; ?>">
                                <input type="hidden" name="total[]" value="<?php echo $row['total']; ?>">
                            </td>
                            <td class="py-1 px-2 text-center unit">
                            <?php echo $row['unit']; ?>
                            </td>
                            <td class="py-1 px-2 item">
                            <?php echo $row['name']; ?> <br>
                            <?php echo $row['description']; ?>
                            </td>
                            <td class="py-1 px-2 text-right price">
                            <?php echo number_format($row['price']); ?>
                            </td>
                            <td class="py-1 px-2 text-right total">
                            <?php echo number_format($row['total']); ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Sous-Total</th>
                            <th class="text-right py-1 px-2 sub-total">0</th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Remise <input style="width:40px !important" name="discount_perc" class='' type="number" min="0" max="100" value="<?php echo isset($discount_perc) ? $discount_perc : 0 ?>">%
                                <input type="hidden" name="discount" value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 discount"><?php echo isset($discount) ? number_format($discount) : 0 ?></th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Taxe <input style="width:40px !important" name="tax_perc" class='' type="number" min="0" max="100" value="<?php echo isset($tax_perc) ? $tax_perc : 0 ?>">%
                                <input type="hidden" name="tax" value="<?php echo isset($tax) ? $tax : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 tax"><?php echo isset($tax) ? number_format($tax) : 0 ?></th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Total
                                <input type="hidden" name="amount" value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 grand-total">0</th>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks" class="control-label text-info">Remarques</label>
                            <textarea name="remarks" id="remarks" class="form-control rounded-0" rows="3"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <button class="btn btn-flat btn-primary" form="po-form">Enregistrer</button>
        <a class="btn btn-flat btn-default" href="?page=purchase_order">Annuler</a>
    </div>
</div>

<script>
    var items = $.parseJSON('<?php echo json_encode($item_arr) ?>')
    var costs = $.parseJSON('<?php echo json_encode($cost_arr) ?>')
    
    function calc_total(){
        var sub_total = 0
        $('#list tbody input[name="total[]"]').each(function(){
            sub_total += parseFloat($(this).val())
        })
        var discount = sub_total * (parseFloat($('[name="discount_perc"]').val()) / 100);
        var tax = (sub_total - discount) * (parseFloat($('[name="tax_perc"]').val()) / 100);
        $('.sub-total').text(parseFloat(sub_total).toLocaleString('fr-FR', { style: 'decimal', minimumFractionDigits: 2 }))
        $('[name="discount"]').val(discount)
        $('.discount').text(parseFloat(discount).toLocaleString('fr-FR', { style: 'decimal', minimumFractionDigits: 2 }))
        $('[name="tax"]').val(tax)
        $('.tax').text(parseFloat(tax).toLocaleString('fr-FR', { style: 'decimal', minimumFractionDigits: 2 }))
        var grand_total = sub_total - discount + tax;
        $('.grand-total').text(parseFloat(grand_total).toLocaleString('fr-FR', { style: 'decimal', minimumFractionDigits: 2 }))
    }

    function display_items(){
        var supplier_id = $('[name="supplier_id"]').val();
        $('#item_id').empty().append('<option disabled selected></option>')
        $.each(items[supplier_id], function(id, item){
            $('#item_id').append('<option value="'+id+'">'+item.name+'</option>')
        })
    }

    $(document).ready(function(){
        if('<?php echo isset($id) ?>' == 1){
            calc_total()
        }

        $('.select2').select2({
            placeholder: "Veuillez sélectionner ici",
            width:'100%',
        })

        $('[name="supplier_id"]').change(function(){
            display_items()
        })

        $('#add_to_list').click(function(){
            var item_id = $('#item_id').val();
            var qty = $('#qty').val();
            var unit = $('#unit').val();
            var price = $('#price').val();
            var total = parseFloat(qty) * parseFloat(price);

           

            if(item_id == '' || qty == '' || unit == '' || price == ''){
                alert("Veuillez remplir tous les champs de l'article.");
                return false;
            }

            var item_name = $('#item_id option:selected').text();
            var tr = $('<tr></tr>')
            tr.append('<td class="py-1 px-2 text-center"><button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button></td>')
            tr.append('<td class="py-1 px-2 text-center qty"><span class="visible">'+qty+'</span><input type="hidden" name="item_id[]" value="'+item_id+'"><input type="hidden" name="unit[]" value="'+unit+'"><input type="hidden" name="qty[]" value="'+qty+'"><input type="hidden" name="price[]" value="'+price+'"><input type="hidden" name="total[]" value="'+total+'"></td>')
            tr.append('<td class="py-1 px-2 text-center unit">'+unit+'</td>')
            tr.append('<td class="py-1 px-2 item">'+item_name+'</td>')
            tr.append('<td class="py-1 px-2 text-right price">'+parseFloat(price).toLocaleString('fr-FR', { style: 'decimal', minimumFractionDigits: 2 })+'</td>')
            tr.append('<td class="py-1 px-2 text-right total">'+parseFloat(total).toLocaleString('fr-FR', { style: 'decimal', minimumFractionDigits: 2 })+'</td>')

            $('#list tbody').append(tr)
            $('#item_id').val('').trigger('change')
            $('#qty').val('')
            $('#unit').val('')
            $('#price').val('');
            calc_total()
        })

        $('#list tbody').on('click', '.rem_row', function(){
            $(this).closest('tr').remove()
            calc_total()
        })

        $('[name="discount_perc"],[name="tax_perc"]').on('input', function(){
            calc_total()
        })

        $('#po-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_po",
                method:"POST",
                data:_this.serialize(),
                dataType:"json",
                error:err=>{
                    console.log(err);
                    alert_toast("Une erreur s'est produite", 'error');
                    end_loader();
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href = "./?page=purchase_order/view_po&id="+resp.id;
                    }else{
                        alert_toast("Une erreur s'est produite", 'error');
                        end_loader();
                    }
                }
            })
        })
    })
</script>