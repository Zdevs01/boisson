<div class="card card-outline card-primary animate__animated animate__fadeIn">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-warehouse"></i> Liste des Fournisseurs</h3>
        <button id="create_new" class="btn btn-flat btn-primary">
            <i class="fas fa-plus"></i> Ajouter un Fournisseur
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date d'Ajout</th>
                        <th>Fournisseur</th>
                        <th>Personne de Contact</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM `supplier_list` ORDER BY `name` ASC");
                    while($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td><?php echo date("d-m-Y H:i", strtotime($row['date_created'])) ?></td>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $row['cperson'] ?></td>
                        <td class="text-center">
                            <?php if($row['status'] == 1): ?>
                                <span class="badge badge-success rounded-pill">Actif</span>
                            <?php else: ?>
                                <span class="badge badge-danger rounded-pill">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-info view_data" data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning edit_data" data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete_data" data-id="<?php echo $row['id'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer ce fournisseur définitivement ?", "delete_supplier", [$(this).attr('data-id')])
        });
        $('#create_new').click(function(){
            uni_modal("<i class='fa fa-plus'></i> Ajouter un Nouveau Fournisseur", "maintenance/manage_supplier.php", "mid-large")
        });
        $('.edit_data').click(function(){
            uni_modal("<i class='fa fa-edit'></i> Modifier les Détails du Fournisseur", "maintenance/manage_supplier.php?id="+$(this).attr('data-id'), "mid-large")
        });
        $('.view_data').click(function(){
            uni_modal("<i class='fa fa-truck-loading'></i> Détails du Fournisseur", "maintenance/view_supplier.php?id="+$(this).attr('data-id'), "")
        });
        $('.table').DataTable();
    });

    function delete_supplier(id){
        start_loader();
        $.ajax({
            url: _base_url_+"classes/Master.php?f=delete_supplier",
            method: "POST",
            data: {id: id},
            dataType: "json",
            success: function(resp){
                if(resp.status == 'success'){
                    location.reload();
                } else {
                    alert_toast("Une erreur est survenue.", 'error');
                    end_loader();
                }
            }
        });
    }
</script>
