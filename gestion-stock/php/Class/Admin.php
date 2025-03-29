<?php
require_once("Personne.php");

class Admin extends Personne {
    private string $mdp;
    private string $role;

    public function __construct(
        $nom,
        $prenom,
        $adr,
        $tele,
        $email,
        $image,
        $mdp,
        $role, // Valeur par défaut si non spécifiée
    ) {
        parent::__construct($nom, $prenom, $adr, $tele, $email, $image);
        $this->mdp = $mdp;
        $this->role = $role;
    }

    // Getter
    public function __get(string $property) {
        switch ($property) {
            case 'mdp':
                return $this->mdp;
            case 'role':
                return $this->role;
            default:
                exit("<b>ERROR :</b> La propriété '{$property}' est invalide !");
        }
    }

    // Ajouter un nouvel admin
    public function AjouterAdmin() {
        return Dao::ajouterAdmin(
            $this->nom,
            $this->prenom,
            $this->adr,
            $this->tele,
            $this->email,
            $this->mdp,
            $this->image,
            $this->role // On enregistre aussi le rôle
        );
    }

    // Modifier les données d'un admin (sauf l'image)
    public static function modifierAdmin($id, $nom, $prenom, $adr, $tele, $email, $mdp, $role) {
        return Dao::modifierAdmin($id, $nom, $prenom, $adr, $tele, $email, $mdp, $role);
    }

    // Modifier l'image d'un admin
    public static function modifierImageAdmin($id, $image) {
        return Dao::modifierImageAdmin($id, $image);
    }

    // Supprimer un compte admin
    public static function supprimer($id, $nom_de_class = "Admin") {
        return Dao::supprimerPersonne($id, $nom_de_class);
    }
    

    // Vérifier si l'email existe et si le mot de passe est correct
    public static function estAdmin($email, $mdp) {
        define("FAUX_EMAIL", "❌ Cet email n'existe pas !");
        define("FAUX_MDP", "⚠️ Mot de passe incorrect !");

        $admin = Dao::adminExiste($email);
        if ($admin === false) {
            return FAUX_EMAIL;
        }

        if (strcmp($mdp, $admin['mdp']) === 0) {
            return $admin;
        } else {
            return FAUX_MDP;
        }
    }
}
