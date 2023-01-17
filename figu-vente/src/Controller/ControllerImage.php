<?php

namespace App\Controller;

use App\Model\DataObjects\Image;
use App\Model\Repository\ImageRepository;

class ControllerImage
{
    public static function readAll() : void {
        $produits=ImageRepository::getImagesInArray();
        $parametres=array("pagetitle" => "Liste des Produits", "cheminVueBody" => "Produit/list.php");
        ControllerImage::afficheVue('view.php',$parametres);
    }

    public static function read():void{//marche
        /*$id=$_GET['image_id'];
        $image=ImageRepository::getImageParId($id);
        $_POST['imageData'] = $image->getData();
        $_POST['imageType'] = $image->getType();*/
        ControllerImage::afficheVue('Image/detail.php',[]);
    }
    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__."/../View/$cheminVue"; // Charge la vue
    }

    private static function created(){
        $parametres=array("pagetitle" => "Liste des produits", "cheminVueBody" => "Produit/created.php");
        ControllerImage::afficheVue('view.php',$parametres);
    }

    public static function error(string $errorMessage) : bool{
        echo "probleme avec le Produit " . $errorMessage;
        return true;
    }
}