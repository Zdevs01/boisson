<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        $('.info-box').each(function(index) {
            $(this).delay(150 * index).queue(function(next) {
                $(this).addClass('animate__animated animate__fadeInUp');
                next();
            });
        });
    });
</script>

<body>
    <h1 class="text-center animate__animated animate__fadeInDown text-primary">Bienvenue sur DrinkFlow </h1>
    <hr class="mb-4">
    <div class="row">
        <?php 
            $info_boxes = [
                ['Dossiers de Commandes', 'purchase_order_list', 'fa-file-alt', 'info'],
                ['Réception des Stocks', 'receiving_list', 'fa-boxes', 'warning'],
                ['Commandes en Retard', 'back_order_list', 'fa-clock', 'primary'],
                ['Retours', 'return_list', 'fa-undo-alt', 'danger'],
                ['Dossiers de Vente', 'sales_list', 'fa-file-invoice-dollar', 'success'],
                ['Fournisseurs', 'supplier_list', 'fa-truck', 'dark', "WHERE `status` = 1"],
                ['Articles', 'item_list', 'fa-cubes', 'secondary', "WHERE `status` = 1"],
            ];
            
            foreach ($info_boxes as $box) {
                list($title, $table, $icon, $color, $condition) = array_pad($box, 5, "");
                $query = "SELECT * FROM `$table` $condition";
                $count = $conn->query($query)->num_rows;
        ?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-light border rounded shadow-sm">
                <span class="info-box-icon bg-<?= $color ?> text-white elevation-1 animate__animated animate__pulse animate__infinite">
                    <i class="fas <?= $icon ?>"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text animate__animated animate__fadeInLeft text-dark font-weight-bold">
                        <?= $title ?>
                    </span>
                    <span class="info-box-number text-right font-weight-bold animate__animated animate__fadeInRight text-dark">
                        <?= $count ?>
                    </span>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php
include 'db_connect.php'; // Connexion à la base de données

// Vérification de la connexion
if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

// Récupérer les ventes par mois
$salesQuery = "SELECT DATE_FORMAT(sale_date, '%b') as month, SUM(total_price) as total 
               FROM sales GROUP BY month ORDER BY MIN(sale_date)";
$salesStmt = $conn->prepare($salesQuery);
$salesStmt->execute();
$salesResults = $salesStmt->fetchAll(PDO::FETCH_ASSOC);

$salesMonths = [];
$salesData = [];
foreach ($salesResults as $row) {
    $salesMonths[] = $row['month'];
    $salesData[] = $row['total'];
}

// Récupérer le stock disponible par produit
$stockQuery = "SELECT item_id, SUM(quantity) as total_stock FROM stock_list GROUP BY item_id";
$stockStmt = $conn->prepare($stockQuery);
$stockStmt->execute();
$stockResults = $stockStmt->fetchAll(PDO::FETCH_ASSOC);

$stockLabels = [];
$stockData = [];
foreach ($stockResults as $row) {
    $stockLabels[] = "Produit " . $row['item_id']; 
    $stockData[] = $row['total_stock'];
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-shadow"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #1e1e2f;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        canvas {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }
    </style>
 <div class="container">
    <h2 class="mt-4 text-center" style="color: #1e1e2f; " >📊 Tableau de Bord DrinkFlow</h2>

    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="salesChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="stockChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var ctx1 = document.getElementById('salesChart').getContext('2d');
    var gradient1 = ctx1.createLinearGradient(0, 0, 0, 400);
    gradient1.addColorStop(0, 'rgba(0, 123, 255, 0.5)');
    gradient1.addColorStop(1, 'rgba(0, 123, 255, 0.1)');

    var salesChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($salesMonths); ?>,
            datasets: [{
                label: 'Ventes mensuelles 💰',
                data: <?php echo json_encode($salesData); ?>,
                backgroundColor: gradient1,
                borderColor: '#007bff',
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#007bff',
                pointHoverRadius: 7,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: '#fff' } }
            },
            scales: {
                x: { ticks: { color: '#fff' } },
                y: { ticks: { color: '#fff' } }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });

    var ctx2 = document.getElementById('stockChart').getContext('2d');
    var gradient2 = ctx2.createLinearGradient(0, 0, 0, 400);
    gradient2.addColorStop(0, 'rgba(40, 167, 69, 0.5)');
    gradient2.addColorStop(1, 'rgba(40, 167, 69, 0.1)');

    var stockChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($stockLabels); ?>,
            datasets: [{
                label: 'Stock Disponible 📦',
                data: <?php echo json_encode($stockData); ?>,
                backgroundColor: gradient2,
                borderColor: '#28a745',
                borderWidth: 2,
                hoverBackgroundColor: '#45d86c'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: '#fff' } }
            },
            scales: {
                x: { ticks: { color: '#fff' } },
                y: { ticks: { color: '#fff' } }
            },
            animation: {
                duration: 2500,
                easing: 'easeInOutQuart'
            }
        }
    });
});
</script>


</body>
