<?php
// Vérifier si une session est déjà démarrée avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$qry = $conn->query("SELECT * FROM sales_list r WHERE id = '{$_GET['id']}'");
if($qry->num_rows > 0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>


<style>
@media print {
    .card {
        border: none;
        box-shadow: none;
    }
    .card-header {
        display: none;
    }
    .print-info {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .print-info .col-md-4 {
        font-weight: bold;
        display: inline-block;
        width: 32%; /* Ajustement pour avoir trois éléments alignés sur une ligne */
        padding-left: 15px;
    }
    .separator {
        display: block;
        width: 100%;
        border-top: 2px dashed black;
        margin-top: 20px;
        margin-bottom: 20px;
    }
}
</style>

<div class="card card-outline card-primary shadow-lg border-0">
    <div class="card-header bg-dark text-light">
        <h4 class="card-title font-weight-bold text-uppercase text-center">
            🏭 Facture de Vente - <?php echo $sales_code ?>
        </h4>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <!-- Informations principales -->
            <div class="row print-info p-3 rounded" style="background: linear-gradient(45deg, #0052D4, #4364F7, #6FB1FC); color: white;">
    <div class="col-md-3 text-left">
        <label class="font-weight-bold">🆔 Code Vente :</label>
        <span><?php echo isset($sales_code) ? $sales_code : '' ?></span>
    </div>
    <div class="col-md-3 text-center">
        <label class="font-weight-bold">👤 Client :</label>
        <span><?php echo isset($client) ? $client : '' ?></span>
    </div>
    <div class="col-md-3 text-center">
        <label class="font-weight-bold">📧 Email :</label>
        <span><?php echo isset($email) ? $email : '' ?></span>
    </div>
    <div class="col-md-3 text-right">
        <label class="font-weight-bold">📞 Téléphone :</label>
        <span><?php echo isset($num) ? $num : '' ?></span>
    </div>
    <div class="col-md-12 text-center mt-2">
        <label class="font-weight-bold">🛒 Mode de Vente :</label>
        <span><?php echo isset($mode_vente) ? $mode_vente : '' ?></span>
    </div>
  
</div>

            <!-- Tableau des articles -->
            <div class="table-responsive mt-4">
                <table class="table table-hover table-striped border">
                    <thead class="text-light bg-dark text-uppercase">
                        <tr class="text-center">
                            <th>Contenant</th>
                            <th>Quantité</th>
                            <th>Type d'Unité</th>
                            <th>Désignation</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $qry = $conn->query("SELECT s.*, i.name, i.description FROM stock_list s INNER JOIN item_list i ON s.item_id = i.id WHERE s.id IN ({$stock_ids})");
                        while($row = $qry->fetch_assoc()):
                            $quantity = ($row['unit'] == 'kg') ? $row['quantity2'] : $row['quantity'];
                            $total_row = $quantity * $row['price'];
                            $total += $total_row;
                        ?>
                        <tr class="text-center">
                        <td><b> <?php echo isset($containing) ? $containing : '' ?></b></td>
                            <td><b><?php echo number_format($quantity) ?></b></td>
                            <td><b><?php echo ($row['unit']) ?></b></td>
                            <td class="text-left"><b><?php echo $row['name'] ?></td>
                            <td class="text-right"><b><?php echo number_format($row['price']) ?></b></td>
                            <td class="text-right"><b><?php echo number_format($total_row) ?></b></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="5" class="text-right">💰 Total :</td>
                            <td class="text-right text-primary"><?php echo number_format($total, 2) ?> €</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right">📝 Montant en lettres :</td>
                            <td class="text-right"></td>
                        </tr>
                        <tr class="signature-row">
                            <td colspan="3" class="signature-cell">
                                <span class="signature-label">✍️ Client:  <?php echo isset($client) ? $client : '' ?></span>
                                <div style="height: 60px; border-top: 2px solid black;"></div>
                            </td>
                            <td colspan="3" class="signature-cell">
                                <span class="signature-label">✍️ Responsable:  <?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?> </span>
                                <div style="height: 60px; border-top: 2px solid black;"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Montants supplémentaires -->
            <div class="row mt-4">
    <div class="col-md-6">
        <div class="p-3 border rounded bg-white shadow-sm text-center">
            <label class="font-weight-bold text-primary">💲 Montant Final :</label>
            <p class="h4 text-success font-weight-bold"><?php echo isset($remarks) ? number_format($remarks, 2) . ' €' : '0 €' ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="p-3 border rounded bg-white shadow-sm text-center">
            <label class="font-weight-bold text-warning">🏷️ Net à payer après TVA :</label>
            <p class="h4 text-danger font-weight-bold"><?php echo isset($final_amount) ? number_format($final_amount, 2) . ' €' : '0 €' ?></p>
        </div>
    </div>

    
</div>

        </div>
    </div>

    <!-- Pied de page -->
    <div class="card-footer py-3 text-center bg-secondary">
    <button class="btn btn-success btn-lg shadow-sm" type="button" id="print">🖨️ Imprimer</button>
    <a class="btn btn-primary btn-lg shadow-sm" href="<?php echo base_url.'/admin?page=Livreurs/manage_sale&id='.(isset($id) ? $id : '') ?>">✏️ Modifier</a>
    <a class="btn btn-dark btn-lg shadow-sm" href="<?php echo base_url.'/admin?page=Livreurs' ?>">🔙 Retour</a>
    <!-- Nouveau bouton 'Commander Livrée' -->
    <!-- Bouton "Commande Livrée" -->
<a class="btn btn-<?php echo ($status == 1) ? 'success' : 'info'; ?> btn-lg shadow-sm" id="btn-livree" data-id="<?php echo $id; ?>">
    <div class="btn btn-<?php echo ($status == 1) ? 'success' : 'info'; ?> btn-lg shadow-sm">
        <?php echo ($status == 1) ? '✅ Commande Livrée' : '🚚 Marquer comme Livrée'; ?>
    </div>
</a>

<!-- Icône de confirmation -->
<span id="status-confirmation" style="font-size: 24px; color: green; display: <?php echo ($status == 1) ? 'inline' : 'none'; ?>;">✅</span>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#btn-livree").click(function() {
        var saleId = $(this).data("id");

        $.ajax({
            url: "update_status.php", // Appel du fichier PHP pour mettre à jour le statut
            type: "POST",
            data: { id: saleId },
            success: function(response) {
                alert("Réponse serveur: " + response); // Afficher la réponse du serveur pour déboguer
                console.log("Réponse serveur:", response);

                if (response.includes("success")) {
                    var newStatus = response.split(":")[1]; // Extraire le nouveau statut
                    if (newStatus == "1") {
                        $("#btn-livree").removeClass("btn-info").addClass("btn-success");
                        $("#btn-livree div").html("✅ Commande Livrée");
                        $("#status-confirmation").show();
                    } else {
                        $("#btn-livree").removeClass("btn-success").addClass("btn-info");
                        $("#btn-livree div").html("🚚 Marquer comme Livrée");
                        $("#status-confirmation").hide();
                    }
                } else {
                    alert("Erreur lors de la mise à jour : " + response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX :", status, error);
            }
        });
    });
});

