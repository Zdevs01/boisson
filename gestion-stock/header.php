<?php

if (isset($_GET['id'])) {
    require_once("php/Class/Admin.php");
    extract($_GET);
    Admin::supprimer($id, "admin");

    // Détruire la session après la suppression
    session_unset();
    session_destroy();

    // Rediriger vers logout.php après la suppression
    header("Location: logout.php");
    exit();
}
?>
<div class="header">
    <div class="header-left active">
        <a href="index.php" class="logo">
            <img src="assets/img/logo.png" alt="Logo de l'Entrepôt de Boissons">
        </a>
        <a href="index.php" class="logo-small">
            <img src="assets/img/logo-small.png" alt="Logo Entrepôt">
        </a>
        <a id="toggle_btn" href="javascript:void(0);" class="toggle-btn"></a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">
    <li class="nav-item dropdown has-arrow main-drop">
        <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
            <span class="user-img">
                <img src="<?= $_SESSION['admin_info']['image'] ?>" alt="Photo de profil" class="profile-img">
                <span class="status online"></span>
            </span>
        </a>
        <div class="dropdown-menu menu-drop-user">
            <div class="profilename">
                <div class="profileset">
                    <span class="user-img">
                        <img src="<?= $_SESSION['admin_info']['image'] ?>" alt="Photo de profil" class="profile-img">
                        <span class="status online"></span>
                    </span>
                    <div class="profilesets">
                        <h6>
                            <?= $_SESSION['admin_info']['nom'] . " " . $_SESSION['admin_info']['prenom'] ?>
                        </h6>
                        <h5>BIENVENUE 🛠️</h5>
                    </div>
                </div>
                <hr class="m-0">
                <a class="dropdown-item" href="profile.php"> <i class="me-2" data-feather="user"></i> Mon Profil 📸</a>
                <hr class="m-0">
                <a class="dropdown-item logout pb-0" href="header.php?id=<?= $_SESSION['admin_info']['id'] ?>">
                    <img style="width:20px;" src="assets/img/icons/delete.svg" class="me-2" alt="Supprimer mon compte">
                    Supprimer mon compte 🗑️
                </a>
                <a class="dropdown-item logout pb-0" href="logout.php">
                    <img src="assets/img/icons/log-out.svg" class="me-2" alt="Se déconnecter">
                    Se déconnecter 🚪
                </a>
            </div>
        </div>
    </li>
</ul>


    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-ellipsis-v"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="profile.php">Mon Profil 📸</a>
            <a class="dropdown-item" href="logout.php">Se déconnecter 🚪</a>
        </div>
    </div>
</div>
<style>


/* Style pour l'image de profil dans le menu déroulant */
.profile-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    object-position: center;
}


    /* Style pour l'image de profil */
.profile-img {
    width: 40px; /* Taille de l'image */
    height: 40px; /* Taille de l'image */
    border-radius: 50%; /* Rendre l'image ronde */
    object-fit: cover; /* Recadrer l'image si elle dépasse */
    object-position: center; /* Centrer l'image dans l'espace */
}

/* Style du Header */
.header {
    background-color: #fff;
    padding: 10px 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
}

.header-left .logo img {
    max-width: 150px;
    transition: transform 0.3s ease-in-out;
}

.header-left .logo-small img {
    max-width: 40px;
    transition: transform 0.3s ease-in-out;
}

/* Animation au survol du logo */
.header-left .logo:hover img {
    transform: scale(1.05);
}

.nav .user-menu {
    position: relative;
}

.nav .user-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #ddd;
    transition: all 0.3s ease;
}

.nav .user-img:hover {
    border-color: #007bff;
    transform: scale(1.1);
}

.profilename {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.profileset {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.profilesets h6 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.profilesets h5 {
    font-size: 14px;
    color: #777;
}

.dropdown-menu {
    width: 220px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.dropdown-item {
    padding: 10px 15px;
    font-size: 14px;
    color: #333;
    transition: background-color 0.3s ease;
}

.dropdown-item:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

/* Animation du bouton mobile */
#mobile_btn {
    display: none;
}

@media (max-width: 768px) {
    #mobile_btn {
        display: block;
    }

    .header-left .logo img {
        max-width: 120px;
    }

    .header-left .logo-small img {
        max-width: 35px;
    }

    .nav {
        display: none;
    }

    .mobile-user-menu {
        display: block;
    }
}




</style>
<script>

document.getElementById('toggle_btn').addEventListener('click', function () {
    document.querySelector('.header-left').classList.toggle('active');
});

document.querySelector('.dropdown-item.logout').addEventListener('click', function() {
    if (confirm("Êtes-vous sûr de vouloir supprimer votre compte ?")) {
        window.location.href = this.href;
    }
});


</script>