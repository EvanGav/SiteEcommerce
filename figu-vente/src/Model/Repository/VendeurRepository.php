<?php

namespace App\Model\Repository;

use App\Lib\Hash;
use App\Lib\VerificationEmail;
use App\Model\DataObjects\Vendeur;
use mysql_xdevapi\TableUpdate;

class VendeurRepository extends UtilisateurRepository
{
    public static function sauvegarder(Vendeur $vendeur){
        try {
            $pdoStatement=DatabaseConnection::getPdo()->prepare("INSERT INTO p_vendeurs(idVendeur,nomVendeur,addresse,motDePasse,emailAValider,nonce) VALUES(:id,:nom,:addr,:mdp,:emailAValider,:nonce)");
            $values=array(
                'id'=>$vendeur->getId(),
                'nom'=>htmlspecialchars($vendeur->getNom()),
                'addr'=>htmlspecialchars($vendeur->getEmail()),
                'mdp'=>htmlspecialchars(Hash::hacher($vendeur->getMotDePasse())),
                'emailAValider'=>htmlspecialchars($vendeur->getEmailAValider()),
                'nonce'=>htmlspecialchars($vendeur->getNonce())
            );
            $pdoStatement->execute($values);
        }
        catch(\PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function construireDepuisArray($vendeurFormatTableau){
        if(!$vendeurFormatTableau){
            return null;
        }
        $vendeur= new Vendeur($vendeurFormatTableau['nomVendeur'],$vendeurFormatTableau['motDePasse'],$vendeurFormatTableau['addresse'],$vendeurFormatTableau['emailAValider'],$vendeurFormatTableau['nonce']);
        $vendeur->setId($vendeurFormatTableau['idVendeur']);
        return $vendeur;
    }

    public static function getVendeursInArray(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_vendeurs");
        $vendeurs=array();
        $pdoStatement->execute();
        foreach($pdoStatement as $vendeur){
            $vendeurs[] = self::construireDepuisArray($vendeur);
        }
        return $vendeurs;
    }

    public static function supprimerVendeurParId(string $id){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("DELETE FROM p_vendeurs WHERE idVendeur=:id");
        $values=array(
            "id"=>$id
        );
        $pdoStatement->execute($values);
    }

    public static function getVendeurParId(string $id){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_vendeurs WHERE idVendeur=:id");
        $values=array(
            "id"=>$id
        );
        $pdoStatement->execute($values);
        $vendeur=$pdoStatement->fetch();
        if($vendeur==null){
            return null;
        }
        return static::construireDepuisArray($vendeur);
    }

    public static function mettreAJour($vendeur,string $email,string $nom){
        if ($vendeur->getEmail()!=$email) {
            $nonce = VerificationEmail::genererChaineAleatoire(32);
            $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_vendeurs SET addresse='', emailAValider=:email, nonce=:non, nom=:nomTag WHERE addresse=:emailDeBase");
            $values = array(
                "emailAValider" => $email,
                "emailDeBase" => $vendeur->getEmail(),
                "non" => $nonce,
                "nomTag" => $nom,
            );
        }
        else{
            $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_vendeurs SET nom=:nomTag WHERE addresse=:emailDeBase");
            $values = array(
                "emailDeBase" => $vendeur->getEmail(),
                "nomTag" => $nom
            );
        }
        $pdoStatement->execute($values);
        if($pdoStatement->rowCount()==1){
            $vendeur->setNom($nom);
            if($email!=$vendeur->getEmail()) {
                $vendeur->setEmailAValider($email);
                $vendeur->setNonce($nonce);
                $vendeur->setEmail("");
                VerificationEmail::envoiEmailValidation($vendeur);
            }
        }
    }

    public static function majMdp($vendeur, $mdp){
        $nonce=VerificationEmail::genererChaineAleatoire(32);
        $pdoStatement=DatabaseConnection::getPdo()->prepare("UPDATE p_vendeurs SET motDePasse=:mdp WHERE addresse=:email");
        $values=array(
            "mdp"=>Hash::hacher($mdp),
            "email"=>$vendeur->getEmail(),
            "non"=>$nonce
        );
        $pdoStatement->execute($values);
        if($pdoStatement->rowCount()==1){
            $vendeur->setMotDePasse($mdp);
        }
    }

    public static function getVendeurParEmail(string $email){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_vendeurs WHERE addresse=:email");
        $values=array(
            "email"=>$email
        );
        $pdoStatement->execute($values);
        $vendeur=$pdoStatement->fetch();
        if($vendeur==null){
            return null;
        }
        return static::construireDepuisArray($vendeur);
    }

}