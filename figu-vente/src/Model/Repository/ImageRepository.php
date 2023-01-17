<?php

namespace App\Model\Repository;

use App\Model\DataObjects\Image;
use App\Model\DataObjects\Produit;
use App\Model\DataObjects\Vendeur;

class ImageRepository
{

    public static function construireDepuisArray(array $imageFormatTableau)
    {
        if(!$imageFormatTableau){
            return null;
        }
        $image= new Image($imageFormatTableau['nom'],$imageFormatTableau['taille'],$imageFormatTableau['imageType'],$imageFormatTableau['imageData'],ProduitRepository::getProduitParId($imageFormatTableau['idProduit']));
        $image->setPath($imageFormatTableau['path']);
        $image->setId($imageFormatTableau['idImage']);
        return $image;
    }

    public static function getImageParId(string $id): ?Image
    {
        $pdoStatement = DatabaseConnection::getPdo()->prepare("SELECT * FROM p_images WHERE idImage=:id");
        $pdoStatement->execute(array(":id" => $id));
        $image=$pdoStatement->fetch();
        if($image){
            return static::construireDepuisArray($image);
        }
        return null;
    }

    public static function getImagesInArray(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_images");
        $images=array();
        $pdoStatement->execute();
        foreach($pdoStatement as $image){
            $images[] = self::construireDepuisArray($image);
        }
        return $images;
    }

    public static function getImagesParProduit(Produit $produit): array
    {
        $pdoStatement = DatabaseConnection::getPdo()->prepare("SELECT * FROM p_images WHERE idProduit=:id");
        $pdoStatement->execute(array(":id" => $produit->getId()));
        $images=array();
        foreach ($pdoStatement as $image){
            $images[] = self::construireDepuisArray($image);
        }
        return $images;
    }

    public static function ajouterImage(Vendeur $vendeur, Image $image){
        if($image->getProduit()->getVendeur()->getId()==$vendeur->getId()){
            self::sauvegarder($image);
        }
    }

    public static function sauvegarder(Image $image): void{
        try{
            $pdoStatement=DatabaseConnection::getPdo()->prepare("INSERT INTO p_images(idProduit,imageType,imageData,idImage,nom,taille,path) VALUES(:produitId,:type,:data,:id,:name,:size,:chemin)");
            $values=array(
                'id'=>htmlspecialchars($image->getId()),
                'type'=>htmlspecialchars($image->getType()),
                'data'=>htmlspecialchars($image->getData()),
                'produitId'=>htmlspecialchars($image->getProduit()->getID()),
                'name'=>htmlspecialchars($image->getNom()),
                'size'=>htmlspecialchars($image->getTaille()),
                'chemin'=>htmlspecialchars($image->getPath())
            );
            $pdoStatement->execute($values);
        }
        catch(\PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function supprimerImage(Vendeur $vendeur, Image $image){
        if($image->getProduit()->getVendeur()->getId()==$vendeur->getId()){
            $pdoStatement=DatabaseConnection::getPdo()->prepare("DELETE FROM p_images WHERE idImage=:id");
            $values=array(
                ":id"=>$image->getId()
            );
            $pdoStatement->execute($values);
        }
    }
}