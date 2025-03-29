<?php require_once('./../../config.php'); ?>
<?php 
$qry = $conn->query("SELECT i.*, s.name as supplier FROM item_list i 
                    INNER JOIN supplier_list s ON i.supplier_id = s.id 
                    WHERE i.id = '{$_GET['id']}'");
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
        padding: 6px 12px;
        border-radius: 10px;
    }
    .details-container {
        max-width: 650px;
        margin: auto;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        background: linear-gradient(45deg, #ff9e00, #ff5722); /* couleur inspirée des jus d'orange */
        color: white;
        text-align: center;
        padding: 15px;
        border-radius: 10px 10px 0 0;
    }
    .card-body dt {
        font-weight: bold;
        color: #343a40;
    }
    .card-body dd {
        font-size: 16px;
        color: #555;
    }
    .badge.bg-success {
        background-color: #28a745; /* Vert frais pour 'En stock' */
    }
    .badge.bg-danger {
        background-color: #dc3545; /* Rouge vif pour 'Rupture de stock' */
    }
    .btn-dark {
        background-color: #343a40;
        color: white;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    .btn-dark:hover {
        background-color: #555;
        cursor: pointer;
    }

    /* Animation pour le texte */
    .animate__fadeInUp {
        animation: fadeInUp 1s ease-out;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container-fluid details-container" id="print_out">
    <div class="card shadow-sm border-primary animate__animated animate__fadeInUp">
        <div class="card-header">
            <i class="fas fa-wine-bottle fa-2x"></i>
            <h4 class="mt-2">Détails du Produit</h4>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-6"><i class="fas fa-box"></i> Nom du Produit :</dt>
                <dd class="col-6 font-weight-bold"> <?php echo $name; ?> </dd>

                <dt class="col-6"><i class="fas fa-tags"></i> Type :</dt>
                <dd class="col-6"> <?php echo isset($category) ? $category : 'Non défini'; ?> </dd>

                <dt class="col-6"><i class="fas fa-align-left"></i> Description :</dt>
                <dd class="col-6"> <?php echo isset($description) ? $description : 'N/A'; ?> </dd>

                <dt class="col-6"><i class="fas fa-wine-glass-alt"></i> Volume en stock :</dt>
                <dd class="col-6"> <?php echo isset($qte) ? $qte . ' L' : 'N/A'; ?> </dd>

                <dt class="col-6"><i class="fas fa-euro-sign"></i> Prix :</dt>
                <dd class="col-6"> <?php echo isset($cost) ? number_format($cost, 2) . ' €' : 'N/A'; ?> </dd>

                <dt class="col-6"><i class="fas fa-truck"></i> Fournisseur :</dt>
                <dd class="col-6"> <?php echo isset($supplier) ? $supplier : 'N/A'; ?> </dd>

                <dt class="col-6"><i class="fas fa-toggle-on"></i> Statut :</dt>
                <dd class="col-6">
                    <?php if ($status == 1): ?>
                        <span class="badge bg-success">En stock</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Rupture de stock</span>
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
