<?php

namespace App\Lib;

use App\Config\Conf;
use App\Model\DataObjects\Utilisateur;
use App\Model\Repository\DatabaseConnection;
use App\Model\Repository\UtilisateurRepository;

class VerificationEmail
{
    public static function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getEmailAValider());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $absoluteURL = Conf::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=validerEmail&controller=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";

        if(str_starts_with($utilisateur->getId(),"A")){
            mail("evanYosseph@yopmail.com", "Validation de votre compte", $corpsEmail);
        }
        else{
            mail($utilisateur->getEmailAValider(), "Validation de votre email", $corpsEmail);
        }
    }

    public static function traiterEmailValidation($login, $nonce): bool
    {
        $utilisateur = UtilisateurRepository::existeUtilisateurAValider($login,$nonce);
        if ($utilisateur !== null) {
            if(str_contains($utilisateur->getId(),"C")){
                $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_clients SET mail=:emailAValider, emailAValider=:emailV, nonce=:nonceV WHERE idClient=:id");
                $pdoStatement->execute(array("emailAValider" => $utilisateur->getEmailAValider(), "emailV" => '',"nonceV"=>'',"id"=>$utilisateur->getId()));
                $utilisateur->setEmail($utilisateur->getEmailAValider());
                $utilisateur->setEmailAValider("");
                $utilisateur->setNonce("");
            }
            if(str_contains($utilisateur->getId(),"V")){
                $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_vendeurs SET addresse=:emailAValider, emailAValider=:emailV, nonce=:nonceV WHERE idVendeur=:id");
                $pdoStatement->execute(array("emailAValider" => $utilisateur->getEmailAValider(), "emailV" => '',"nonceV"=>'',"id"=>$utilisateur->getId()));
                $utilisateur->setEmail($utilisateur->getEmailAValider());
                $utilisateur->setEmailAValider("");
                $utilisateur->setNonce("");
            }
            if(str_contains($utilisateur->getId(),"A")){
                $pdoStatement = DatabaseConnection::getPdo()->prepare("UPDATE p_Administrateurs SET email=:emailAValider, emailAValider=:emailV, nonce=:nonceV WHERE idAdmin=:id");
                $pdoStatement->execute(array("emailAValider" => $utilisateur->getEmailAValider(), "emailV" => "","nonceV"=>"","id"=>$utilisateur->getId()));
                $utilisateur->setEmail($utilisateur->getEmailAValider());
                $utilisateur->setEmailAValider("");
                $utilisateur->setNonce("");
            }
            return true;
        }
        return false;
    }

    public static function aValideEmail(Utilisateur $utilisateur) : bool
    {
        return $utilisateur->getEmail()!="";
    }




    public static function genererChaineAleatoire(int $nbCaracteres) : string
    {
        $octetsAleatoires = random_bytes(ceil($nbCaracteres * 6 / 8));
        return substr(base64_encode($octetsAleatoires), 0, $nbCaracteres);
    }


    public static function envoiEmailMotDePasse(Utilisateur $utilisateur): void
    {
        $absoluteURL = Conf::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=updatePassword&controller=utilisateur&email=".$utilisateur->getEmail();
        $corpsEmail = "$lienValidationEmail";
        mail($utilisateur->getEmail(), "Changement de mot de passe", $corpsEmail);
    }

}