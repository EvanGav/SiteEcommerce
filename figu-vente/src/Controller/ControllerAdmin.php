<?php

namespace App\Controller;

use App\Model\DataObjects\Administrateur;
use App\Model\Repository\AdminRepository;
use App\Model\Repository\UtilisateurRepository;

class ControllerAdmin
{

    public static function ban(): void
    {
        UtilisateurRepository::supprimerParEmail($_GET['email']);
        self::afficheVue("view.php", ["pagetitle" => "Liste des Utilisateurs", "cheminVueBody" => "Utilisateur/list.php"]);
    }

    public static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}