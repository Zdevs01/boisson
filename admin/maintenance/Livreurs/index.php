<!-- Carte de Gestion des Ventes -->
<div class="card card-outline card-primary shadow-lg animate__animated animate__fadeInUp">
    <div class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white">
        <h3 class="card-title"><i class="fas fa-cash-register"></i> Gestion des Ventes</h3>
        <div class="card-tools">
            <a href="<?php echo base_url ?>admin/?page=sales/manage_sale" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-plus-circle"></i> Nouvelle Vente
            </a>
        </div>
    </div>
    <div class="card-body bg-light">
        <div class="container-fluid">
            <table class="table table-bordered table-hover table-striped">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="20%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                </colgroup>
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Code Vente</th>
                        <th>Client</th>
                        <th>Articles</th>
                        <th>Avance (€)</th>
                       
                        <th>Montant Net a payer (€)</th>
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
                    <tr class="animate__animated animate__fadeIn">
                        <td class="text-center font-weight-bold"><?php echo $i++; ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['date_created'])) ?></td>
                        <td class="text-uppercase font-weight-bold"><?php echo $row['sales_code'] ?></td>
                        <td><?php echo $row['client'] ?></td>
                        <td class="text-right text-primary font-weight-bold"><?php echo number_format($row['items']) ?></td>
                        <td class="text-right text-primary font-weight-bold"><?php echo number_format($row['remarks']) ?> €</td>
                       

                        <td class="text-right text-success font-weight-bold"> <?php echo number_format($row['amount'], 2) ?> €</td>
                        <td align="center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-dark" href="<?php echo base_url.'admin?page=sales/view_sale&id='.$row['id'] ?>">
                                        <i class="fa fa-eye text-dark"></i> Voir
                                    </a>
                                    <a class="dropdown-item text-primary" href="<?php echo base_url.'admin?page=sales/manage_sale&id='.$row['id'] ?>">
                                        <i class="fa fa-edit text-primary"></i> Modifier
                                    </a>
                                    <a class="dropdown-item text-danger delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-trash text-danger"></i> Supprimer
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
<!-- Carte des Statistiques des Ventes - Version Futuriste -->
<div class="card card-outline card-info shadow-lg mt-4 animate__animated animate__fadeInUp">
    <div class="card-header bg-gradient-dark text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-chart-line"></i> Statistiques des Ventes</h3>
    </div>
    <div class="card-body bg-dark text-white">
        <canvas id="salesChart" style="height: 350px;"></canvas>
    </div>
</div>

<?php 
$sales_data = [];
$labels = [];
$amounts = [];

$qry = $conn->query("SELECT DATE_FORMAT(date_created, '%b %Y') as month, SUM(amount) as total FROM sales_list GROUP BY month ORDER BY date_created ASC");
while ($row = $qry->fetch_assoc()) {
    $labels[] = $row['month'];
    $amounts[] = $row['total'];
}

$labels_json = json_encode($labels);
$amounts_json = json_encode($amounts);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("salesChart").getContext("2d");
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(0, 255, 255, 0.5)");
    gradient.addColorStop(1, "rgba(0, 0, 128, 0.1)");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: <?php echo $labels_json; ?>,
            datasets: [{
                label: "Ventes (€)",
                data: <?php echo $amounts_json; ?>,
                backgroundColor: gradient,
                borderColor: "#00FFFF",
                borderWidth: 2,
                hoverBackgroundColor: "rgba(0, 255, 255, 0.8)",
                hoverBorderColor: "#FFFFFF",
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 2000,
                easing: "easeInOutQuart"
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(255, 255, 255, 0.2)"
                    }
                },
                x: {
                    grid: {
                        color: "rgba(255, 255, 255, 0.2)"
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: "#FFFFFF"
                    }
                }
            }
        }
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById("salesChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: <?php echo $labels_json; ?>,
                datasets: [{
                    label: "Ventes (€)",
                    data: <?php echo $amounts_json; ?>,
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 2,
                    hoverBackgroundColor: "rgba(255, 99, 132, 0.8)",
                    hoverBorderColor: "rgba(255, 99, 132, 1)",
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1500,
                    easing: "easeInOutBounce"
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(200, 200, 200, 0.2)"
                        }
                    },
                    x: {
                        grid: {
                            color: "rgba(200, 200, 200, 0.2)"
                        }
                    }
                }
            }
        });
    });
</script>

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