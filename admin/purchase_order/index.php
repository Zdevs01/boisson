<div class="card card-outline card-primary border-0 shadow-lg rounded">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-cogs"></i> Liste des Commandes d'Achat</h3>
        <div class="card-tools mt-2 mt-md-0">
            <a href="<?php echo base_url ?>admin/?page=purchase_order/manage_po" class="btn btn-success btn-flat btn-sm">
                <span class="fas fa-plus-circle"></span> Créer Nouveau
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-nowrap">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="20%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>#</th>
                            <th>Date de Création</th>
                            <th>Code PO</th>
                            <th>Fournisseur</th>
                            <th>Articles</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT p.*, s.name as supplier FROM `purchase_order_list` p INNER JOIN supplier_list s ON p.supplier_id = s.id ORDER BY p.`date_created` DESC");
                        while ($row = $qry->fetch_assoc()):
                            $row['items'] = $conn->query("SELECT COUNT(item_id) AS `items` FROM `po_items` WHERE po_id = '{$row['id']}'")->fetch_assoc()['items'];
                        ?>
                            <tr class="table-row">
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("d/m/Y H:i", strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['po_code'] ?></td>
                                <td><?php echo $row['supplier'] ?></td>
                                <td class="text-right"><?php echo number_format($row['items']) ?></td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 0): ?>
                                        <span class="badge badge-primary rounded-pill">En Attente <span class="emoji">⏳</span></span>
                                    <?php elseif ($row['status'] == 1): ?>
                                        <span class="badge badge-warning rounded-pill">Partiellement Reçu <span class="emoji">📦</span></span>
                                    <?php elseif ($row['status'] == 2): ?>
                                        <span class="badge badge-success rounded-pill">Reçu <span class="emoji">✅</span></span>
                                    <?php else: ?>
                                        <span class="badge badge-danger rounded-pill">N/A <span class="emoji">❌</span></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <span class="fas fa-cogs"></span> Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <?php if ($row['status'] == 0): ?>
                                                <a class="dropdown-item" href="<?php echo base_url.'admin?page=receiving/manage_receiving&po_id='.$row['id'] ?>">
                                                    <span class="fa fa-boxes text-dark"></span> Recevoir
                                                </a>
                                                <div class="dropdown-divider"></div>
                                            <?php endif; ?>
                                            <a class="dropdown-item" href="<?php echo base_url.'admin?page=purchase_order/view_po&id='.$row['id'] ?>">
                                                <span class="fa fa-eye text-dark"></span> Voir <span class="emoji">👁️</span>
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo base_url.'admin?page=purchase_order/manage_po&id='.$row['id'] ?>">
                                                <span class="fa fa-edit text-primary"></span> Modifier <span class="emoji">✏️</span>
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                                <span class="fa fa-trash text-danger"></span> Supprimer <span class="emoji">🗑️</span>
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
            _conf("Êtes-vous sûr de vouloir supprimer cette Commande d'Achat définitivement ?", "delete_po", [$(this).attr('data-id')])
        });
        $('.table').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "responsive": true,
            "columnDefs": [
                { 
                    "targets": [0, 1, 2, 3], 
                    "className": "text-center" 
                },
                { 
                    "targets": [4, 5, 6], 
                    "className": "text-right"
                }
            ]
        });
    });

    function delete_po(id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_po",
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
