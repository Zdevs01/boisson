<?php
session_start();
require_once("config.php"); // Connexion à la base de données

if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit();
}

// Récupération des administrateurs depuis la base de données
$sql = "SELECT id, nom, prenom, email, role, adr, tele, statut FROM admin";
$stmt = $pdo->query($sql);
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Template Admin Bootstrap">
    <meta name="keywords"
        content="admin, gestion, bootstrap, entreprise, corporate, créatif, facture, html5, responsive, projets">
    <meta name="author" content="Dreamguys - Template Admin Bootstrap">
    <meta name="robots" content="noindex, nofollow">
    <title>Liste des Administrateurs</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

    <div class="main-wrapper">
        <?php require_once("header.php"); ?>
        <?php require_once("sidebar.php"); ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>👨‍💼 Liste des Administrateurs</h4>
                        <h6>📋 Gérez vos administrateurs</h6>
                    </div>
                    <div class="page-btn">
                        <a href="newuser.php" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img"> Ajouter un Administrateur</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datanew">
                                <thead>
                                <tr>
                                        <th>👤 Nom</th>
                                        <th>👥 Prénom</th>
                                        <th>📧 E-mail</th>
                                        <th>🔑 Rôle</th>
                                        <th>🏠 Adresse</th>
                                        <th>📞 Téléphone</th>
                                        <th>🚦 Statut</th>
                                        <th>⚙️ Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($admins as $admin): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($admin['nom']); ?></td>
                                        <td><?= htmlspecialchars($admin['prenom']); ?></td>
                                        <td><?= htmlspecialchars($admin['email']); ?></td>
                                        <td><?= htmlspecialchars($admin['role']); ?></td>
                                        <td><?= htmlspecialchars($admin['adr']); ?></td>
                                        <td><?= htmlspecialchars($admin['tele']); ?></td>
                                        <td>
                                            <span class="badge <?= $admin['statut'] ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $admin['statut'] ? 'Actif' : 'Bloqué' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm <?= $admin['statut'] ? 'btn-danger' : 'btn-success' ?>"
                                                    onclick="changerStatut(<?= $admin['id']; ?>, <?= $admin['statut']; ?>)">
                                                <?= $admin['statut'] ? 'Bloquer' : 'Débloquer' ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>
    <script src="assets/js/script.js"></script>


   

    <script>
    function changerStatut(id, statutActuel) {
        let confirmation = confirm("Voulez-vous vraiment " + (statutActuel ? "bloquer" : "débloquer") + " cet administrateur ?");
        if (!confirmation) return;

        $.post("changer_statut.php", { id: id, statut: statutActuel }, function(response) {
            alert(response);
            location.reload();
        });
    }
    </script>
</body>
</html>
