<!-- Inclusion des fichiers CSS et JS pour DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- Carte du suivi des clients -->

<!-- Carte du suivi des clients -->
<div class="card card-outline card-primary shadow-lg animate__animated animate__fadeIn">
    <div class="card-header bg-gradient-primary text-white">
        <h3 class="card-title">Suivi des Clients</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped" id="clientsTable">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Nom du Client</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Somme Versée (€)</th>
                        <th>Commandes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $clients = $conn->query("SELECT DISTINCT client, num, email FROM sales_list ORDER BY date_created ASC");
                    
                    while ($client = $clients->fetch_assoc()):
                        $total_paid = $conn->query("SELECT SUM(amount) AS total FROM sales_list WHERE client = '{$client['client']}'")->fetch_assoc()['total'] ?? 0;
                        $order_count = $conn->query("SELECT COUNT(id) AS total FROM sales_list WHERE client = '{$client['client']}'")->fetch_assoc()['total'] ?? 0;
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td class="text-center"><img src="./default.png" class="img-avatar img-thumbnail p-0 border-2" alt="avatar_client"></td>
                        <td><?php echo $client['client'] ?></td>
                        <td><?php echo $client['num'] ?></td>
                        <td><?php echo $client['email'] ?></td>
                        <td class="text-right"><?php echo number_format($total_paid, 2) ?> €</td>
                        <td class="text-center"><?php echo $order_count ?> Commandes</td>
                        <td align="center">
                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Actions</button>
                            <div class="dropdown-menu">
                                
                                <a class="dropdown-item contact_client" href="#"><i class="fa fa-envelope text-success"></i> Contacter</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Initialisation de DataTables -->
<script>
    $(document).ready(function() {
        $('#clientsTable').DataTable({
            "searching": true, // Active la recherche dans le tableau
            "paging": true, // Active la pagination
            "ordering": true, // Active le tri
            "info": true, // Affiche des informations sur le tableau
            "lengthMenu": [10, 25, 50, 75, 100] // Nombre de lignes par page
        });
    });
</script>

<?php 
// Préparation des données pour le graphique des ventes
$labels = [];
$amounts = [];
$qry = $conn->query("SELECT DATE_FORMAT(date_created, '%b %Y') as month, SUM(amount) as total FROM sales_list GROUP BY month ORDER BY date_created ASC");
while ($row = $qry->fetch_assoc()) {
    $labels[] = $row['month'];
    $amounts[] = $row['total'];
}
$labels_json = json_encode($labels);
$amounts_json = json_encode($amounts);

// Préparation des données pour le graphique des clients
$clients_labels = [];
$clients_counts = [];
$qry_clients = $conn->query("SELECT DATE_FORMAT(date_created, '%b %Y') as month, COUNT(DISTINCT client) as total FROM sales_list GROUP BY month ORDER BY date_created ASC");
while ($row = $qry_clients->fetch_assoc()) {
    $clients_labels[] = $row['month'];
    $clients_counts[] = $row['total'];
}
$clients_labels_json = json_encode($clients_labels);
$clients_counts_json = json_encode($clients_counts);
?>

<!-- Graphique des ventes -->
<div class="card card-outline card-info shadow-lg mt-4 animate__animated animate__fadeInUp">
    <div class="card-header bg-gradient-info text-white">
        <h3 class="card-title"><i class="fas fa-chart-line"></i> Évolution des Ventes</h3>
    </div>
    <div class="card-body">
        <canvas id="salesChart" style="height: 300px;"></canvas>
    </div>
</div>

<!-- Graphique du nombre de clients -->
<div class="card card-outline card-success shadow-lg mt-4 animate__animated animate__fadeInUp">
    <div class="card-header bg-gradient-success text-white">
        <h3 class="card-title"><i class="fas fa-users"></i> Évolution des Clients</h3>
    </div>
    <div class="card-body">
        <canvas id="clientsChart" style="height: 300px;"></canvas>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Graphique des ventes
        var ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo $labels_json; ?>,
                datasets: [{
                    label: 'Montant des ventes (€)',
                    data: <?php echo $amounts_json; ?>,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {responsive: true, maintainAspectRatio: false}
        });

        // Graphique du nombre de clients
        var ctx2 = document.getElementById('clientsChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?php echo $clients_labels_json; ?>,
                datasets: [{
                    label: 'Nombre de Clients',
                    data: <?php echo $clients_counts_json; ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.5)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {responsive: true, maintainAspectRatio: false}
        });
    });
</script>
