<?php
require 'includes/db.php'; // Connexion à la base de données

// Récupérer tous les produits
$sql = "SELECT * FROM produits";
$stmt = $pdo->query($sql);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
// Récupérer la page et l'action dans l'URL
$page = $_GET['page'] ?? 'accueil'; // Par défaut "accueil" si aucun paramètre "page"
$action = $_GET['action'] ?? null;

// Afficher le contenu en fonction des paramètres
if ($page === 'produit') {
    switch ($action) {
        case 'stock':
            include './produit/index.php';
            break;
        case 'ajouter_produit':
            include './produit/ajouter_produit.php';
            break;
        case 'passer_commande':
            include './produit/passer_commande.php';
            break;
        case 'liste_commandes':
            include './produit/commandes/liste_commandes.php';
            break;
        default:
            echo '<h1>Bienvenue dans la section Produit</h1>';
            echo '<p>Sélectionnez une action dans le menu ci-dessus.</p>';
            break;
    }
} else {
    echo '<h1>Bienvenue sur le tableau de bord</h1>';
    echo '<p>Sélectionnez une page dans le menu principal.</p>';
}
?>
<nav>
    <ul>
      
        <li><a href="?page=produit&action=ajouter_produit">Ajouter un Produit</a></li>
        <li><a href="?page=produit&action=passer_commande">Reduire Commande</a></li>
        
    </ul>
</nav>

<?php
require 'includes/db.php'; // Connexion à la base de données

$message = '';
$message_type = '';

// Ajouter une quantité au stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_stock'])) {
    $id_produit = intval($_POST['id_produit']);
    $quantite_ajout = floatval($_POST['quantite_ajout']);

    $stmt = $pdo->prepare("UPDATE produits SET quantite_kg = quantite_kg + :quantite_ajout WHERE id = :id_produit");
    $stmt->execute(['quantite_ajout' => $quantite_ajout, 'id_produit' => $id_produit]);

    // Afficher un message de confirmation
    $message = "Stock ajouté avec succès pour le produit sélectionné.";
    $message_type = 'success';
}

// Récupérer tous les produits
$sql = "SELECT * FROM produits";
$stmt = $pdo->query($sql);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            z-index: 1050;
        }
        .form-popup.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
        .overlay.active {
            display: block;
        }
    </style>
<body>
<div class="container my-4">
    <!-- Titre Principal -->
     <!-- Message -->
     <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message_type; ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h1 class="text-center text-primary mb-4">Gestion du Stock</h1>

<!-- Barre de recherche -->
<div class="input-group mb-3">
    <input type="text" id="searchBar" class="form-control" placeholder="Rechercher un produit...">
    <span class="input-group-text"><i class="bi bi-search"></i></span>
</div>

<!-- Tableau des Produits -->
<div class="table-responsive">
    <table class="table table-hover align-middle" id="produitTable">
        <thead class="table-dark">
        <tr>
            <th>Nom <i class="bi bi-box"></i></th>
            <th>Poids du Sac (kg) <i class="bi bi-weight"></i></th>
            <th>Quantité (kg) <i class="bi bi-stack"></i></th>
            <th>Quantité Restante (Sacs) <i class="bi bi-cart"></i></th>
            <th>Prix Achat (FCFA) <i class="bi bi-cash-stack"></i></th>
            <th>Prix Vente (FCFA) <i class="bi bi-tag"></i></th>
            <th>Bénéfice Attendu <i class="bi bi-graph-up"></i></th>
            <th>Actions <i class="bi bi-tools"></i></th>
        </tr>
        </thead>
        <tbody>
        <?php
foreach ($produits as $produit): 
    $poids_sac = $produit['poids_sac'];
    $quantite_kg = $produit['quantite_kg'];
    $benefice = $quantite_kg * ($produit['prix_vente'] - $produit['prix_achat']);
?>
<tr class="<?= $quantite_kg < 200 ? 'low-stock' : '' ?>">
    <td><?= htmlspecialchars($produit['nom']) ?></td>
    <td><?= number_format($poids_sac, 2) ?> kg</td>
    <td><?= number_format($quantite_kg, 2) ?> kg</td>
    <td>
        <?= floor($quantite_kg / $poids_sac) ?> sacs complets
        <?= number_format(fmod($quantite_kg / $poids_sac, 1) * $poids_sac, 2) > 0 ? "et " . number_format(fmod($quantite_kg / $poids_sac, 1) * $poids_sac, 2) . " kg" : "" ?>
    </td>
    <td><?= number_format($produit['prix_achat'], 2, ',', ' ') ?> FCFA</td>
    <td><?= number_format($produit['prix_vente'], 2, ',', ' ') ?> FCFA</td>
    <td class="text-success"><?= number_format($benefice, 2, ',', ' ') ?> FCFA</td>
    <td>
        <button class="btn btn-sm btn-primary ajouter-stock-btn" data-id="<?= $produit['id'] ?>" data-nom="<?= htmlspecialchars($produit['nom']) ?>">
            <i class="bi bi-plus-circle"></i> Ajouter Stock
        </button>
    </td>
