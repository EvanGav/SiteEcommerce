<?php

namespace App\Model\DataObjects;

use App\Model\Repository\DatabaseConnection;
use App\Model\Repository\ImageRepository;
use App\Model\Repository\ProduitRepository;

class Vendeur extends Utilisateur
{
    private string $id;


    public function __construct(string $nom, string $mdp,string $email,string $emailAValider,string $nonce)
    {
        parent::__construct($nom,$mdp,$email,$emailAValider,$nonce);
        $this->id=$this->constructId();
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }



    private function getIdPrec(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT MAX(idVendeur) FROM p_vendeurs");
        $pdoStatement->execute();
        return $pdoStatement->fetch();
    }

    private function constructId(){
        $nb=intval(substr($this->getIdPrec()[0],1))+1;
        return "V".$nb;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNote(): int
    {
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT note FROM p_vendeurs WHERE idVendeur=:id");
        $values=array(
            'id'=>$this->id
        );
        $pdoStatement->execute($values);
        return $pdoStatement->fetch()[0];
    }

    public function ajouterProduit($nom,$description,$prix,$stock){
        ProduitRepository::sauvegarder(new Produit($nom,$description,$prix,$stock,$this));
    }

    public function supprimerProduit($produit){
        if($produit->getVendeur()->getId()==$this->id){
            ProduitRepository::supprimerProduitParId($produit->getId());
        }

    }

    public function ajouterImage(Image $image){
        ImageRepository::ajouterImage($this,$image);
    }

    public function supprimerImage(Image $image){
        ImageRepository::supprimerImage($this,$image);

    }
}