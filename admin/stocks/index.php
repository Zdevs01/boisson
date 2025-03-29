<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks</title>

    <!-- Bootstrap 5 for modern UI components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- DataTables and Bootstrap integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Chart.js for modern graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom styles -->
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }

        h3 {
            font-weight: bold;
            color: #4e73df;
        }

        .low-stock {
            animation: blink 1s infinite alternate;
            color: #dc3545 !important;
            font-weight: bold;
        }

        @keyframes blink {
            0% { opacity: 1; }
            100% { opacity: 0.5; }
        }

        .table thead {
            background-color: #4e73df;
            color: white;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .dataTables_filter input {
            border-radius: 20px;
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .card-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            background-color: #4e73df;
            color: white;
        }

        .card-body {
            padding: 30px;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
            border-radius: 25px;
        }

        .btn-custom:hover {
            background-color: #218838;
        }

        #stockChart {
            margin-top: 30px;
            border-radius: 15px;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header">
                <h3><i class="fas fa-warehouse"></i> Gestion des Stocks de Boissons</h3>
            </div>
            <div class="card-body">
                <!-- Search Bar -->
                <div class="d-flex justify-content-between mb-3">
                    <input type="text" id="searchBox" class="form-control w-50" placeholder="Rechercher un produit..." />
                    <button class="btn btn-custom" id="exportBtn"><i class="fas fa-download"></i> Exporter les données</button>
                </div>

                <!-- Stock Table -->
                <table id="stockTable" class="table table-striped table-bordered shadow-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom de l'Article</th>
                            <th>Fournisseur</th>
                            <th>Description</th>
                            <th>Stock Disponible</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT i.*, s.name as supplier FROM item_list i INNER JOIN supplier_list s ON i.supplier_id = s.id ORDER BY name DESC");
                        $stockData = [];
                        while($row = $qry->fetch_assoc()):
                            $stock_initial = $row['qte'];
                            $in = $conn->query("SELECT IFNULL(SUM(quantity),0) as total FROM stock_list WHERE item_id = '{$row['id']}' AND type = 1")->fetch_array()['total'];
                            $out = $conn->query("SELECT IFNULL(SUM(quantity),0) as total FROM stock_list WHERE item_id = '{$row['id']}' AND type = 2")->fetch_array()['total'];
                            $stock_disponible = $stock_initial + $in - $out;
                            $class = ($stock_disponible < 1000) ? 'low-stock' : '';
                            $stockData[] = ["name" => $row['name'], "stock" => $stock_disponible];
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['supplier']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td class="text-right <?php echo $class; ?>"><?php echo number_format($stock_disponible); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Stock Chart -->
                <canvas id="stockChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            let table = $('#stockTable').DataTable({
                "searching": true, 
                "paging": true, 
                "ordering": true, 
                "info": true, 
                "lengthMenu": [10, 25, 50, 75, 100] 
            });

            // Search functionality
            $('#searchBox').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Prepare data for chart
            let stockData = <?php echo json_encode($stockData); ?>;
            let ctx = document.getElementById('stockChart').getContext('2d');

            // Create a modern and beautiful bar chart
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: stockData.map(item => item.name),
                    datasets: [{
                        label: 'Stock Disponible (L)',
                        data: stockData.map(item => item.stock),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' L';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.raw + ' L';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</body>
</html>
