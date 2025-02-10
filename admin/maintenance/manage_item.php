<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `item_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    .form-group label {
        font-weight: bold;
    }
</style>

<div class="container-fluid animate__animated animate__fadeIn">
    <div class="card p-4">
        <div class="text-center mb-3">
            <i class="fas fa-warehouse fa-3x text-primary"></i>
            <h4 class="text-primary mt-2">Gestion du Produit</h4>
        </div>
        
        <form action="" id="item-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            
            <div class="row">
                <!-- Colonne Gauche -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-wine-bottle"></i> Nom du Produit *</label>
                        <input type="text" name="name" id="name" class="form-control rounded-0" placeholder="Entrez le nom du produit" value="<?php echo isset($name) ? $name : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="category"><i class="fas fa-tags"></i> Type de Produit *</label>
                        <select name="category" id="category" class="custom-select select2" required>
                            <option value="" disabled selected>Choisissez un type</option>
                            <option value="Bière" <?php echo isset($category) && $category == 'Bière' ? 'selected' : '' ?>>Bière</option>
                            <option value="Jus" <?php echo isset($category) && $category == 'Jus' ? 'selected' : '' ?>>Jus</option>
                            <option value="Soda" <?php echo isset($category) && $category == 'Soda' ? 'selected' : '' ?>>Soda</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left"></i> Description *</label>
                        <textarea name="description" id="description" cols="30" rows="2" class="form-control rounded-0" placeholder="Ajoutez une description" required><?php echo isset($description) ? $description : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                    <label for="cost"><i class="fas fa-money-bill-wave"></i> quantite en stock *</label>
                    <input type="number" name="qte" id="qte" step="any" class="form-control rounded-0 text-end" placeholder="Entrez le coût" value="<?php echo isset($qte) ? $qte : ''; ?>" required>
                    </div>

                </div>

                <!-- Colonne Droite -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cost"><i class="fas fa-money-bill-wave"></i> Coût *</label>
                        <input type="number" name="cost" id="cost" step="any" class="form-control rounded-0 text-end" placeholder="Entrez le coût" value="<?php echo isset($cost) ? $cost : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="supplier_id"><i class="fas fa-truck"></i> Fournisseur *</label>
                        <select name="supplier_id" id="supplier_id" class="custom-select select2" required>
                            <option value="" disabled selected>Veuillez sélectionner</option>
                            <?php 
                            $supplier = $conn->query("SELECT * FROM `supplier_list` where status = 1 order by `name` asc");
                            while($row=$supplier->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?> ><?php echo $row['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status"><i class="fas fa-toggle-on"></i> Disponibilité *</label>
                        <select name="status" id="status" class="custom-select" required>
                            <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>En stock</option>
                            <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Rupture de stock</option>
                        </select>
                    </div>
                    
                </div>
            </div>

            <!-- Bouton de validation -->
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    // Initialisation de Select2
    $('.select2').select2({placeholder: "Veuillez sélectionner", width: "100%"});

    // Soumission du formulaire avec vérifications
    $('#item-form').submit(function(e){
        e.preventDefault();

        if ($('#category').val() === null || $('#category').val() === '') {
            alert_toast("Veuillez sélectionner un type de produit !", 'warning');
            return false;
        }

        var _this = $(this);
        $('.err-msg').remove();
        start_loader();

        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_item",
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(err){
                console.log(err);
                alert_toast("Une erreur est survenue", 'error');
                end_loader();
            },
            success: function(resp){
                if (typeof resp === 'object' && resp.status === 'success') {
                    location.reload();
                } else if (resp.status === 'failed' && !!resp.msg) {
                    var el = $('<div>');
                    el.addClass("alert alert-danger err-msg").text(resp.msg);
                    _this.prepend(el);
                    el.show('slow');
                    end_loader();
                } else {
                    alert_toast("Une erreur est survenue", 'error');
                    end_loader();
                    console.log(resp);
                }
            }
        });
    });
});
</script>