</tr>
<?php endforeach; ?>

        </tbody>
    </table>
</div>
</div>

<style>
body {
    background-color: #f4f4f4;
    font-family: 'Roboto', sans-serif;
}

.table thead th {
    text-transform: uppercase;
    font-weight: bold;
}

.low-stock {
    animation: blink 1s infinite;
    background-color: #ffcccc;
}

@keyframes blink {
    0% {
        background-color: #ffcccc;
    }
    50% {
        background-color: #ff4d4d;
    }
    100% {
        background-color: #ffcccc;
    }
}

.table th, .table td {
    vertical-align: middle;
}

.input-group-text {
    background-color: #007bff;
    color: white;
}

.input-group-text i {
    font-size: 1.2rem;
}

.btn-primary {
    background-color: #007bff;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchBar = document.getElementById('searchBar');
    const produitTable = document.getElementById('produitTable');

    // Filtrage des produits
    searchBar.addEventListener('keyup', () => {
        const searchTerm = searchBar.value.toLowerCase();
        const rows = produitTable.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const productName = row.cells[0].textContent.toLowerCase();
            row.style.display = productName.includes(searchTerm) ? '' : 'none';
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"></script>
<!-- Formulaire Popup -->
<div class="overlay" id="overlay"></div>
<div class="form-popup" id="stockForm">
    <form method="POST" class="p-4">
        <h4 id="formTitle" class="mb-4">Ajouter au Stock</h4>
        <input type="hidden" name="id_produit" id="idProduit">
        <div class="mb-3">
            <label for="quantiteAjout" class="form-label">Quantité à Ajouter (kg)</label>
            <input type="number" step="0.01" name="quantite_ajout" id="quantiteAjout" class="form-control" required>
        </div>
        <button type="submit" name="ajouter_stock" class="btn btn-success w-100">Confirmer</button>
        <button type="button" class="btn btn-secondary w-100 mt-2" id="closeForm">Annuler</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const overlay = document.getElementById('overlay');
        const stockForm = document.getElementById('stockForm');
        const formTitle = document.getElementById('formTitle');
        const idProduit = document.getElementById('idProduit');
        const quantiteAjout = document.getElementById('quantiteAjout');
        const ajouterStockBtns = document.querySelectorAll('.ajouter-stock-btn');
        const closeForm = document.getElementById('closeForm');

        // Ouvrir le formulaire
        ajouterStockBtns.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nom = button.dataset.nom;

                formTitle.textContent = `Ajouter au Stock pour ${nom}`;
                idProduit.value = id;
                quantiteAjout.value = '';

                overlay.classList.add('active');
                stockForm.classList.add('active');
            });
        });

        // Fermer le formulaire
        overlay.addEventListener('click', closePopup);
        closeForm.addEventListener('click', closePopup);

        function closePopup() {
            overlay.classList.remove('active');
            stockForm.classList.remove('active');
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Graphique des Bénéfices -->
    <div class="mt-5">
        <h2 class="text-center text-success mb-4">Bénéfices Attendus par Produit</h2>
        <canvas id="benefitChart"></canvas>
    </div>
</div>

<script>
    // Données pour le graphique
    const produits = <?= json_encode($produits); ?>;
    const labels = produits.map(p => p.nom);
    const benefits = produits.map(p => {
        // Calcul des bénéfices : quantité totale (kg) * (prix de vente/kg - prix d'achat/kg)
        const quantiteKg = parseFloat(p.quantite_kg);
        const prixVente = parseFloat(p.prix_vente);
        const prixAchat = parseFloat(p.prix_achat);
        return quantiteKg * (prixVente - prixAchat);
    });

    // Configuration du graphique
    const ctx = document.getElementById('benefitChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bénéfices Attendus (FCFA)',
                data: benefits,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            // Formater les valeurs en devise locale (FCFA)
                            return context.raw.toLocaleString('fr-FR') + ' FCFA';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            // Formater l'axe Y en FCFA
                            return value.toLocaleString('fr-FR') + ' FCFA';
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
