<?php

namespace App\Model\Repository;

use App\Model\DataObjects\Produit;

class ProduitRepository
{
    public static function sauvegarder($produit){
        try{
            if(self::produitExisteDeja($produit)){
                throw new \PDOException("Produit déjà existant");
            }
            $sql = "INSERT INTO p_produits (idProduit,nomProduit,prix,stock,description,idVendeur) VALUES (:id,:nom, :prix,:stock,:dec ,:idVendeur)";
            $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

            $values = array(
                'id' => $produit->getId(),
                'nom' => htmlspecialchars($produit->getNom()),
                'dec' => htmlspecialchars($produit->getDescription()),
                'prix' => $produit->getPrix(),
                'stock' => $produit->getStock(),
                'idVendeur' => htmlspecialchars($produit->getVendeur()->getId()),
            );

            $pdoStatement->execute($values);
        }
        catch (\PDOException $e){
            echo $e->getMessage();
        }

    }

    public static function construireDepuisArray($produitFormatTableau){
        if(!$produitFormatTableau){
            return null;
        }
        $produit= new Produit($produitFormatTableau['nomProduit'],$produitFormatTableau['description'],$produitFormatTableau['prix'],$produitFormatTableau['stock'],VendeurRepository::getVendeurParId($produitFormatTableau['idVendeur']));
        $produit->setId($produitFormatTableau['idProduit']);
        return $produit;
    }

    public static function getProduitsInArray(){
        $model = DatabaseConnection::getPdo();
        $pdoStatement = $model->query("SELECT * FROM p_produits");
        $produits = array();
        $pdoStatement->execute();
        foreach($pdoStatement as $produit){
            $produits[] = static::construireDepuisArray($produit);
        }
        return $produits;
    }

    public static function supprimerProduitParId(string $id){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("DELETE FROM p_produits WHERE idProduit=:id");
        $values=array(
            "id"=>$id
        );
        $pdoStatement->execute($values);
    }

    public static function getProduitParId($id){
        $sql = "SELECT * FROM p_produits WHERE idProduit=:id";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "id" => htmlspecialchars($id),
        );
        $pdoStatement->execute($values);
        $produit = $pdoStatement->fetch();
        if($produit==null){
            return null;
        }
        return static::construireDepuisArray($produit);
    }

    public static function getProduitsParVendeur($idVendeur){
        $sql = "SELECT * FROM p_produits WHERE idVendeur=:id";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($sql);

        $values = array(
            "id" => htmlspecialchars($idVendeur),
        );
        $pdoStatement->execute($values);
        $produits = [];
        foreach($pdoStatement as $produit){
            $produits[] = self::construireDepuisArray($produit);
        }
        return $produits;
    }

    private static function produitExisteDeja($produit){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_produits WHERE nomProduit=:nom AND description=:desc AND idVendeur=:idVendeur");
        $values=array(
            "nom"=>$produit->getNom(),
            "desc"=>$produit->getDescription(),
            "idVendeur"=>$produit->getVendeur()->getId()
        );
        $pdoStatement->execute($values);
        $produit=$pdoStatement->fetch();
        if($produit==null){
            return false;
        }
        return true;
    }

    public static function updateProduit(Produit $produit, string $nom, string $description, int $prix, int $stock){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("UPDATE p_produits SET nomProduit=:nom, description=:desc, prix=:prix, stock=:stock WHERE idProduit=:id");
        $values=array(
            "nom"=>$nom,
            "desc"=>$description,
            "prix"=>$prix,
            "stock"=>$stock,
            "id"=>$produit->getId()
        );
        $pdoStatement->execute($values);
        $produit->setNom($nom);
        $produit->setDescription($description);
        $produit->setPrix($prix);
        $produit->setStock($stock);
    }

    public static function getProduitsInArrayFiltre(string $filtre){
        $model = DatabaseConnection::getPdo();
        $pdoStatement = $model->prepare("SELECT * FROM p_produits WHERE nomProduit LIKE :filtre");
        $values=array(
            "filtre"=>"%".$filtre."%"
        );
        $produits = array();
        $pdoStatement->execute($values);
        foreach($pdoStatement as $produit){
            $produits[] = static::construireDepuisArray($produit);
        }
        return $produits;
    }
}