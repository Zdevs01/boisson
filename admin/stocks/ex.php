<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Liste du stock en magasin <i class="fas fa-warehouse"></i></h3>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<!-- Recherche et table de "Vente Simple" -->
			<div class="search-container">
				<input type="text" id="searchVente" placeholder="Rechercher dans Vente Simple..." class="form-control mb-3">
			</div>
			<h4 class="text-primary">Produits - Vente Simple <i class="fas fa-boxes"></i></h4>
			<table id="tableVente" class="table table-bordered table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="25%">
					<col width="40%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead class="thead-dark">
					<tr>
						<th>#</th>
						<th>Nom du produit</th>
						<th>Description</th>
						<th>Stock restant (Sac/Kg)</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$qry = $conn->query("SELECT i.*,s.name as supplier FROM `item_list` i INNER JOIN supplier_list s ON i.supplier_id = s.id WHERE s.name = 'Vente Simple' ORDER BY `name` DESC");
					while($row = $qry->fetch_assoc()):
						$in = $conn->query("SELECT SUM(quantity) as total FROM stock_list WHERE item_id = '{$row['id']}' AND type = 1")->fetch_array()['total'];
						$out = $conn->query("SELECT SUM(quantity) as total FROM stock_list WHERE item_id = '{$row['id']}' AND type = 2")->fetch_array()['total'];

						$row['available'] = $in - $out;
						$row['qte'] = $row['qte'] - $out;
						$row['nbrs'] = $row['nbrs'] - $out;
						$stock_class = ($row['qte'] <= 0) ? 'stock-epuise' : '';
					?>
					<tr class="<?php echo $stock_class; ?>">
						<td class="text-center"><?php echo $i++; ?></td>
						<td><i class="fas fa-box-open"></i> <?php echo $row['name']; ?></td>
						<td><?php echo $row['description']; ?></td>
						<td class="text-center"><i class="fas fa-pallet"></i> <?php echo number_format($row['qte']); ?> Sac / <b><?php echo number_format($row['nbrs']); ?> KG</b></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>

			<!-- Recherche et table de "Production" -->
			<div class="search-container">
				<input type="text" id="searchProduction" placeholder="Rechercher dans Production..." class="form-control mt-4 mb-3">
			</div>
			<h4 class="text-primary">Produits - Production <i class="fas fa-industry"></i></h4>
			<table id="tableProduction" class="table table-bordered table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="25%">
					<col width="40%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead class="thead-dark">
					<tr>
						<th>#</th>
						<th>Nom du produit</th>
						<th>Description</th>
						<th>Stock restant (Sac/Kg)</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$j = 1;
					$qry = $conn->query("SELECT i.*,s.name as supplier FROM `item_list` i INNER JOIN supplier_list s ON i.supplier_id = s.id WHERE s.name = 'Production' ORDER BY `name` DESC");
					while($row = $qry->fetch_assoc()):
						$in = $conn->query("SELECT SUM(quantity) as total FROM stock_list WHERE item_id = '{$row['id']}' AND type = 1")->fetch_array()['total'];
						$out = $conn->query("SELECT SUM(quantity) as total FROM stock_list WHERE item_id = '{$row['id']}' AND type = 2")->fetch_array()['total'];

						$row['available'] = $in - $out;
						$row['qte'] = $row['qte'] - $out;
						$row['nbrs'] = $row['nbrs'] - $out;
						$stock_class = ($row['qte'] <= 0) ? 'stock-epuise' : '';
					?>
					<tr class="<?php echo $stock_class; ?>">
						<td class="text-center"><?php echo $j++; ?></td>
						<td><i class="fas fa-box-open"></i> <?php echo $row['name']; ?></td>
						<td><?php echo $row['description']; ?></td>
						<td class="text-center"><i class="fas fa-pallet"></i> <?php echo number_format($row['qte']); ?> Sac / <b><?php echo number_format($row['nbrs']); ?> KG</b></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Styles CSS -->
<style>
	/* Styles des tableaux et animations */
	.table, .table th, .table td {
		font-family: Arial, sans-serif;
		border: 1px solid #dee2e6;
		padding: 10px;
	}

	.table thead {
		background-color: #343a40;
		color: #fff;
	}

	.table-hover tbody tr:hover {
		background-color: #f8f9fa;
	}

	.stock-epuise {
		animation: clignoter 1s infinite;
		background-color: #ffcccc;
	}

	.card {
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		border-radius: 10px;
	}

	.card-title {
		color: #007bff;
		font-weight: bold;
	}

	.search-container input {
		width: 100%;
		padding: 8px;
		border-radius: 5px;
	}

</style>

<!-- Script JS pour le filtrage et les animations -->
<script>
	// Filtrage dans les tableaux
	document.querySelector("#searchVente").addEventListener("input", function () {
		let search = this.value.toLowerCase();
		let rows = document.querySelectorAll("#tableVente tbody tr");
		rows.forEach(row => {
			row.style.display = row.innerText.toLowerCase().includes(search) ? "" : "none";
		});
	});

	document.querySelector("#searchProduction").addEventListener("input", function () {
		let search = this.value.toLowerCase();
		let rows = document.querySelectorAll("#tableProduction tbody tr");
		rows.forEach(row => {
			row.style.display = row.innerText.toLowerCase().includes(search) ? "" : "none";
		});
	});
</script>
