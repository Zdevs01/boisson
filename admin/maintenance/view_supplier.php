<?php require_once('./../../config.php') ?>
<?php 
 $qry = $conn->query("SELECT * FROM supplier_list WHERE id = '{$_GET['id']}' ");
 if($qry->num_rows > 0){
     foreach($qry->fetch_assoc() as $k => $v){
         $$k=$v;
     }
 }
?>
<!-- Ajout d'animations et styles CSS -->
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
    .info-title {
        font-weight: bold;
        color: #007bff;
    }
    .data-field {
        margin-left: 15px;
        font-size: 16px;
    }
    .info-box {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 10px;
    }
</style>

<div class="container-fluid" id="print_out">
    <div id='transaction-printable-details' class='position-relative animated-card'>
        <div class="row">
            <fieldset class="w-100">
                <div class="col-12">
                    <dl>
                        <div class="info-box">
                            <dt class="info-title"><i class="fas fa-user icon-container"></i> Nom du Fournisseur :</dt>
                            <dd class="data-field">🍾 <?php echo $name ?></dd>
                        </div>
                        
                        <div class="info-box">
                            <dt class="info-title"><i class="fas fa-map-marker-alt icon-container"></i> Adresse :</dt>
                            <dd class="data-field">📍 <?php echo isset($address) ? $address : 'Non spécifié' ?></dd>
                        </div>

                        <div class="info-box">
                            <dt class="info-title"><i class="fas fa-flag icon-container"></i> Pays :</dt>
                            <dd class="data-field">🌍 <?php echo isset($country) ? $country : 'Non spécifié' ?></dd>
                        </div>
                        
                        <div class="info-box">
                            <dt class="info-title"><i class="fas fa-user-tie icon-container"></i> Personne de Contact :</dt>
                            <dd class="data-field">👤 <?php echo isset($cperson) ? $cperson : 'Non spécifié' ?></dd>
                        </div>
                        
                        <div class="info-box">
                            <dt class="info-title"><i class="fas fa-phone icon-container"></i> Numéro de Téléphone :</dt>
                            <dd class="data-field">📞 <?php echo isset($phone_number) ? $phone_number : 'Non spécifié' ?></dd>
                        </div>
                        
                        <div class="info-box">
                            <dt class="info-title"><i class="fas fa-envelope icon-container"></i> Email :</dt>
                            <dd class="data-field">📧 <?php echo isset($email) ? $email : 'Non spécifié' ?></dd>
                        </div>

                        <div class="info-box">
                            <dt class="info-title"><i class="fas fa-toggle-on icon-container"></i> Statut :</dt>
                            <dd class="data-field">
                                <?php if($status == 1): ?>
                                    <span class="badge badge-success rounded-pill"><i class="fas fa-check"></i> Actif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger rounded-pill"><i class="fas fa-times"></i> Inactif</span>
                                <?php endif; ?>
                            </dd>
                        </div>
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
