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
                        <th>Montant (€)</th>
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


<?php 
// Récupération des données pour le graphique
$sales_data = [];
$labels = [];
$amounts = [];

$qry = $conn->query("SELECT DATE_FORMAT(date_created, '%b %Y') as month, SUM(amount) as total FROM sales_list GROUP BY month ORDER BY date_created ASC");
while ($row = $qry->fetch_assoc()) {
    $labels[] = $row['month'];
    $amounts[] = $row['total'];
}

// Transformation des données en format JSON pour JavaScript
$labels_json = json_encode($labels);
$amounts_json = json_encode($amounts);
?>

<div class="card card-outline card-info mt-4">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistiques des Ventes</h3>
    </div>
    <div class="card-body">
        <canvas id="salesChart" style="height: 300px;"></canvas>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer cette vente ?", "delete_sale", [$(this).attr('data-id')]);
        });

        $('.table').dataTable();

        // Récupération des données PHP
        var labels = <?php echo $labels_json; ?>;
        var amounts = <?php echo $amounts_json; ?>;

        // Création du graphique des ventes
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Montant des ventes (€)',
                    data: amounts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',   // Rouge (vin)
                        'rgba(54, 162, 235, 0.6)',  // Bleu (eau)
                        'rgba(255, 206, 86, 0.6)',  // Jaune (bière)
                        'rgba(75, 192, 192, 0.6)',  // Vert (jus)
                        'rgba(153, 102, 255, 0.6)', // Violet (liqueurs)
                        'rgba(255, 159, 64, 0.6)'   // Orange (sodas)
                    ],
                    borderColor: 'rgba(0, 0, 0, 0.3)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + "€";
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutBounce'
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