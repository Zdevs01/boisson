<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Liste des Retours de Marchandise</h3>
        <div class="card-tools">
			<a href="<?php echo base_url ?>admin/?page=return/manage_return" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Créer un Nouveau Retour</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-striped">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="25%">
                        <col width="25%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date de Création</th>
                            <th>Code de Retour</th>
                            <th>Fournisseur</th>
                            <th>Boissons Retournées</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT r.*, s.name as supplier FROM `return_list` r 
                        INNER JOIN supplier_list s ON r.supplier_id = s.id 
                        ORDER BY r.`date_created` DESC");
                        while($row = $qry->fetch_assoc()):
                            $row['items'] = count(explode(',', $row['stock_ids']));
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['return_code'] ?></td>
                                <td><?php echo $row['supplier'] ?></td>
                                <td class="text-right"><?php echo number_format($row['items']) ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Actions
                                        <span class="sr-only">Basculer le menu</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="<?php echo base_url.'admin?page=return/view_return&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>">
                                            <span class="fa fa-eye text-dark"></span> Voir
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo base_url.'admin?page=return/manage_return&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>">
                                            <span class="fa fa-edit text-primary"></span> Modifier
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                            <span class="fa fa-trash text-danger"></span> Supprimer
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
			_conf("Êtes-vous sûr de vouloir supprimer définitivement cet enregistrement de retour ?", "delete_return", [$(this).attr('data-id')])
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_return($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_return",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("Une erreur est survenue.", 'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("Une erreur est survenue.", 'error');
					end_loader();
				}
			}
		})
	}
</script>
