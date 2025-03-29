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
      <!-- Ajout des styles Bootstrap et intl-tel-input -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<!-- Conteneur pour l'effet usine -->
<div id="bubbles-container"></div>

<div class="card border-info shadow-lg factory-animation">
    <div class="card-header bg-info text-white text-center">
        <h4>🏭 Nouvelle Vente - Entrepôt de Boissons 🍾</h4>
    </div>
    <div class="card-body">
        <form action="" id="sale-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    
                    <!-- Code Vente -->
                    <div class="col-md-6">
                        <label class="control-label text-info"><i class="fas fa-barcode"></i> Code Vente</label>
                        <input type="text" class="form-control form-control-sm rounded-0 bg-light" 
                               value="<?php echo isset($sales_code) ? $sales_code : '' ?>" readonly>
                    </div>

                    <!-- Nom du client -->
                    <div class="col-md-6">
                        <label for="client" class="control-label text-info"><i class="fas fa-user"></i> Nom du Client</label>
                        <input type="text" name="client" class="form-control form-control-sm rounded-0" 
                               value="<?php echo isset($client) ? $client : 'Mr Durane' ?>">
                    </div>

                    <!-- Numéro de téléphone -->
                    <div class="col-md-6">
                        <label for="num" class="control-label text-info"><i class="fas fa-phone"></i> Téléphone</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="tel" id="num" name="num" class="form-control form-control-sm rounded-0" 
                                   placeholder="Votre numéro" 
                                   value="<?php echo isset($num) ? $num : ''; ?>" >
                        </div>
                        <small class="form-text text-muted">Inclure l'indicatif du pays.</small>
                    </div>

                    <!-- Adresse e-mail -->
                    <div class="col-md-6">
                        <label for="email" class="control-label text-info"><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email"  class="form-control form-control-sm rounded-0" 
                                value="<?php echo isset($email) ? $email : 'exemple@email.com'; ?>">
                    </div>

                </div>
           
    

<style>
/* Fond animé avec bulles */
#bubbles-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    z-index: -1;
    background: linear-gradient(to bottom, #083b66, #0e0e0e);
    overflow: hidden;
}

/* Bulles qui montent */
.bubble {
    position: absolute;
    bottom: -50px;
    width: 20px;
    height: 20px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: bubble-animation 5s infinite ease-in-out;
}

/* Animation des bulles */
@keyframes bubble-animation {
    0% {
        transform: translateY(0);
        opacity: 1;
    }
    100% {
        transform: translateY(-100vh);
        opacity: 0;
    }
}

/* Animation de l'usine (effet néon sur la carte) */
.factory-animation {
    position: relative;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.7);
    animation: neon-glow 3s infinite alternate;
}

/* Effet lumière clignotante */
@keyframes neon-glow {
    0% {
        box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
    }
    100% {
        box-shadow: 0 0 30px rgba(0, 255, 255, 0.9);
    }
}
</style>

<script>
// Sélection du champ input téléphone
var input = document.querySelector("#num");

// Initialisation de intlTelInput avec options
var iti = window.intlTelInput(input, {
    initialCountry: "cm",  // Pays par défaut : Cameroun
    preferredCountries: ["cm", "fr", "us", "gb"], // Pays favoris
    separateDialCode: true, // Affiche l'indicatif séparément
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
});

// Validation du téléphone avant soumission
document.getElementById("sale-form").addEventListener("submit", function(event) {
    if (!iti.isValidNumber()) {
        event.preventDefault();
        alert("Veuillez entrer un numéro de téléphone valide !");
    }
});

// Générer des bulles de manière aléatoire
function createBubble() {
    const bubble = document.createElement("div");
    bubble.classList.add("bubble");
    bubble.style.left = Math.random() * 100 + "vw";
    bubble.style.animationDuration = Math.random() * 5 + 3 + "s"; // Durée entre 3 et 8 sec
    document.getElementById("bubbles-container").appendChild(bubble);

    setTimeout(() => {
        bubble.remove();
    }, 8000);
}

