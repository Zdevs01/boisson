<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM sales_list WHERE id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Vente</title>
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
</head>
<body>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h4 class="card-title"><?php echo isset($id) ? "Détails de la Vente - ".$sales_code : 'Créer un nouvel enregistrement de vente' ?></h4>
        </div>
        <div class="card-body">
            <form action="" id="sale-form">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label text-info">Code vente</label>
                            <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($sales_code) ? $sales_code : '' ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client" class="control-label text-info">Nom complet du client</label>
                                <input type="text" name="client" class="form-control form-control-sm rounded-0" value="<?php echo isset($client) ? $client : 'Diane' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
    <div class="form-group">
        <label for="num" class="control-label text-info">
            <i class="fas fa-phone"></i> Numéro de téléphone
        </label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input type="tel" id="num" name="num" class="form-control form-control-sm rounded-0" 
                   placeholder="06 12 34 56 78" 
                   value="<?php echo isset($num) ? $num : ''; ?>" 
                   maxlength="10" 
                   pattern="0\d{9}" 
                   title="Le numéro de téléphone doit contenir exactement 10 chiffres et commencer par 0">
        </div>
        <small class="form-text text-muted">Le numéro de téléphone doit contenir exactement 10 chiffres et commencer par 0.</small>
    </div>
</div>



                        
                    </div>
                    <hr>
                    <fieldset>
                        <legend class="text-info">Formulaire d'article</legend>
                        <div class="row justify-content-center align-items-end">
                            <?php 
                                $item_arr = array();
                                $cost_arr = array();
                                $item = $conn->query("SELECT * FROM `item_list` where status = 1 order by `name` asc");
                                while($row = $item->fetch_assoc()):
                                    $item_arr[$row['id']] = $row;
                                    $cost_arr[$row['id']] = $row['cost'];
                                endwhile;
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="item_id" class="control-label">Choisir le produit en question</label>
                                    <select id="item_id" class="custom-select select2">
                                        <option disabled selected></option>
                                        <?php foreach($item_arr as $k => $v): ?>
                                            <option value="<?php echo $k ?>"> <?php echo $v['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-md-3">
                                <label for="unit" class="control-label">Type d'Unité (Littre)</label>
                                <select id="unit" name="unit" class="custom-select select2">
                                    <option disabled selected></option>
                                    <option value="littre">littre</option>
                                    
                                </select>
                            </div>
                            
                            <div class="col-md-3" id="quantityKg" style="display: none;">
                                <label for="qty2" class="control-label">Quantité (Littre)</label>
                                <input type="number" step="any" class="form-control rounded-0" id="qty2">
                            </div>
                            
                            <div class="col-md-3" id="quantitysac">
                                <label for="qty" class="control-label">Quantité (Littre)</label>
                                <input type="number" step="any" class="form-control rounded-0" id="qty">
                            </div>
                           


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="price" class="control-label">Prix Unitaire</label>
                                    <input type="number" class="form-control rounded-0" id="price" placeholder="prix" readonly>

                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="form-group">
                                    <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Ajouter à la liste</button>
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
                            $qry = $conn->query("SELECT s.*,i.name,i.typ,i.description FROM `stock_list` s INNER JOIN item_list i ON s.item_id = i.id WHERE s.id IN ({$stock_ids})");
                            while($row = $qry->fetch_assoc()):
                                $total += $row['total'];
                            ?>
                            <tr>
                                <td class="py-1 px-2 text-center">
                                    <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                                </td>
                                <td class="py-1 px-2 text-center qty">
                                    <span class="visible"><?php echo number_format($row['quantity2']); ?> </span>
                                    <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                                    <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                                    <input type="hidden" name="qty2[]" value="<?php echo $row['quantity2']; ?>">
                                    <input type="hidden" name="qty[]" value="<?php echo $row['quantity']; ?>">
                                    <input type="hidden" name="price[]" value="<?php echo $row['price']; ?>">
                                    <input type="hidden" name="total[]" value="<?php echo $row['total']; ?>">
                                </td>
                                <td class="py-1 px-2 text-center unit">
                                    <?php echo $row['unit']; ?>
                                </td>
                                <td class="py-1 px-2 item">
                                    <?php echo $row['name']; ?><br>
                                    <?php echo $row['description']; ?>
                                </td>
                                <td class="py-1 px-2 text-right cost">
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
                                <th class="text-right py-1 px-2" colspan="5">Total</th>
                                <input type="hidden" name="amount" value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="remarks" class="text-info control-label">Remarque</label>
                                <textarea name="remarks" id="remarks" class="form-control rounded-0" rows="3"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer py-1 text-center">
            <button class="btn btn-flat btn-primary" form="sale-form">Sauvegarder</button>
            <a class="btn btn-flat btn-default" href="?page=sales">Annuler</a>
        </div>
    </div>
    <table class="d-none" id="clone_list">
        <tr>
            <td class="py-1 px-2 text-center">
                <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
            </td>
            <td class="py-1 px-2 text-center qty">
                <span class="visible">1</span>
                <input type="hidden" name="item_id[]">
                <input type="hidden" name="unit[]">
                <input type="hidden" name="qty2[]">
                <input type="hidden" name="qty[]">
                <input type="hidden" name="price[]">
                <input type="hidden" name="total[]">
            </td>
            <td class="py-1 px-2 text-center unit"></td>
            <td class="py-1 px-2 item"></td>
            <td class="py-1 px-2 text-right price"></td>
            <td class="py-1 px-2 text-right total">0</td>
        </tr>
    </table>
    <script>
        $(document).ready(function() {
    var item_prices = <?php echo json_encode($cost_arr); ?>; // Récupérer les prix depuis PHP

    $('#item_id').change(function() {
        var item_id = $(this).val();
        if (item_id && item_prices[item_id]) {
            $('#price').val(item_prices[item_id]); // Remplit le prix avec la valeur correspondante
        } else {
            $('#price').val('');
        }
    });
});


$(document).ready(function() {
    // Affiche le champ de quantité approprié selon le type d'unité sélectionné
    $('#unit').change(function() {
        if ($(this).val() == 'kg') {
            $('#quantityKg').show();
            $('#quantitysac').hide();
        } else {
            $('#quantityKg').hide();
            $('#quantitysac').show();
        }
    });

    // Fonction pour ajouter un article à la liste
    function addToList() {
        var item = $('#item_id').val();
        var unit = $('#unit').val();
        var qty = unit == 'littre' ? $('#qty').val() : $('#qty').val();
        var qty2 = unit == 'kg' ? $('#qty2').val() : $('#qty').val();
        var price = $('#price').val();
        var total = parseFloat(qty) * parseFloat(price);

        if (item == '' || qty == '' && qty2 == '' || unit == '' || price == '') {
            alert_toast('Tous les champs sont obligatoires.', 'warning');
            return false;
        }

        var item_name = items[item].name || 'N/A';
        var item_description = items[item].description || 'N/A';
        var tr = $('#clone_list tr').clone();

        tr.find('[name="item_id[]"]').val(item);
        tr.find('[name="unit[]"]').val(unit);
        tr.find('[name="qty[]"]').val(qty);
        tr.find('[name="qty2[]"]').val(qty2);
        
        tr.find('[name="price[]"]').val(price);
        tr.find('[name="total[]"]').val(total);

        tr.attr('data-id', item);
        tr.find('.qty .visible').text(qty);
        tr.find('.qty2 .visible').text(qty2);
        tr.find('.unit').text(unit);
        tr.find('.item').html(item_name + '<br/>' + item_description);
        tr.find('.price').text(price);
        tr.find('.total').text(parseFloat(total).toLocaleString('en-US'));

        $('table#list tbody').append(tr);
        calc();
        $('#item_id').val('').trigger('change');
        $('#qty').val('');
        $('#qty2').val('');
        $('#price').val('');

        tr.find('.rem_row').click(function() {
            rem($(this));
        });
    }

    // Événement pour ajouter à la liste
    $('#add_to_list').click(addToList);

    // Calculer le total global
    function calc() {
        var grand_total = 0;
        $('table#list tbody input[name="total[]"]').each(function() {
            grand_total += parseFloat($(this).val());
        });
        $('table#list tfoot .grand-total').text(parseFloat(grand_total).toLocaleString('en-US', { style: 'decimal', maximumFractionDigits: 2 }));
        $('[name="amount"]').val(parseFloat(grand_total));
    }

    function rem(_this) {
        _this.closest('tr').remove();
        calc();
    }

    // Soumission du formulaire de vente
    $('#sale-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);
        $('.err-msg').remove();
        var _el = $('<div>').addClass("alert alert-danger err-msg").hide();
        start_loader();

        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_sale",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("Une erreur s'est produite", 'error');
                end_loader();
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.href = "./?page=sales/view_sale&id=" + resp.id;
                } else if (resp.status == 'failed' && !!resp.msg) {
                    _el.text(resp.msg);
                    _this.prepend(_el);
                    _el.show('slow');
                    $("html, body").scrollTop(0);
                } else {
                    alert_toast("Une erreur s'est produite", 'error');
                    console.log(resp);
                }
                end_loader();
            }
        });
    });
});

// Récupération des articles
var items = $.parseJSON('<?php echo json_encode($item_arr); ?>');
</script>

</body>
</html>