</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</div>

</div>


<script>
  $(function(){
    $('#print').click(function(){
        start_loader();

        var _el = $('<div>');
        var _head = $('head').clone();
        _head.find('title').text("Facture de Vente - Impression");

        // Cloner le contenu de la facture
        var p = $('#print_out').clone();
        p.find('tr.text-light').removeClass("text-light bg-navy");

        _el.append(_head);
        
        // En-tête de la facture
        _el.append(`
            <div class="container" style="background:#F4F6F7; padding: 20px; border-radius: 10px;">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <img src="<?php echo validate_image($_settings->info('logo')) ?>" width="120px" height="120px" />
                    </div>
                    <div class="col-6 text-center">
                        <h3 class="font-weight-bold text-uppercase text-primary">Application de gestion d’entrepôt</h3>
                        <p class="text-secondary">Société spécialisée dans la distribution de boissons</p>
                        <hr style="border: 1px solid black;" />
                        <p><strong>📞 Tél :</strong> .................</p>
                        <p><strong>📍 Adresse :</strong> .......................</p>
                    </div>
                    <div class="col-3"></div>
                </div>
                <hr style="border-top: 2px solid black;"/>
            </div>
        `);

        // Ajout du contenu de la facture
        _el.append(`
            <div class="container mt-4">
                <div class="p-3 border rounded shadow-sm bg-white">
                    ${p.html()}
                </div>
            </div>
        `);

        // Ajout du QR Code
        _el.append(`
            <div class="text-center mt-4">
                <p class="font-weight-bold text-primary">🔍 Vérification de la facture</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($sales_code); ?>" 
                     alt="QR Code de validation" class="shadow-lg rounded p-2 bg-white" />
            </div>
        `);

        // Création d'une nouvelle fenêtre d'impression
        var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes");
        nw.document.write(_el.html());
        nw.document.close();

        setTimeout(() => {
            nw.print();
            setTimeout(() => {
                nw.close();
                end_loader();
            }, 200);
        }, 500);
    });
});

</script>