// Générer plusieurs bulles
setInterval(createBubble, 500);
</script>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    const rows = document.querySelectorAll("#list tbody tr");
    rows.forEach((row, index) => {
      setTimeout(() => {
        row.style.opacity = 1;
        row.style.transform = "translateY(0)";
      }, index * 200);
    });

    document.querySelector("#add_to_list").addEventListener("click", function() {
      let newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td class="py-1 px-2 text-center"><button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button></td>
        <td class="py-1 px-2 text-center">New</td>
        <td class="py-1 px-2 text-center">Unit</td>
        <td class="py-1 px-2">Product Name</td>
        <td class="py-1 px-2 text-right">0</td>
        <td class="py-1 px-2 text-right">0</td>`;
      document.querySelector("#list tbody").appendChild(newRow);
      newRow.style.opacity = 0;
      newRow.style.transform = "translateY(-10px)";
      setTimeout(() => {
        newRow.style.opacity = 1;
        newRow.style.transform = "translateY(0)";
      }, 200);
    });
  });
</script>





                        
                    </div>
                    <hr>
                    <fieldset>
    <legend class="text-info">Formulaire d'article</legend>
   <!-- Conteneur d'animation -->
<div id="bubbles-container"></div>

<div class="row justify-content-center align-items-end factory-animation">
    <?php 
        $item_arr = array();
        $cost_arr = array();
        $item = $conn->query("SELECT * FROM `item_list` where status = 1 order by `name` asc");
        while($row = $item->fetch_assoc()):
            $item_arr[$row['id']] = $row;
            $cost_arr[$row['id']] = $row['cost'];
        endwhile;
    ?>

    <!-- Sélection Produit -->
    <div class="col-md-3">
        <div class="form-group">
            <label for="item_id" class="control-label text-warning">🍹 Choisir le Produit</label>
            <select id="item_id" class="custom-select select2 border border-warning shadow-sm">
                <option disabled selected>🔽 Sélectionnez un produit</option>
                <?php foreach($item_arr as $k => $v): ?>
                    <option value="<?php echo $k ?>"> <?php echo $v['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Type d'unité -->
    <div class="col-md-3">
        <div class="form-group">
            <label for="unit" class="control-label text-success font-weight-bold">📏 Type d'Unité</label>
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
            <label for="containing" class="control-label text-info font-weight-bold">🍾 Contenant</label>
            <input type="text" value="<?php echo isset($containing) ? $containing : ''; ?>" 
                   name="containing" class="form-control border border-info shadow-sm" id="containing" readonly>
        </div>
    </div>
    
    <!-- Quantité -->
    <div class="col-md-3" id="quantitysac">
        <label for="qty" class="control-label text-primary">📊 Quantité</label>
        <input type="number" class="form-control border border-primary shadow-sm" id="qty" 
               value="<?php echo isset($qty) ? $qty : 0; ?>">
    </div>
    
    <!-- Prix Unitaire -->
    <div class="col-md-3">
        <div class="form-group">
            <label for="price" class="control-label text-danger">💰 Prix Unitaire</label>
            <input type="number" class="form-control border border-danger shadow-sm" id="price" 
                   value="<?php echo isset($price) ? $price : 0; ?>" readonly>
        </div>
    </div>

    <!-- Mode de Vente -->
    <div class="col-md-3">
        <div class="form-group">
            <label for="mode_vente" class="control-label text-dark">🛒 Mode de Vente</label>
            <select id="mode_vente" name="mode_vente" value="<?php echo isset($mode_vente) ? $mode_vente : ''; ?>"  
                    class="custom-select border border-dark shadow-sm">
                <option disabled selected>🔽 Sélectionnez un mode</option>
                <option value="entrepot">🏢 Vente en Entrepôt (Retrait sur place)</option>
                <option value="livraison">🚚 Livraison (Effectuée par un livreur)</option>
            </select>
        </div>
    </div>
    
    <!-- Bouton d'ajout -->
    <div class="col-md-2 text-center">
        <div class="form-group">
            <button type="button" class="btn btn-flat btn-sm btn-primary animated-btn" id="add_to_list">
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
                               
                                <th class="text-center py-1 px-2">Quantité</th>
                                
                                <th class="text-center py-1 px-2">(Type d'Unité)</th>
                                <th class="text-center py-1 px-2">Designation Produit</th>
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
   
    <!-- Section pour l'avance -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="remarks" class="text-info control-label">Avez-vous effectué un paiement anticipé ?</label>
            <select name="remarks" id="remarks" class="form-control rounded-0" onchange="toggleAdvanceField()">
                <option value="no" <?php echo isset($remarks) && $remarks == 'no' ? 'selected' : ''; ?>>Non</option>
                <option value="yes" <?php echo isset($remarks) && $remarks == 'yes' ? 'selected' : ''; ?>>Oui</option>
            </select>
        </div>

        <!-- Section conditionnelle pour le montant avancé -->
        <div class="form-group" id="advance_field" style="display: <?php echo (isset($remarks) && $remarks == 'yes') ? 'block' : 'none'; ?>;">
            <label for="remarks" class="text-info control-label">Montant Avancé</label>
            <input type="number" name="remarks" id="remarks" class="form-control rounded-0" value="<?php echo isset($remarks) ? $remarks : ''; ?>" min="0" placeholder="Entrez le montant avancé" />
        </div>
    </div>

   
    <!-- Section pour l'activation de la TVA -->
    <div class="col-md-6">
    <div class="form-group">
        <label for="apply_tva" class="text-info control-label">Appliquer la TVA ?</label>
        <select name="apply_tva" id="apply_tva" class="form-control rounded-0" onchange="toggleTvaField()">
            <option value="no" <?php echo isset($apply_tva) && $apply_tva == 'no' ? 'selected' : ''; ?>>Non</option>
            <option value="yes" <?php echo isset($apply_tva) && $apply_tva == 'yes' ? 'selected' : ''; ?>>Oui</option>
        </select>
    </div>

    <!-- Section conditionnelle pour le montant total et la TVA -->
    <div class="form-group" id="tva_field" style="display: <?php echo (isset($apply_tva) && $apply_tva == 'yes') ? 'block' : 'none'; ?>;">
        <label for="total_amount1" class="text-info control-label">Montant Total de la Commande</label>
        <input type="number" name="total_amount1" id="total_amount1" class="form-control rounded-0"
               min="0" placeholder="Entrez le montant total"
               value="<?php echo isset($total_amount1) ? $total_amount1 : ''; ?>" oninput="calculateTva()">

        <label for="tva_rate" class="text-info control-label">Taux de TVA (%)</label>
        <input type="number" name="tva_rate" id="tva_rate" class="form-control rounded-0"
               min="0" max="100" placeholder="Entrez le taux de TVA"
               value="<?php echo isset($tva_rate) ? $tva_rate : ''; ?>" oninput="calculateTva()">

        <label for="calculated_tva" class="text-info control-label">Montant de la TVA</label>
        <input type="number" id="calculated_tva" class="form-control rounded-0"
               value="<?php echo isset($calculated_tva) ? $calculated_tva : ''; ?>" readonly>

        <label for="final_amount" class="text-info control-label">Montant Final (avec TVA)</label>
        <input type="number" name="final_amount" id="final_amount" class="form-control rounded-0"
               value="<?php echo isset($final_amount) ? $final_amount : ''; ?>" readonly>
    </div>
</div>

<!-- Script JavaScript pour gérer l'affichage et le calcul de la TVA -->
<script>
    function toggleTvaField() {
        var applyTva = document.getElementById('apply_tva').value;
        var tvaField = document.getElementById('tva_field');
        
        if (applyTva === 'yes') {
            tvaField.style.display = 'block';
        } else {
            tvaField.style.display = 'none';
            // Réinitialiser les valeurs lorsque la TVA est désactivée
            document.getElementById('total_amount1').value = '';
            document.getElementById('tva_rate').value = '';
            document.getElementById('calculated_tva').value = '';
            document.getElementById('final_amount').value = '';
        }
    }

    function calculateTva() {
        var totalAmount = parseFloat(document.getElementById('total_amount1').value) || 0;
        var tvaRate = parseFloat(document.getElementById('tva_rate').value) || 0;

        var calculatedTva = (totalAmount * tvaRate) / 100;
        var finalAmount = totalAmount + calculatedTva;

        // Mettre à jour les champs de TVA et du montant final
        document.getElementById('calculated_tva').value = calculatedTva.toFixed(2);
        document.getElementById('final_amount').value = finalAmount.toFixed(2);
    }

    // Appeler la fonction au chargement de la page pour respecter la valeur initiale
    document.addEventListener('DOMContentLoaded', function () {
        toggleTvaField();
        calculateTva(); // Si les champs sont pré-remplis
    });
</script>


</div>

<!-- Script JavaScript pour afficher ou cacher le champ de l'avance -->
<script>
    function toggleAdvanceField() {
        var advanceOption = document.getElementById('remarks').value;
        var advanceField = document.getElementById('advance_field');
        
        if (advanceOption === 'yes') {
            advanceField.style.display = 'block';
        } else {
            advanceField.style.display = 'none';
        }
    }

    // Appeler la fonction au chargement de la page pour respecter la valeur initiale
    document.addEventListener('DOMContentLoaded', function () {
        toggleAdvanceField();
    });
</script>

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


// Met à jour la description du contenant en fonction de l'unité et la quantité
$('#unit, #qty').on('input change', function() {
        let unit = $('#unit').val();
        let qty = parseFloat($('#qty').val()) || 0;
        let contenantField = $('#containing');

        // Met à jour le champ en fonction de l'unité sélectionnée
        contenantField.val(
            unit === 'carton' ? `${qty} bouteille(s)` :
            unit === 'pack' ? `${qty} plastique(s)` :
            ''
        );
    });











       $(document).ready(function() {
    var item_prices = <?php echo json_encode($cost_arr); ?>; // Récupérer les prix depuis PHP

    $('#item_id').change(function() {
        var item_id = $(this).val();
        if (item_id && item_prices[item_id]) {
            $('#price').val(item_prices[item_id]); // Met à jour le prix selon l'article sélectionné
        } else {
            $('#price').val('');
        }
    });

    // Affiche le champ de quantité approprié selon l'unité sélectionnée
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
        var qty = $('#qty').val(); // Suppression de qty2
        var price = $('#price').val();
        var total = parseFloat(qty) * parseFloat(price);

        if (item == '' || qty == '' || unit == '' || price == '') {
            alert_toast('Tous les champs sont obligatoires.', 'warning');
            return false;
        }

        var item_name = items[item].name || 'N/A';
        var item_description = items[item].description || 'N/A';
        var tr = $('#clone_list tr').clone();

        tr.find('[name="item_id[]"]').val(item);
        tr.find('[name="unit[]"]').val(unit);
        tr.find('[name="qty[]"]').val(qty);
        tr.find('[name="price[]"]').val(price);
        tr.find('[name="total[]"]').val(total);

        tr.attr('data-id', item);
        tr.find('.qty .visible').text(qty);
        tr.find('.unit').text(unit);
        tr.find('.item').html(item_name + '<br/>' + item_description);
        tr.find('.price').text(price);
        tr.find('.total').text(parseFloat(total).toLocaleString('en-US'));

        $('table#list tbody').append(tr);
        calc();
        $('#item_id').val('').trigger('change');
        $('#qty').val('');
        $('#price').val('');

        tr.find('.rem_row').click(function() {
            rem($(this));
        });
    }

    $('#add_to_list').click(addToList);

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

    var items = $.parseJSON('<?php echo json_encode($item_arr); ?>');
});

</script>

</body>
</html>
