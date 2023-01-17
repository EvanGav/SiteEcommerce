<?php

namespace App\Controller;

use App\Model\DataObjects\Vendeur;
use App\Model\Repository\VendeurRepository;

class ControllerVendeur
{
    public static function gererProduits() : void {
        self::afficheVue('view.php',["pagetitle" => "Gestion des produits", "cheminVueBody" => "Utilisateur/Vendeur/mesProduits.php"]);
    }

    public static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}