<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Liste des commandes en attente</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="20%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                </colgroup>
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Date de création</th>
                        <th>Code BO</th>
                        <th>Fournisseur</th>
                        <th>Articles</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $qry = $conn->query("SELECT p.*, s.name as supplier FROM `back_order_list` p INNER JOIN supplier_list s ON p.supplier_id = s.id ORDER BY p.`date_created` DESC");
                    while($row = $qry->fetch_assoc()):
                        $row['items'] = $conn->query("SELECT COUNT(item_id) as `items` FROM `bo_items` WHERE bo_id = '{$row['id']}' ")->fetch_assoc()['items'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($row['date_created'])) ?></td>
                            <td><?php echo $row['bo_code'] ?></td>
                            <td><?php echo $row['supplier'] ?></td>
                            <td class="text-right"><?php echo number_format($row['items']) ?></td>
                            <td class="text-center">
                                <?php if($row['status'] == 0): ?>
                                    <span class="badge badge-primary">En attente</span>
                                <?php elseif($row['status'] == 1): ?>
                                    <span class="badge badge-warning">Partiellement reçu</span>
                                <?php elseif($row['status'] == 2): ?>
                                    <span class="badge badge-success">Reçu</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php if($row['status'] == 0): ?>
                                            <a class="dropdown-item" href="<?php echo base_url.'admin?page=receiving/manage_receiving&bo_id='.$row['id'] ?>">
                                                <i class="fa fa-boxes text-primary"></i> Réceptionner
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        <?php endif; ?>
                                        <a class="dropdown-item" href="<?php echo base_url.'admin?page=back_order/view_bo&id='.$row['id'] ?>">
                                            <i class="fa fa-eye text-dark"></i> Voir
                                        </a>
                                    </div>
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
            _conf("Êtes-vous sûr de vouloir supprimer cette commande en attente définitivement ?","delete_bo",[$(this).attr('data-id')])
        })
        
        $('.view_details').click(function(){
            uni_modal("Détails du paiement","transaction/view_payment.php?id="+$(this).attr('data-id'),'mid-large')
        })
        
        $('.table').dataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/French.json'
            }
        });
    });
    
    function delete_bo(id){
        start_loader();
        $.ajax({
            url: _base_url_+"classes/Master.php?f=delete_bo",
            method: "POST",
            data: {id: id},
            dataType: "json",
            error: function(err){
                console.log(err);
                alert_toast("Une erreur s'est produite.",'error');
                end_loader();
            },
            success: function(resp){
                if(typeof resp == 'object' && resp.status == 'success'){
                    location.reload();
                } else {
                    alert_toast("Une erreur s'est produite.",'error');
                    end_loader();
                }
            }
        })
    }
</script>
