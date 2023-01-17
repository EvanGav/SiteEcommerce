<?php

namespace App\Model\Repository;

use App\Lib\Hash;
use App\Lib\VerificationEmail;
use App\Model\DataObjects\Administrateur;

class AdminRepository extends UtilisateurRepository
{
    public static function sauvegarder(Administrateur $admin){
        try {
            $pdoStatement=DatabaseConnection::getPdo()->prepare("INSERT INTO p_Administrateurs(idAdmin,nomAdmin,prenomAdmin,motDePasse,email,emailAValider,nonce) VALUES(:id,:nomClient,:prenomClient,:mdp,:mail,:emailV,:nonceTag)");
            $values=array(
                'id'=>$admin->getId(),
                'nomClient'=>htmlspecialchars($admin->getNom()),
                'prenomClient'=>htmlspecialchars($admin->getPrenom()),
                'mail'=>htmlspecialchars($admin->getEmail()),
                'mdp'=>htmlspecialchars(Hash::hacher($admin->getMotDePasse())),
                'emailV'=>htmlspecialchars($admin->getEmailAValider()),
                'nonceTag'=>htmlspecialchars($admin->getNonce())
            );
            $pdoStatement->execute($values);
        }
        catch(\PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function construireDepuisArray($adminFormatTableau){
        if(!$adminFormatTableau){
            return null;
        }
        $admin= new Administrateur($adminFormatTableau['prenomAdmin'],$adminFormatTableau['nomAdmin'],$adminFormatTableau['email'],$adminFormatTableau['motDePasse'],$adminFormatTableau['emailAValider'],$adminFormatTableau['nonce']);
        $admin->setId($adminFormatTableau['idAdmin']);
        return $admin;
    }

    public static function getAdminsInArray(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_Administrateurs");
        $admins=array();
        $pdoStatement->execute();
        foreach($pdoStatement as $admin){
            $admins[] = self::construireDepuisArray($admin);
        }
        return $admins;
    }

    public static function supprimerAdminParId(string $id){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("DELETE FROM p_Administrateurs WHERE idAdmin=:id");
        $values=array(
            "id"=>$id
        );
        $pdoStatement->execute($values);
    }

    public static function getAdminParId(string $id){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_Administrateurs WHERE idAdmin=:id");
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

    public static function mettreAJour($admin, string $email, string $nom, string $prenom){
        if($email!=$admin->getEmail()) {

            $nonce = VerificationEmail::genererChaineAleatoire(32);
            $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_Administrateurs SET emailAValider=:email, nonce=:non, email='', nom=:nomTag,prenom=:prenomTag WHERE email=:emailDeBase");
            $values = array(
                "email" => $email,
                "non" => $nonce,
                "emailDeBase" => $admin->getEmail(),
                "nomTag" => $nom,
                "prenomTag" => $prenom
            );
        }
        else{
            $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_Administrateurs SET nom=:nomTag,prenom=:prenomTag WHERE email=:emailDeBase");
            $values = array(
                "emailDeBase" => $admin->getEmail(),
                "nomTag" => $nom,
                "prenomTag" => $prenom
            );
        }
        $pdoStatement->execute($values);
        if($pdoStatement->rowCount()==1){
            $admin->setNom($nom);
            $admin->setPrenom($prenom);
            if($email!=$admin->getEmail()){
                $admin->setEmail("");
                $admin->setNonce($nonce);
                $admin->setEmailAValider($email);
                VerificationEmail::envoiEmailValidation($admin);
            }
        }
    }

    public static function majMdp($admin,  $mdp){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("UPDATE p_Administrateurs SET motDePasse=:mdp WHERE email=:email");
        $values=array(
            "mdp"=>Hash ::hacher($mdp),
            "email"=>$admin->getEmail()
        );
        $pdoStatement->execute($values);
        if($pdoStatement->rowCount()==1){
            $admin->setMotDePasse($mdp);
        }
    }

    public static function getAdminParEmail(string $email){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT * FROM p_Administrateurs WHERE email=:email");
        $values=array(
            "email"=>$email
        );
        $pdoStatement->execute($values);
        $admin=$pdoStatement->fetch();
        if($admin==null){
            return null;
        }
        return static::construireDepuisArray($admin);
    }
}