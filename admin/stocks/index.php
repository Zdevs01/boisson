<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .low-stock {
            animation: blink 1s infinite alternate;
            color: red !important;
            font-weight: bold;
        }
        @keyframes blink {
            0% { opacity: 1; }
            100% { opacity: 0.5; }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3 class="text-primary"><i class="fas fa-warehouse"></i> Gestion des Stocks</h3>
        <input type="text" id="searchBox" class="form-control mb-3" placeholder="Rechercher un produit...">
        
        <table id="stockTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom de l'Article</th>
                    <th>Fournisseur</th>
                    <th>Description</th>
                    <th>Stock Disponible (L)</th>
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
                        <td class="text-center"> <?php echo $i++; ?> </td>
                        <td> <?php echo $row['name']; ?> </td>
                        <td> <?php echo $row['supplier']; ?> </td>
                        <td> <?php echo $row['description']; ?> </td>
                        <td class="text-right <?php echo $class; ?>"> <?php echo number_format($stock_disponible); ?> </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <canvas id="stockChart"></canvas>
    </div>
    
    <script>
        $(document).ready(function() {
            let table = $('#stockTable').DataTable();
            $('#searchBox').on('keyup', function() {
                table.search(this.value).draw();
            });
            
            let stockData = <?php echo json_encode($stockData); ?>;
            let ctx = document.getElementById('stockChart').getContext('2d');
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
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
</body>
</html>
