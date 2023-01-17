<?php

namespace App\Model\Repository;

use App\Model\DataObjects\Utilisateur;

class UtilisateurRepository
{
    public static function existeUtilisateurAValider(string $email, $nonce): ?Utilisateur
    {
        $pdoStatement = DatabaseConnection::getPdo()->prepare("SELECT * FROM p_clients WHERE emailAValider=:email AND nonce=:nonce");
        $pdoStatement->execute(array("email" => $email, "nonce" => $nonce));
        $utilisateur = $pdoStatement->fetch();
        if($utilisateur!=null){
            return ClientRepository::construireDepuisArray($utilisateur);
        }
        $pdoStatement = DatabaseConnection::getPdo()->prepare("SELECT * FROM p_vendeurs WHERE emailAValider=:email AND nonce=:nonce");
        $pdoStatement->execute(array("email" => $email, "nonce" => $nonce));
        $utilisateur = $pdoStatement->fetch();
        if($utilisateur!=null){
            return VendeurRepository::construireDepuisArray($utilisateur);
        }
        $pdoStatement = DatabaseConnection::getPdo()->prepare("SELECT * FROM p_Administrateurs WHERE emailAValider=:email AND nonce=:nonce");
        $pdoStatement->execute(array("email" => $email, "nonce" => $nonce));
        $utilisateur = $pdoStatement->fetch();
        if($utilisateur!=null){
            return AdminRepository::construireDepuisArray($utilisateur);
        }
        return null;
    }

    public static function getUtilisateurParId(string $id){
        if(str_starts_with($id,"C")){
            return ClientRepository::getClientParId($id);
        }
        if(str_starts_with($id,"V")){
            return VendeurRepository::getVendeurParId($id);
        }
        else{
            return AdminRepository::getAdminParId($id);
        }
    }

    public static function getUtilisateurParEmail(string $email){
        $utilisateur=ClientRepository::getClientParEmail($email);
        if($utilisateur!=null){
            return $utilisateur;
        }
        $utilisateur=VendeurRepository::getVendeurParEmail($email);
        if($utilisateur!=null){
            return $utilisateur;
        }
        $utilisateur=AdminRepository::getAdminParEmail($email);
        if($utilisateur!=null){
            return $utilisateur;
        }
    }

    public static function supprimerParEmail(string $email){
        $pdoStatement = DatabaseConnection::getPdo()->prepare("DELETE FROM p_clients WHERE mail=:email");
        $pdoStatement->execute(array("email" => $email));
        if($pdoStatement->rowCount()==0){
            $pdoStatement = DatabaseConnection::getPdo()->prepare("DELETE FROM p_vendeurs WHERE addresse=:email");
            $pdoStatement->execute(array("email" => $email));
            if($pdoStatement->rowCount()==0){
                $pdoStatement = DatabaseConnection::getPdo()->prepare("DELETE FROM p_Administrateurs WHERE email=:emailTag");
                $pdoStatement->execute(array("emailTag" => $email));
            }
        }
    }

    public static function getUtilisateursInArray(){
        $array=array();
        $array[] = ClientRepository::getClientsInArray();
        $array[] = VendeurRepository::getVendeursInArray();
        $array[] = AdminRepository::getAdminsInArray();
        return $array;
    }

    public static function mettreAJourUtilisateur(Utilisateur $utilisateur, $email, $addresse, $nom, $prenom){
        if(str_starts_with($utilisateur->getId(), "C")){
            ClientRepository::mettreAJour($utilisateur,$email,$addresse,$nom,$prenom);
        }
        if(str_starts_with($utilisateur->getId(), "V")){
            VendeurRepository::mettreAJour($utilisateur,$email,$nom);
        }
        if(str_starts_with($utilisateur->getId(), "A")){
            AdminRepository::mettreAJour($utilisateur,$email,$nom,$prenom);
        }
    }

    public static function majMdp($utilisateur, $mdp){
        if(str_starts_with($utilisateur->getId(), "C")){
            ClientRepository::majMdp($utilisateur,$mdp);
        }
        if(str_starts_with($utilisateur->getId(), "V")){
            VendeurRepository::majMdp($utilisateur,$mdp);
        }
        if(str_starts_with($utilisateur->getId(), "A")){
            AdminRepository::majMdp($utilisateur,$mdp);
        }
    }
}