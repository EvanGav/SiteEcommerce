<?php

namespace App\Controller;

use App\Lib\Cookie;
use App\Model\DataObjects\Client;
use App\Model\Repository\ClientRepository;
use App\Model\Repository\ProduitRepository;

class ControllerClient
{

    public static function gererPanier(){
        self::afficheVue("view.php", ["pagetitle" => "Panier", "cheminVueBody" => "Utilisateur/Client/panier.php"]);
    }

    public static function addtocart(){
        $produit=ProduitRepository::getProduitParId($_POST['id']);
        $_SESSION['utilisateur']->ajouterAuPanier($produit,$_POST['quantite']);
        Cookie::supprimer('panier');
        Cookie::enregistrer('panier',$_SESSION['utilisateur']->getPanier(),time()+3600*24*365);
        self::afficheVue("view.php", ["pagetitle" => "Panier", "cheminVueBody" => "accueil.php"]);
    }

    public static function removefromcart(){
        $_SESSION['utilisateur']->supprimerDuPanier($_POST['id'],$_POST['quantite']);
        Cookie::supprimer('panier');
        Cookie::enregistrer('panier',$_SESSION['utilisateur']->getPanier(),time()+3600*24*365);
        self::afficheVue("view.php", ["pagetitle" => "Panier", "cheminVueBody" => "Utilisateur/Client/panier.php"]);
    }

    public static function acheter(){
        $_SESSION['utilisateur']->acheter();
        Cookie::enregistrer('panier',$_SESSION['utilisateur']->getPanier(),time()+3600*24*365);
        self::afficheVue("view.php", ["pagetitle" => "Panier", "cheminVueBody" => "Utilisateur/Client/achat.php"]);
    }

    public static function historique(){
        self::afficheVue("view.php", ["pagetitle" => "Historique", "cheminVueBody" => "Utilisateur/Client/historique.php"]);
    }

    public static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}