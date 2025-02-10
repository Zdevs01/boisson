<div class="card card-outline card-primary border-0 shadow-lg rounded p-2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">Liste des Commandes Reçues</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <colgroup>
                        <col width="5%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                        <col width="20%">
                    </colgroup>
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Date de Création</th>
                            <th>Provenance</th>
                            <th>Articles</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM `receiving_list` ORDER BY `date_created` DESC");
                        while ($row = $qry->fetch_assoc()):
                            $row['items'] = explode(',', $row['stock_ids']);
                            $code = ($row['from_order'] == 1)
                                ? $conn->query("SELECT po_code FROM `purchase_order_list` WHERE id='{$row['form_id']}'")->fetch_assoc()['po_code']
                                : $conn->query("SELECT bo_code FROM `back_order_list` WHERE id='{$row['form_id']}'")->fetch_assoc()['bo_code'];
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($row['date_created'])) ?></td>
                                <td><?php echo $code ?></td>
                                <td class="text-right"><?php echo number_format(count($row['items'])) ?></td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <span class="fas fa-cogs"></span> Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?php echo base_url.'admin?page=receiving/view_receiving&id='.$row['id'] ?>">
                                                <span class="fa fa-eye text-dark"></span> Voir
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url.'admin?page=receiving/manage_receiving&id='.$row['id'] ?>">
                                                <span class="fa fa-edit text-primary"></span> Modifier
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                                <span class="fa fa-trash text-danger"></span> Supprimer
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
</div>

<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer cette Commande Reçue définitivement ?", "delete_receiving", [$(this).attr('data-id')])
        });
        $('.table').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true
        });
    });

    function delete_receiving(id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_receiving",
            method: "POST",
            data: {id: id},
            dataType: "json",
            error: function(err){
                console.log(err);
                alert_toast("Une erreur est survenue.", 'error');
                end_loader();
            },
            success: function(resp){
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("Une erreur est survenue.", 'error');
                    end_loader();
                }
            }
        });
    }
</script>
