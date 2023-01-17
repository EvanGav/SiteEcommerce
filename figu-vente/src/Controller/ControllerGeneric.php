<?php

namespace App\Controller;

class ControllerGeneric
{
    public static function acceuil()
    {
        $filtre="";
        if(isset($_GET['filtre'])){
            $filtre=$_GET['filtre'];
        }
        self::afficheVue("view.php", ["pagetitle" => "Accueil", "cheminVueBody" => "accueil.php","filtre"=>$filtre]);
    }

    public static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}