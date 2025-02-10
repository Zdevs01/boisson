<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-cash-register"></i> Gestion des Ventes</h3>
        <div class="card-tools">
            <a href="<?php echo base_url ?>admin/?page=sales/manage_sale" class="btn btn-flat btn-success">
                <i class="fas fa-plus-circle"></i> Nouvelle Vente
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-bordered table-striped">
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
                        <th>Date</th>
                        <th>Code Vente</th>
                        <th>Client</th>
                        <th>Articles</th>
                        <th>Montant (FCFA)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM `sales_list` ORDER BY `date_created` DESC");
                    while ($row = $qry->fetch_assoc()):
                        $row['items'] = count(explode(',', $row['stock_ids']));
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['date_created'])) ?></td>
                        <td><?php echo $row['sales_code'] ?></td>
                        <td><?php echo $row['client'] ?></td>
                        <td class="text-right"><?php echo number_format($row['items']) ?></td>
                        <td class="text-right"> <?php echo number_format($row['amount'], 2) ?> </td>
                        <td align="center">
                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?php echo base_url.'admin?page=sales/view_sale&id='.$row['id'] ?>">
                                    <i class="fa fa-eye text-dark"></i> Voir
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo base_url.'admin?page=sales/manage_sale&id='.$row['id'] ?>">
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

<!-- Ajout d'un graphique des ventes -->
<div class="card card-outline card-info mt-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistiques des Ventes</h3>
    </div>
    <div class="card-body">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer cette vente ?", "delete_sale", [$(this).attr('data-id')]);
        });
        $('.table').dataTable();

        // Ajout du graphique des ventes
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Montant des ventes (FCFA)',
                    data: [120000, 150000, 80000, 95000, 110000, 130000],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

    function delete_sale(id){
        start_loader();
        $.ajax({
            url: _base_url_+"classes/Master.php?f=delete_sale",
            method: "POST",
            data: { id: id },
            dataType: "json",
            success: function(resp){
                if (resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("Une erreur s'est produite.", 'error');
                    end_loader();
                }
            }
        });
    }
</script>
