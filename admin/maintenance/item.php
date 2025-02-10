<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-wine-bottle"></i> Liste des Boissons</h3>
        <div class="card-tools">
            <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary">
                <i class="fas fa-plus-circle"></i> Ajouter une Boisson
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="25%">
                    <col width="20%">
                    <col width="15%">
                    <col width="20%">
                </colgroup>
                <thead class="bg-primary text-white">
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
                    <tr>
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
                        <td align="center">
                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Actions
                                <span class="sr-only">Basculer Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
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
<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer cette boisson définitivement ?","delete_category",[$(this).attr('data-id')])
        })
        $('#create_new').click(function(){
            uni_modal("<i class='fa fa-plus'></i> Ajouter une Boisson","maintenance/manage_item.php","mid-large")
        })
        $('.edit_data').click(function(){
            uni_modal("<i class='fa fa-edit'></i> Modifier les Détails","maintenance/manage_item.php?id="+$(this).attr('data-id'),"mid-large")
        })
        $('.view_data').click(function(){
            uni_modal("<i class='fa fa-box'></i> Détails de la Boisson","maintenance/view_item.php?id="+$(this).attr('data-id'),"")
        })
        $('.table td,.table th').addClass('py-2 px-3 align-middle')
        $('.table').dataTable();
    })

    function delete_category($id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=delete_item",
            method:"POST",
            data:{id: $id},
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("Une erreur s'est produite.",'error');
                end_loader();
            },
            success:function(resp){
                if(typeof resp== 'object' && resp.status == 'success'){
                    location.reload();
                }else{
                    alert_toast("Une erreur s'est produite.",'error');
                    end_loader();
                }
            }
        })
    }
</script>