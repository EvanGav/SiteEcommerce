<?php

namespace App\Model\DataObjects;

use App\Model\Repository\ClientRepository;
use App\Model\Repository\DatabaseConnection;
use App\Model\Repository\ProduitRepository;


class Client extends Utilisateur
{
    private string $id;
    private String $prenom;
    private array $panier=array();
    private string $addresse;

    /**
     * @param String $nom
     * @param String $prenom
     * @param String $email
     */
    public function __construct(string $nom, string $prenom, string $mdp,string $email ,string $addresse,string $emailAValider,string $nonce)
    {
        parent::__construct($nom,$mdp, $email,$emailAValider, $nonce);
        $this->prenom = htmlspecialchars($prenom);
        $this->addresse=htmlspecialchars($addresse);
        $this->id=$this->constructId();
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @param String $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }



    /**
     * @return String
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    private function getIdPrec(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT MAX(idClient) FROM p_clients");
        $pdoStatement->execute();
        return $pdoStatement->fetch();
    }

    private function constructId(){
        $nb=intval(substr($this->getIdPrec()[0],1))+1;
        return "C".$nb;
    }

    /**
     * @return int
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getAddresse(): string
    {
        return $this->addresse;
    }

    public function acheter(){
        try {
            date_default_timezone_set('Europe/Paris');
            $date = date('d-m-y H:i:s');
            $achetable=array();
            foreach($this->panier as $cle=>$valeur){
                if(ProduitRepository::getProduitParId($cle)->getStockFromDb()==0 || ProduitRepository::getProduitParId($cle)->getStockFromDb()-$valeur<0){
                    return false;
                }
                $achetable[$cle]=$valeur;
            }
            foreach ($achetable as $cle=>$valeur){
                $pdoStatement=DatabaseConnection::getPdo()->prepare("INSERT INTO p_achats(idClient,idProduit,quantiteAchete,dateAchat) VALUES (:idDuClient, :idDuProduit, :qte, :date)");
                $values=array(
                    'idDuClient'=>$this->id,
                    'idDuProduit'=>$cle,
                    'qte'=>$valeur,
                    'date'=>$date
                );
                $pdoStatement->execute($values);
            }
            $this->panier=array();
        }
        catch (\PDOException $e){
            echo $e->getMessage();
        }
    }

    public function noter(Vendeur $vendeur,int $note){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("INSERT INTO p_notations(idClient,idVendeur,note) VALUES (:idDuClient, :idDuVendeur, :noteTag)");
        $values=array(
            'idDuClient'=>$this->id,
            'idDuVendeur'=>$vendeur->getId(),
            'noteTag'=>$note
        );
        $pdoStatement->execute($values);
    }

    public function getHistorique(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT nomProduit,quantiteAchete,dateAchat FROM p_achats a JOIN p_produits p ON a.idProduit=p.idProduit WHERE idClient=:id");
        $values=array(
            'id'=>$this->id,
        );
        $pdoStatement->execute($values);
        $produits = [];
        foreach($pdoStatement as $produit){
            $produits[] = $produit;
        }
        return $produits;
    }

    public function ajouterAuPanier($produit, $qte){
        if(array_key_exists($produit->getId(),$this->panier)){
            $this->panier[$produit->getId()]+=$qte;
        }
        else{
            $this->panier[$produit->getId()]=$qte;
        }
    }

    public function supprimerDuPanier($produit, $qte){
        if($this->panier[$produit]>=$qte){
            $this->panier[$produit]-=$qte;
            if($this->panier[$produit]<=0){
                unset($this->panier[$produit]);
            }
        }
        else{
            unset($this->panier[$produit]);
        }
    }

    public function getPanier(){
        return $this->panier;
    }

    public function supprimerCompte(){
        ClientRepository::supprimerClientParId($this->id);
    }

    /**
     * @param string $addresse
     */
    public function setAddresse(string $addresse): void
    {
        $this->addresse = $addresse;
    }

    /**
     * @param array $panier
     */
    public function setPanier(array $panier): void
    {
        $this->panier = $panier;
    }




}