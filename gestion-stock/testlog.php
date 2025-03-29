<?php
session_start();
require_once("php/Class/Admin.php");

if (isset($_POST['connecter'])) {
    extract($_POST);
    
    // Récupérer le résultat de l'authentification de l'admin
    $resultat = Admin::estAdmin($email, $mdp);
    
    if ($resultat) {
        // Vérifier si le mot de passe saisi correspond au mot de passe crypté
        if (password_verify($mdp, $resultat['mdp'])) {
            $_SESSION['admin'] = $resultat;
            $_SESSION['message'] = ['type' => 'success', 'content' => 'Connexion réussie ! Bienvenue dans l\'administration.'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['message'] = ['type' => 'error', 'content' => 'Échec de la connexion. Vérifiez votre email et/ou votre mot de passe.'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'error', 'content' => 'Échec de la connexion. Vérifiez votre email et/ou votre mot de passe.'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Entrepôt de Boissons</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: url('assets/img/cover-1739190838.png') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .login-wrapper {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .form-control {
            border-radius: 10px;
            padding: 10px;
        }
        .btn-login {
            background: #ff7b00;
            color: white;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #e06900;
        }
        .emoji {
            font-size: 24px;
        }
        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <h2>Bienvenue 🍾</h2>
        <p>Connectez-vous pour gérer votre stock de boissons 📦</p>

        <!-- Affichage des messages de notification -->
        <?php
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $alertClass = ($message['type'] == 'success') ? 'alert-success' : 'alert-danger';
            echo "<div class='alert $alertClass'>{$message['content']}</div>";
            unset($_SESSION['message']);
        }
        ?>

        <form method="post" action="signin.php">
            <div class="mb-3">
                <label class="form-label">📧 Email</label>
                <input type="email" class="form-control" placeholder="Saisissez votre email" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">🔒 Mot de passe</label>
                <div class="input-group">
                    <input type="password" class="form-control" placeholder="Saisissez votre mot de passe" name="mdp" required>
                    <span class="input-group-text toggle-password"><i class="fa fa-eye-slash"></i></span>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100">Se connecter 🚀</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector(".toggle-password").addEventListener("click", function () {
            let passwordInput = document.querySelector("input[name='mdp']");
            let icon = this.querySelector("i");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        });
    </script>
</body>
</html>
