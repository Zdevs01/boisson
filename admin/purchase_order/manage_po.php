
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
        bacpackround: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        bacpackround: #eee;
        box-shadow: none;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background: url('https://source.unsplash.com/1600x900/?warehouse,drinks') no-repeat center center fixed;
            background-size: cover;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .btn-primary {
            background: #ff9800;
            border: none;
        }
        .btn-primary:hover {
            background: #e68900;
        }
        .form-control {
            border-radius: 10px;
        }
        legend {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff9800;
        }
        .animated-icon {
            font-size: 1.5rem;
            color: #ff9800;
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
            <div class="row p-3 border rounded shadow-sm">
    <!-- Code de la commande -->
    <div class="col-md-6">
        <label class="control-label font-weight-bold text-primary">📜 Code de la Commande</label>
        <input type="text" class="form-control form-control-sm rounded-0 border border-primary shadow-sm" 
               value="<?php echo isset($po_code) ? $po_code : '' ?>" readonly>
    </div>

    <!-- Fournisseur -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="supplier_id" class="control-label font-weight-bold text-success">🏢 Fournisseur</label>
            <select name="supplier_id" id="supplier_id" class="custom-select select2 border border-success shadow-sm">
                <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled>🔽 Sélectionnez un fournisseur</option>
                <?php 
                $supplier = $conn->query("SELECT * FROM supplier_list WHERE status = 1 ORDER BY name ASC");
                while($row = $supplier->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>>
                    <?php echo $row['name'] ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Nom du client -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="client" class="control-label font-weight-bold text-info">🧑‍💼 Nom complet du client</label>
            <input type="text" name="client" class="form-control form-control-sm rounded-0 border border-info shadow-sm"
                   value="<?php echo isset($client) ? $client : 'Diane' ?>" placeholder="Entrez le nom du client">
        </div>
    </div>

    <!-- Numéro de téléphone -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="num" class="control-label font-weight-bold text-danger">📞 Numéro de téléphone</label>
            <input type="tel" id="num" name="num" 
                   class="form-control form-control-sm rounded-0 border border-danger shadow-sm" 
                   placeholder="06XXXXXXXX" 
                   value="<?php echo isset($num) ? $num : '0639481655'; ?>" 
                   pattern="^0[1-9]\d{8}$" 
                   title="Le numéro doit être un format français valide (ex: 06XXXXXXXX)">
            <small class="form-text text-muted">
                📌 Format attendu : 06XXXXXXXX (numéro français valide).
            </small>
        </div>
    </div>
</div>

                <hr>
                <fieldset class="border p-3 rounded shadow-sm">
    <legend class="text-primary font-weight-bold">
        🍾 Formulaire d'Article
    </legend>
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

        <!-- Sélection du produit -->
        <div class="col-md-3">
            <div class="form-group">
                <label for="item_id" class="control-label font-weight-bold text-success">📦 Choisir le produit</label>
                <select id="item_id" class="custom-select border border-success shadow-sm">
                    <option disabled selected>🔽 Sélectionnez un produit</option>
                </select>
            </div>
        </div>

        <!-- Type d'unité -->
        <div class="col-md-3">
            <div class="form-group">
                <label for="unit" class="control-label font-weight-bold text-success">📏 Type d'Unité</label>
                <select id="unit" class="custom-select border border-success shadow-sm">
                    <option disabled selected>🔽 Sélectionnez un type</option>
                    <option value="carton">📦 Carton</option>
                    <option value="pack">🎯 Pack</option>
                </select>
            </div>
        </div>

        <!-- Contenant -->
        <div class="col-md-3">
            <div class="form-group">
                <label for="containing" class="control-label font-weight-bold text-info">🍾 Contenant</label>
                <input type="text" class="form-control rounded-0 border border-info shadow-sm" id="containing" readonly>
            </div>
        </div>

        <!-- Quantité -->
        <div class="col-md-3">
            <div class="form-group">
                <label for="qty" class="control-label font-weight-bold text-info">🔢 Quantité</label>
                <input type="number" step="any" class="form-control rounded-0 border border-info shadow-sm" id="qty" placeholder="Entrez la quantité">
            </div>
        </div>

        <!-- Prix Unitaire -->
        <div class="col-md-3">
            <div class="form-group">
                <label for="price" class="control-label font-weight-bold text-danger">💰 Prix Unitaire</label>
                <input type="number" class="form-control rounded-0 border border-danger shadow-sm" id="price" placeholder="Saisissez le prix">
            </div>
        </div>

        <!-- Date de péremption -->
        <div class="col-md-3">
            <div class="form-group position-relative">
                <label for="expiry_date" class="control-label font-weight-bold text-primary">
                    📅 Date de péremption <span class="text-danger">*</span>
                </label>
                <input type="date" name="client" class="form-control form-control-sm rounded-0 border border-info shadow-sm"
                value="<?php echo isset($date) ? $date : 'Diane' ?>" required>
                <small class="form-text text-muted">
                    📌 Sélectionnez la date limite avant consommation.
                </small>
            </div>
        </div>

        <!-- Bouton d'ajout -->
        <div class="col-md-2 text-center">
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-lg shadow-lg" id="add_to_list">
                    ➕ Ajouter à la Liste
                </button>
            </div>
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
                        <th class="text-center py-1 px-2"> Contenant</th>
                                <th class="text-center py-1 px-2">Quantité</th>
                                <th class="text-center py-1 px-2">(carton/pack)</th>
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
    document.getElementById('unit').addEventListener('change', function() {
        let contenantField = document.getElementById('containing');
        if (this.value === 'carton') {
            contenantField.value = 'bouteilles';
        } else if (this.value === 'pack') {
            contenantField.value = 'plastique';
        } else {
            contenantField.value = '';
        }
    });
</script>
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
            var containing = $('#containing').val();
            var qty = $('#qty').val();
            var unit = $('#unit').val();
            var price = $('#price').val();
            var total = parseFloat(qty) * parseFloat(price);

           

            if(item_id == '' || containing == '' || qty == '' || unit == '' || price == ''){
                alert("Veuillez remplir tous les champs de l'article.");
                return false;
            }

            var item_name = $('#item_id option:selected').text();
            var tr = $('<tr></tr>')
            tr.append('<td class="py-1 px-2 text-center"><button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button></td>')
            tr.append('<td class="py-1 px-2 text-center qty"><span class="visible">'+qty+'</span><input type="hidden" name="item_id[]" value="'+item_id+'"><input type="hidden" name="unit[]" value="'+unit+'"><input type="hidden" name="qty[]" value="'+qty+'"><input type="hidden" name="price[]" value="'+price+'"><input type="hidden" name="total[]" value="'+total+'"></td>')
            tr.append('<td class="py-1 px-2 text-center containing">'+containing+'</td>')
            tr.append('<td class="py-1 px-2 text-center unit">'+unit+'</td>')
            tr.append('<td class="py-1 px-2 item">'+item_name+'</td>')
            tr.append('<td class="py-1 px-2 text-right price">'+parseFloat(price).toLocaleString('fr-FR', { style: 'decimal', minimumFractionDigits: 2 })+'</td>')
            tr.append('<td class="py-1 px-2 text-right total">'+parseFloat(total).toLocaleString('fr-FR', { style: 'decimal', minimumFractionDigits: 2 })+'</td>')

            $('#list tbody').append(tr)
            $('#item_id').val('').trigger('change')
            $('#qty').val('')
            $('#containing').val('')
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let unitField = document.getElementById('unit');
        let qtyField = document.getElementById('qty');
        let contenantField = document.getElementById('containing');

        function updatecontaining() {
            let qty = parseFloat(qtyField.value) || 0;
            let unit = unitField.value;
            
            if (unit === 'carton') {
                contenantField.value = qty > 1 ? qty + " bouteilles" : "1 bouteille";
            } else if (unit === 'pack') {
                contenantField.value = qty > 1 ? qty + " plastiques" : "1 plastique";
            } else {
                contenantField.value = '';
            }
        }

        unitField.addEventListener('change', updatecontaining);
        qtyField.addEventListener('input', updatecontaining);
    });
</script>
