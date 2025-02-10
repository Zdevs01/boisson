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
    
    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="salesChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="stockChart"></canvas>
        </div>
    </div>
    
    <script>
        var ctx1 = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Juin', 'Juil'],
                datasets: [{
                    label: 'Ventes',
                    data: [120, 190, 300, 500, 200, 300, 400],
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2
                }]
            }
        });
        
        var ctx2 = document.getElementById('stockChart').getContext('2d');
        var stockChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Produit A', 'Produit B', 'Produit C', 'Produit D'],
                datasets: [{
                    label: 'Stock Disponible',
                    data: [500, 700, 400, 600],
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2
                }]
            }
        });
    </script>
</body>
