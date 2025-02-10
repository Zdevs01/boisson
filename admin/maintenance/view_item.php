<?php require_once('./../../config.php') ?>
<?php 
$qry = $conn->query("SELECT i.*, s.name as supplier FROM `item_list` i INNER JOIN supplier_list s ON i.supplier_id = s.id WHERE i.id = '{$_GET['id']}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_assoc() as $k => $v) {
        $$k = $v;
    }
}
?>

<style>
    #uni_modal .modal-footer {
        display: none;
    }
    .badge {
        font-size: 14px;
        padding: 5px 10px;
    }
    .details-container {
        max-width: 600px;
        margin: auto;
    }
</style>

<div class="container-fluid details-container" id="print_out">
    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white text-center">
            <i class="fas fa-box fa-2x"></i>
            <h4 class="mt-2">Détails du Produit</h4>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-6"><i class="fas fa-box"></i> Nom du Produit :</dt>
                <dd class="col-6 font-weight-bold"> <?php echo $name ?> </dd>

                <dt class="col-6"><i class="fas fa-tags"></i> Type de Produit :</dt>
                <dd class="col-6"> <?php echo isset($category) ? $category : 'Non défini' ?> </dd>

                <dt class="col-6"><i class="fas fa-align-left"></i> Description :</dt>
                <dd class="col-6"> <?php echo isset($description) ? $description : 'N/A' ?> </dd>

                <dt class="col-6"><i class="fas fa-align-left"></i> Littre en stok :</dt>
                <dd class="col-6"> <?php echo isset($qte) ? $qte : 'N/A' ?> </dd>

                <dt class="col-6"><i class="fas fa-money-bill-wave"></i> Coût :</dt>
                <dd class="col-6"> <?php echo isset($cost) ? number_format($cost, 2) . ' FCFA' : 'N/A' ?> </dd>

                <dt class="col-6"><i class="fas fa-truck"></i> Fournisseur :</dt>
                <dd class="col-6"> <?php echo isset($supplier) ? $supplier : 'N/A' ?> </dd>

                <dt class="col-6"><i class="fas fa-toggle-on"></i> Statut :</dt>
                <dd class="col-6">
                    <?php if ($status == 1): ?>
                        <span class="badge badge-success rounded-pill">En stock</span>
                    <?php else: ?>
                        <span class="badge badge-danger rounded-pill">Rupture de stock</span>
                    <?php endif; ?>
                </dd>
            </dl>
        </div>
    </div>
</div>

<div class="form-group mt-3 text-center">
    <button class="btn btn-dark btn-flat" type="button" id="cancel" data-dismiss="modal">
        <i class="fas fa-times"></i> Fermer
    </button>
</div>

<script>
    $(document).ready(function(){
        $('#cancel').on('click', function(){
            $('#uni_modal').modal('hide');
        });
    });
</script>