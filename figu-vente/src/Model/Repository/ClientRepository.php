<?php

namespace App\Model\Repository;

use App\Lib\Hash;
use App\Lib\VerificationEmail;
use App\Model\DataObjects\Client;

class ClientRepository extends UtilisateurRepository
{

    public static function sauvegarder(Client $client) {
        try{
            $pdoStatement=DatabaseConnection::getPdo()->prepare("INSERT INTO p_clients(idClient,nom,prenom,motDePasse,addresse,emailAValider,nonce,mail) VALUES(:id,:nomClient,:prenomClient,:mdp,:add,:emailV,:nonceTag,:email)");
            $values=array(
                'id'=>$client->getId(),
                'nomClient'=>htmlspecialchars($client->getNom()),
                'prenomClient'=>htmlspecialchars($client->getPrenom()),
                'email'=>htmlspecialchars($client->getEmail()),
                'mdp'=>htmlspecialchars(Hash::hacher($client->getMotDePasse())),
                'add'=>htmlspecialchars($client->getAddresse()),
                'emailV'=>htmlspecialchars($client->getEmailAValider()),
                'nonceTag'=>htmlspecialchars($client->getNonce())
            );
            $pdoStatement->execute($values);
        }
        catch(\PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function construireDepuisArray($clientFormatTableau){
        if(!$clientFormatTableau){
            return null;
        }
        $client= new Client($clientFormatTableau['nom'],$clientFormatTableau['prenom'],$clientFormatTableau['motDePasse'],$clientFormatTableau['mail'],$clientFormatTableau['addresse'],$clientFormatTableau['emailAValider'],$clientFormatTableau['nonce']);
        $client->setId($clientFormatTableau['idClient']);
        return $client;
    }

    public static function getClientsInArray(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_clients");
        $clients=array();
        $pdoStatement->execute();
        foreach($pdoStatement as $client){
            $clients[] = self::construireDepuisArray($client);
        }
        return $clients;
    }

    public static function supprimerClientParId(string $id){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("DELETE FROM p_clients WHERE idClient=:id");
        $values=array(
            "id"=>$id
        );
        $pdoStatement->execute($values);
    }

    public static function getClientParId(string $id){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_clients WHERE idClient=:id");
        $values=array(
            "id"=>$id
        );
        $pdoStatement->execute($values);
        $client=$pdoStatement->fetch();
        if($client==null){
            return null;
        }
        return static::construireDepuisArray($client);
    }

    public static function mettreAJour($client, string $email, string $addresse, string $nom, string $prenom){
        $nonce=VerificationEmail::genererChaineAleatoire(32);
        $pdoStatement="";
        $values=array();
        if($email!=$client->getEmail()) {
            $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_clients SET emailAValider=:email,nonce=:non,addresse=:add, mail='', nom=:nomTag, prenom=:prenomTag WHERE mail=:emailDeBase");
            $values = array(
                "email" => $email,
                "non" => $nonce,
                "add" => $addresse,
                "emailDeBase" => $client->getEmail(),
                "nomTag" => $nom,
                "prenomTag" => $prenom
            );
        }
        else{
            $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_clients SET addresse=:add, nom=:nomTag, prenom=:prenomTag WHERE mail=:emailDeBase");
            $values = array(
                "add" => $addresse,
                "nomTag" => $nom,
                "prenomTag" => $prenom,
                "emailDeBase" => $client->getEmail()
            );
        }
        $pdoStatement->execute($values);
        if($pdoStatement->rowCount()==1){
            $client->setAddresse($addresse);
            $client->setNom($nom);
            $client->setPrenom($prenom);
            if($email!=$client->getEmail()){
                $client->setEmailAValider($email);
                $client->setNonce($nonce);
                $client->setEmail("");
                VerificationEmail::envoiEmailValidation($client);
            }
        }
    }

    public static function majMdp($client,  $mdp){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("UPDATE p_clients SET motDePasse=:mdp WHERE idClient=:id");
        $values=array(
            "mdp"=>Hash ::hacher($mdp),
            "id"=>$client->getId()
        );
        $pdoStatement->execute($values);
        if($pdoStatement->rowCount()==1){
            $client->setMotDePasse($mdp);
        }
    }


    public static function getClientParEmail(string $email){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_clients WHERE mail=:email");
        $values=array(
            "email"=>$email
        );
        $pdoStatement->execute($values);
        $client=$pdoStatement->fetch();
        if($client==null){
            return null;
        }
        return static::construireDepuisArray($client);
    }

}