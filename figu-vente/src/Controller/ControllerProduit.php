<?php

namespace App\Controller;
use App\Model\DataObjects\Image;
use App\Model\DataObjects\Produit;
use App\Model\Repository\ProduitRepository;

class ControllerProduit
{
    public static function readAll() : void {
        $filtre="";
        if(isset($_GET['filtre'])){
            $filtre=$_GET['filtre'];
        }
        ControllerProduit::afficheVue('Produit/list.php',["filtre"=>$filtre]);
    }



    public static function read(){
        self::afficheVue('view.php',["pagetitle" => "Detail du produit", "cheminVueBody" => "Produit/detail.php","id"=>$_GET['id'],"utilisateur"=>$_GET['utilisateur']]);
    }

    public static function create() : void {
        ControllerProduit::afficheVue('view.php',["pagetitle" => "Ajouter un produit", "cheminVueBody" => "Produit/createUpdate.php","act"=>"create","id"=>""]);
    }

    public static function created(){
        $produit=new Produit(htmlspecialchars($_POST['nom']),htmlspecialchars($_POST['description']),$_POST['prix'],$_POST['stock'],$_SESSION['utilisateur']);
        $_SESSION['utilisateur']->ajouterProduit(htmlspecialchars($_POST['nom']),htmlspecialchars($_POST['description']),$_POST['prix'],$_POST['stock']);
        $image=new Image($_FILES['file']['name'],$_FILES['file']['size'],$_FILES['file']['type'],file_get_contents($_FILES['file']['tmp_name']),$produit);
        $image->setPath(  "https://webinfo.iutmontp.univ-montp2.fr/~gavrielie/eCommerce/web-project/figu-vente/web/assets/".$image->getNom());
        $_SESSION['utilisateur']->ajouterImage($image);
        move_uploaded_file($_FILES['file']['tmp_name'], __DIR__ . "/../../web/assets/".$image->getNom());

        self::afficheVue('view.php',["pagetitle" => "Liste des produits", "cheminVueBody" => "Utilisateur/Vendeur/mesProduits.php"]);
    }

    public static function delete(){
        ProduitRepository::supprimerProduitParId($_GET['id']);
        self::afficheVue('view.php',["pagetitle" => "Liste des produits", "cheminVueBody" => "Utilisateur/Vendeur/mesProduits.php"]);
    }

    public static function update(){
        self::afficheVue('view.php',["pagetitle" => "Modifier un produit", "cheminVueBody" => "Produit/createUpdate.php","act"=>"update","id"=>$_GET['id']]);
    }

    public static function updated(){
        $produit=ProduitRepository::getProduitParId($_POST['id']);
        ProduitRepository::updateProduit($produit,$_POST['nom'],$_POST['description'],$_POST['prix'],$_POST['stock']);
        self::afficheVue('view.php',["pagetitle" => "Liste des produits", "cheminVueBody" => "Utilisateur/Vendeur/mesProduits.php"]);
    }

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__."/../View/$cheminVue"; // Charge la vue
    }

    public static function error(string $errorMessage) : bool{
        echo "probleme avec le Produit " . $errorMessage;
        return true;
    }



}