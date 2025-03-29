<div class="card card-outline card-primary shadow-lg rounded-lg border-0">
    <div class="card-header d-flex justify-content-between align-items-center bg-gradient-to-r from-yellow-400 via-orange-500 to-green-500 text-white rounded-t-lg">
        <h3 class="card-title">
            <i class="fas fa-wine-bottle"></i> <span>Liste des Boissons 🍹</span>
        </h3>
        <div class="card-tools">
            <a href="javascript:void(0)" id="create_new" class="btn btn-outline-light btn-lg">
                <i class="fas fa-plus-circle"></i> Ajouter une Boisson
            </a>
        </div>
    </div>
    <div class="card-body py-4">
        <div class="container-fluid">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped table-sm">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="25%">
                        <col width="20%">
                        <col width="15%">
                        <col width="20%">
                    </colgroup>
                    <thead class="bg-orange-600 text-white">
                        <tr>
                            <th>#</th>
                            <th>Date de Création</th>
                            <th>Nom</th>
                            <th>Fournisseur</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT i.*,s.name as supplier FROM `item_list` i INNER JOIN supplier_list s ON i.supplier_id = s.id ORDER BY i.name ASC, s.name ASC");
                        while($row = $qry->fetch_assoc()):
                        ?>
                        <tr class="animate__animated animate__fadeInUp animate__delay-1s">
                            <td class="text-center"> <?php echo $i++; ?> </td>
                            <td> <?php echo date("d/m/Y H:i", strtotime($row['date_created'])) ?> </td>
                            <td> <?php echo $row['name'] ?> </td>
                            <td> <?php echo $row['supplier'] ?> </td>
                            <td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success rounded-pill">Actif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger rounded-pill">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-cogs"></i> Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-eye text-dark"></i> Voir
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-edit text-primary"></i> Modifier
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-trash text-danger"></i> Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer cette boisson définitivement ?","delete_category",[$(this).attr('data-id')])
        });
        $('#create_new').click(function(){
            uni_modal("<i class='fa fa-plus'></i> Ajouter une Boisson","maintenance/manage_item.php","mid-large");
        });
        $('.edit_data').click(function(){
            uni_modal("<i class='fa fa-edit'></i> Modifier les Détails","maintenance/manage_item.php?id="+$(this).attr('data-id'),"mid-large");
        });
        $('.view_data').click(function(){
            uni_modal("<i class='fa fa-box'></i> Détails de la Boisson","maintenance/view_item.php?id="+$(this).attr('data-id'),"");
        });
        $('.table td,.table th').addClass('py-2 px-3 align-middle');
        $('.table').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "responsive": true
        });
    });

    function delete_category($id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=delete_item",
            method:"POST",
            data:{id: $id},
            dataType:"json",
            error:function(err){
                console.log(err);
                alert_toast("Une erreur s'est produite.",'error');
                end_loader();
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload();
                }else{
                    alert_toast("Une erreur s'est produite.",'error');
                    end_loader();
                }
            }
        });
    }
</script>

<style>
    .card {
        border-radius: 15px;
    }

    .card-header {
        border-bottom: 3px solid #FFB84D;
        animation: juice-pour 1s ease-in-out infinite;
    }

    .table td, .table th {
        transition: background-color 0.5s ease;
    }

    .table tr:hover {
        background-color: #FFEB99;
    }

    .badge {
        font-size: 14px;
        padding: 6px 12px;
        text-transform: uppercase;
    }

    .dropdown-menu {
        width: 250px;
    }

    .dropdown-item {
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background-color: #FFE066;
        color: #FF5733;
    }

    .table {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    @keyframes juice-pour {
        0% { background-color: #FFB84D; }
        50% { background-color: #FF9F00; }
        100% { background-color: #FFB84D; }
    }

    /* Smooth slide-in effect for table rows */
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
