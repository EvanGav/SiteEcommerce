<?php

namespace App\Model\DataObjects;

use App\Model\Repository\DatabaseConnection;

class Produit
{
    private String $id;
    private String $nom;
    private String $description;
    private int $prix;
    private int $stock;
    private Vendeur $vendeur;

    public function __construct(string $nom, string $description, int $prix, int $stock, Vendeur $vendeur) {
        $this->nom = htmlspecialchars($nom);
        $this->description = htmlspecialchars($description);
        $this->prix = $prix;
        $this->stock = $stock;
        $this->vendeur=$vendeur;
        $this->id=$this->constructId();
    }

    private function getIdPrec(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT MAX(idProduit) FROM p_produits");
        $pdoStatement->execute();
        return $pdoStatement->fetch();
    }

    private function constructId(){
        $nb=intval(substr($this->getIdPrec()[0],1))+1;
        return "P".$nb;
    }

    public function getNom() : string{
        return $this->nom;
    }

    public function setNom(string $nom){
        $this->nom = htmlspecialchars($nom);
    }

    public function getDescription() : string{
        return $this->description;
    }

    public function setDescription(string $description){
        $this->description = htmlspecialchars($description);
    }

    public function getPrix() : int{
        return $this->prix;
    }

    public function setPrix(int $prix){
        $this->prix = $prix;
    }



    public function getID() : string{
        return $this->id;
    }

    public function getStock() : int{
        return $this->stock;
    }


    /**
     * @return int
     */
    public function getStockFromDb()
    {
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT stock FROM p_produits WHERE idProduit=:id");
        $values=array('id'=>$this->id);
        $pdoStatement->execute($values);
        return $pdoStatement->fetch()[0];
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return Vendeur
     */
    public function getVendeur(): Vendeur
    {
        return $this->vendeur;
    }

    /**
     * @param String $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

}