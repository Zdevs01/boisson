<?php require_once('./../../config.php') ?>
<?php 
 $qry = $conn->query("SELECT * FROM `supplier_list` WHERE id = '{$_GET['id']}' ");
 if($qry->num_rows > 0){
     foreach($qry->fetch_assoc() as $k => $v){
         $$k=$v;
     }
 }
?>
<!-- Ajout d'animations CSS -->
<style>
    #uni_modal .modal-footer {
        display: none;
    }
    .animated-card {
        animation: fadeInUp 0.5s ease-in-out;
    }
    .icon-container {
        transition: transform 0.3s ease-in-out;
    }
    .icon-container:hover {
        transform: scale(1.1);
    }
    .badge {
        font-size: 14px;
        padding: 5px 10px;
    }
</style>

<div class="container-fluid" id="print_out">
    <div id='transaction-printable-details' class='position-relative animated-card'>
        <div class="row">
            <fieldset class="w-100">
                <div class="col-12">
                    <dl>
                        <dt class="text-info"><i class="fas fa-user icon-container"></i> Nom du Fournisseur :</dt>
                        <dd class="pl-3"><?php echo $name ?></dd>
                        
                        <dt class="text-info"><i class="fas fa-map-marker-alt icon-container"></i> Adresse :</dt>
                        <dd class="pl-3"><?php echo isset($address) ? $address : '' ?></dd>
                        
                        <dt class="text-info"><i class="fas fa-user-tie icon-container"></i> Personne de Contact :</dt>
                        <dd class="pl-3"><?php echo isset($cperson) ? $cperson : '' ?></dd>
                        
                        <dt class="text-info"><i class="fas fa-phone icon-container"></i> Numéro de Contact :</dt>
                        <dd class="pl-3"><?php echo isset($contact) ? $contact : '' ?></dd>
                        
                        <dt class="text-info"><i class="fas fa-toggle-on icon-container"></i> Statut :</dt>
                        <dd class="pl-3">
                            <?php if($status == 1): ?>
                                <span class="badge badge-success rounded-pill">✔ Actif</span>
                            <?php else: ?>
                                <span class="badge badge-danger rounded-pill">✖ Inactif</span>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-12">
        <div class="d-flex justify-content-end align-items-center">
            <button class="btn btn-danger btn-flat icon-container" type="button" id="cancel" data-dismiss="modal">
                <i class="fas fa-times"></i> Fermer
            </button>
        </div>
    </div>
</div>

<!-- Ajout d'animations au chargement -->
<script>
    $(function(){
        $('.table td, .table th').addClass('py-1 px-2 align-middle');
        
        // Animation d'entrée
        $('#transaction-printable-details').hide().fadeIn(500);

        // Effet de survol sur le bouton fermer
        $('#cancel').hover(function(){
            $(this).toggleClass('btn-dark btn-danger');
        });
    })
</script>
