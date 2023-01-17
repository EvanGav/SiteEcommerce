<?php

namespace App\Model\DataObjects;

use App\Model\Repository\ClientRepository;
use App\Model\Repository\DatabaseConnection;
use App\Model\Repository\ProduitRepository;
use App\Model\Repository\VendeurRepository;

class Administrateur extends Utilisateur
{
    private string $id;
    private string $prenom;

    /**
     * @param string $prenom
     */
    public function __construct(string $prenom, string $nom,string $email, string $mdp,string $emailAValider,string $nonce)
    {
        parent::__construct($nom,$mdp,$email,$emailAValider,$nonce);
        $this->prenom = $prenom;
        $this->id=$this->constructId();
    }

    private function getIdPrec(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT MAX(idAdmin) FROM p_Administrateurs");
        $pdoStatement->execute();
        return $pdoStatement->fetch();
    }

    private function constructId(){
        $nb=intval(substr($this->getIdPrec()[0],1))+1;
        return "A".$nb;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }



    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function supprimerProduit(Produit $produit){
        ProduitRepository::supprimerProduitParId($produit->getId());
    }

    public function supprimerVendeur(Vendeur $vendeur){
        VendeurRepository::supprimerVendeurParId($vendeur->getId());
    }

    public function supprimerClient(Client $client){
        ClientRepository::supprimerClientParId($client->getId());
    }
}