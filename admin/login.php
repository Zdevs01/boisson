<?php require_once('../config.php'); ?>
<!DOCTYPE html>
<html lang="fr" class="" style="height: auto;">
  <?php require_once('inc/header.php'); ?>
  <body class="hold-transition login-page dark-mode">
    <script>
      start_loader()
    </script>
    <style>
      body{
        background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
        background-size: cover;
        background-repeat: no-repeat;
      }
      .login-title{
        text-shadow: 2px 2px black;
        font-size: 2rem;
      }
      .login-box {
        animation: fadeIn 1s ease-in-out;
      }
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
      }
    </style>
    <h1 class="text-center py-5 login-title text-light"><b><?php echo $_settings->info('name') ?></b></h1>
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <a href="./" class="h1"><b>Connexion</b></a>
        </div>
        <div class="card-body">
          <p class="login-box-msg">Identifiez-vous pour accéder à l'entrepôt</p>
          <form id="login-frm" action="" method="post">
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="username" placeholder="Nom d'utilisateur" autofocus required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>

            <script>
        function generate2FA() {
            let code = Math.floor(100000 + Math.random() * 900000); // Génère un code à 6 chiffres
            document.getElementById("2fa_code").value = code;
        }

        setInterval(generate2FA, 30000); // Change le code toutes les 30 secondes

        window.onload = generate2FA; // Génère un premier code au chargement
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    

             <div class="input-group mb-3">
        <input type="text" class="form-control" id="2fa_code" name="2fa_code" readonly>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-shield-alt"></span>
            </div>
        </div>
    </div>
            <div class="row">
              <div class="col-8">
                <label><input type="checkbox"> Se souvenir de moi</label>
              </div>
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Connexion</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script>
      $(document).ready(function(){
        end_loader();
      })
    </script>
  </body>
</html>
