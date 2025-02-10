<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "boisson");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Initialisation des ventes du jour
$total_day_sales = 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des ventes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Liste des ventes</h3>
        <div class="card-tools">
        
            <a href="<?php echo base_url ?>admin/?page=sales/manage_sale" class="btn btn-flat btn-primary">
                <span class="fas fa-plus"></span> Ajouter une commande
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
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date de création</th>
                        <th>Code vente</th>
                        <th>Nom du client</th>
                        <th>Numéro du client</th>
                        <th>Produit référence</th>
                        <th>Montant encaissé</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <?php 
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM `sales_list` ORDER BY `date_created` DESC");
                    while($row = $qry->fetch_assoc()):
                        $total_amount = 0;
                        if (!empty($row['stock_ids'])) {
                            $stock_ids = implode(',', array_map('intval', explode(',', $row['stock_ids'])));
                            $qry_items = $conn->query("SELECT total FROM `stock_list` WHERE id IN ($stock_ids)");
                            while($item = $qry_items->fetch_assoc()) {
                                $total_amount += $item['total'];
                            }
                        }
                        // Calcul du total des ventes du jour
                        if (date('Y-m-d') == date('Y-m-d', strtotime($row['date_created']))) {
                            $total_day_sales += $total_amount;
                        }
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td><?php echo $row['sales_code'] ?></td>
                            <td><?php echo $row['client'] ?></td>
                            <td><?php echo $row['num'] ?></td>
                            <td class="text-right"><?php echo number_format(count(explode(',', $row['stock_ids']))) ?></td>
                            <td class="text-right"><?php echo number_format($total_amount, 2) ?> XFA</td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="<?php echo base_url.'admin?page=sales/view_sale&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Voir</a>
                                  
                                  
                                  <div class="dropdown-divider"></div>
                                  
                                  <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Supprimer</a>
                              
                               
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total des ventes du jour :</th>
                        <th class="text-right" id="totalDaySales"><?php echo number_format($total_day_sales, 2) ?> XFA</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Section du graphique -->
<div class="container mt-4">
    <h3>Graphique des ventes (par jour)</h3>
    <canvas id="salesTrendChart"></canvas>
</div>

<?php
// Requête pour obtenir les ventes par jour
$days_sales = $conn->query("SELECT DATE(date_created) as day, SUM(total_amount) as total_sales FROM sales_list GROUP BY DATE(date_created)");
$days_sales_data = [];
while($row = $days_sales->fetch_assoc()) {
    $days_sales_data[] = $row;
}
?>

<script>
    // Préparation des données pour le graphique
    const daySalesData = <?php echo json_encode($days_sales_data); ?>;
    const labels = daySalesData.map(data => data.day);
    const salesData = daySalesData.map(data => parseFloat(data.total_sales));

    // Création du graphique
    const ctx = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ventes par jour',
                data: salesData,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Montant des ventes (XFA)'
                    }
                }
            }
        }
    });

    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer définitivement cet enregistrement de vente ?", "delete_sale", [$(this).attr('data-id')])
        });
        $('.table td, .table th').addClass('py-1 px-2 align-middle');
        $('.table').dataTable();
    });
</script>
</body>
</html>






























<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "boisson");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Initialisation des ventes du jour
$total_day_sales = 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des ventes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Liste des ventes</h3>
        <div class="card-tools">
        
            <a href="<?php echo base_url ?>admin/?page=sales/manage_sale" class="btn btn-flat btn-primary">
                <span class="fas fa-plus"></span> Ajouter une commande
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
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date de création</th>
                        <th>Code vente</th>
                        <th>Nom du client</th>
                        <th>Numéro du client</th>
                        <th>Produit référence</th>
                        <th>Montant encaissé</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <?php 
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM `sales_list` ORDER BY `date_created` DESC");
                    while($row = $qry->fetch_assoc()):
                        $total_amount = 0;
                        if (!empty($row['stock_ids'])) {
                            $stock_ids = implode(',', array_map('intval', explode(',', $row['stock_ids'])));
                            $qry_items = $conn->query("SELECT total FROM `stock_list` WHERE id IN ($stock_ids)");
                            while($item = $qry_items->fetch_assoc()) {
                                $total_amount += $item['total'];
                            }
                        }
                        // Calcul du total des ventes du jour
                        if (date('Y-m-d') == date('Y-m-d', strtotime($row['date_created']))) {
                            $total_day_sales += $total_amount;
                        }
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td><?php echo $row['sales_code'] ?></td>
                            <td><?php echo $row['client'] ?></td>
                            <td><?php echo $row['num'] ?></td>
                            <td class="text-right"><?php echo number_format(count(explode(',', $row['stock_ids']))) ?></td>
                            <td class="text-right"><?php echo number_format($total_amount, 2) ?> XFA</td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="<?php echo base_url.'admin?page=sales/view_sale&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Voir</a>
                                  
                                  
                                  <div class="dropdown-divider"></div>
                                  
                                  <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Supprimer</a>
                              
                               
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total des ventes du jour :</th>
                        <th class="text-right" id="totalDaySales"><?php echo number_format($total_day_sales, 2) ?> XFA</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Section du graphique -->
<div class="container mt-4">
    <h3>Graphique des ventes (par jour)</h3>
    <canvas id="salesTrendChart"></canvas>
</div>

<?php
// Requête pour obtenir les ventes par jour
$days_sales = $conn->query("SELECT DATE(date_created) as day, SUM(total_amount) as total_sales FROM sales_list GROUP BY DATE(date_created)");
$days_sales_data = [];
while($row = $days_sales->fetch_assoc()) {
    $days_sales_data[] = $row;
}
?>

<script>
    // Préparation des données pour le graphique
    const daySalesData = <?php echo json_encode($days_sales_data); ?>;
    const labels = daySalesData.map(data => data.day);
    const salesData = daySalesData.map(data => parseFloat(data.total_sales));

    // Création du graphique
    const ctx = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ventes par jour',
                data: salesData,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Montant des ventes (XFA)'
                    }
                }
            }
        }
    });

    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer définitivement cet enregistrement de vente ?", "delete_sale", [$(this).attr('data-id')])
        });
        $('.table td, .table th').addClass('py-1 px-2 align-middle');
        $('.table').dataTable();
    });
</script>
</body>
</html>
