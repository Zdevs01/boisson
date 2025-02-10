<?php
require_once('./../../config.php');

// Vérifier si l'ID du client est passé en paramètre
if(isset($_GET['id'])){
    $client_id = $_GET['id'];
    
    // Récupérer les informations du client depuis la base de données avec une jointure sur la table 'clients'
    $qry = $conn->query("SELECT s.*, c.lastname, c.firstname, c.email, c.avatar FROM `sales_list` s 
                         INNER JOIN clients c ON s.client_id = c.id WHERE s.id = '{$client_id}'");

    if($qry->num_rows > 0){
        $client_info = $qry->fetch_assoc();
        // Récupérer les métadonnées du client
        $meta_qry = $conn->query("SELECT * FROM `user_meta` WHERE user_id = '{$client_id}'");
        $meta = [];
        while($row = $meta_qry->fetch_assoc()){
            $meta[$row['meta_field']] = $row['meta_value'];
        }
    }
}
?>

<style>
    #uni_modal .modal-footer {
        display: none;
    }
    img#cimg {
        height: 15vh;
        width: 15vh;
        object-fit: cover;
        border-radius: 100%;
    }
</style>

<div class="container-fluid" id="print_out">
    <div id="transaction-printable-details" class="position-relative">
        <div class="row">
            <fieldset class="w-100">
                <legend class="text-info">Information</legend>
                <div class="col-12">
                    <div class="form-group text-center">
                        <!-- Affichage de l'avatar du client -->
                        <img src="<?php echo validate_image(isset($client_info['avatar']) ? $client_info['avatar'] : ''); ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                    </div>
                    <hr class="border-light">
                    <dl>
                        <!-- Affichage du nom du client (Prénom et Nom) -->
                        <dt class="text-info">Nom :</dt>
                        <dd class="pl-3"><?php echo isset($client_info['firstname']) && isset($client_info['lastname']) ? $client_info['firstname'] . ' ' . $client_info['lastname'] : ''; ?></dd>
                        
                        <!-- Affichage du genre -->
                        <dt class="text-info">Genre :</dt>
                        <dd class="pl-3"><?php echo isset($meta['gender']) ? $meta['gender'] : ''; ?></dd>
                        
                        <!-- Affichage de la date de naissance -->
                        <dt class="text-info">Date de Naissance :</dt>
                        <dd class="pl-3"><?php echo isset($meta['dob']) ? date("M d, Y", strtotime($meta['dob'])) : ''; ?></dd>
                        
                        <!-- Affichage du contact -->
                        <dt class="text-info">Contact # :</dt>
                        <dd class="pl-3"><?php echo isset($meta['contact']) ? $meta['contact'] : ''; ?></dd>
                        
                        <!-- Affichage de l'adresse -->
                        <dt class="text-info">Adresse :</dt>
                        <dd class="pl-3"><?php echo isset($meta['address']) ? $meta['address'] : ''; ?></dd>
                        
                        <!-- Affichage de l'email -->
                        <dt class="text-info">Email :</dt>
                        <dd class="pl-3"><?php echo isset($client_info['email']) ? $client_info['email'] : ''; ?></dd>
                    </dl>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-12">
        <div class="d-flex justify-content-end align-items-center">
            <button class="btn btn-light btn-flat" type="button" id="cancel" data-dismiss="modal">Fermer</button>
            <!-- Bouton d'impression -->
            <button class="btn btn-primary btn-flat" type="button" onclick="window.print()">Imprimer</button>
        </div>
    </div>
</div>
