<?php

namespace App\Controller;

use App\Lib\Cookie;
use App\Lib\Hash;
use App\Lib\Session;
use App\Lib\VerificationEmail;
use App\Model\DataObjects\Administrateur;
use App\Model\DataObjects\Vendeur;
use App\Model\Repository\AdminRepository;
use App\Model\Repository\ClientRepository;
use App\Model\Repository\ProduitRepository;
use App\Model\Repository\UtilisateurRepository;
use App\Model\DataObjects\Client;
use App\Model\Repository\VendeurRepository;

class ControllerUtilisateur
{

    public static function readAll(): void
    {
        self::afficheVue('view.php', ["pagetitle" => "Liste des Utilisateurs", "cheminVueBody" => "Utilisateur/list.php"]);
    }

    public static function read()
    {
        if(isset($_GET['email'])) {
            self::afficheVue('view.php', ["pagetitle" => "Liste des Utilisateurs", "cheminVueBody" => "Utilisateur/detail.php", "email" => $_GET['email']]);
        }

    }

    public static function profil(){
        if(isset($_SESSION['utilisateur'])){
            self::afficheVue('view.php', [ "pagetitle" => "Profil", "cheminVueBody" => "Utilisateur/profil.php"]);
        }
    }

    public static function choisirTypeUtilisateur(): void
    {
        self::afficheVue('view.php', ["cheminVueBody"=>"Utilisateur/choix.php", "pagetitle"=>"Inscription"]);
    }

    public static function create()
    {
        self::afficheVue("view.php", ["act"=>"create","type" => $_GET['type'],"emailAchanger" => "", "cheminVueBody"=>"Utilisateur/createUpdate.php", "pagetitle"=>"Inscription"]);
    }

    public static function created()
    {
        $nom = $_POST['nom'];
        if(isset($_POST['prenom'])){
            $prenom = $_POST['prenom'];
        }
        $motDePasse = $_POST['password'];
        if(isset($_POST['adresse'])){
            $addresse = $_POST['adresse'];
        }
        $emailAValider = $_POST['email'];
        $type = $_POST['type'];
        $nonce = VerificationEmail::genererChaineAleatoire(32);

        if($type=="Client"){
            $client = new Client(htmlspecialchars($nom), htmlspecialchars($prenom), htmlspecialchars($motDePasse),"", htmlspecialchars($addresse), htmlspecialchars($emailAValider), htmlspecialchars($nonce));
            ClientRepository::sauvegarder($client);
            VerificationEmail::envoiEmailValidation($client);

        }
        else if($type=="Vendeur"){
            $vendeur = new Vendeur(htmlspecialchars($nom), htmlspecialchars($motDePasse), "",htmlspecialchars($emailAValider) , htmlspecialchars($nonce));
            VendeurRepository::sauvegarder($vendeur);
            VerificationEmail::envoiEmailValidation($vendeur);

        }
        else{
            $admin = new Administrateur(htmlspecialchars($prenom), htmlspecialchars($nom), "",htmlspecialchars($motDePasse),htmlspecialchars($emailAValider) , htmlspecialchars($nonce));
            AdminRepository::sauvegarder($admin);
            VerificationEmail::envoiEmailValidation($admin);

        }
        self::afficheVue("view.php", ["pagetitle" => "Inscription", "cheminVueBody" => "Utilisateur/created.php", "type" => $type]);
    }

    public static function validerEmail(){
        if(isset($_GET['nonce']) && isset($_GET['login'])){
            $nonce = $_GET['nonce'];
            $email = $_GET['login'];
            if(VerificationEmail::traiterEmailValidation($email, $nonce)){
                self::connect();
            }
        }
    }

    public static function error(string $errorMessage = ""){

        self::afficheVue("/error.php",["messageErreur" =>$errorMessage]);

    }

    public static function delete(){
        if(isset($_SESSION['utilisateur'])){
            UtilisateurRepository::supprimerParEmail($_SESSION['utilisateur']->getEmail());
            self::disconnect();
        }
    }


    public static function update(){
        self::afficheVue("view.php",["act"=>"update","type"=>"donnee","pagetitle" => "Changement données", "cheminVueBody" => "Utilisateur/createUpdate.php", "emailAchanger" => $_SESSION['utilisateur']->getEmail()]);
    }

    public static function emailPassword(){
        if(isset($_POST['email'])){
           VerificationEmail::envoiEmailMotDePasse(UtilisateurRepository::getUtilisateurParEmail($_POST['email']));
        }
        else{
            VerificationEmail::envoiEmailMotDePasse($_SESSION['utilisateur']);
        }
        self::afficheVue("view.php",[ "pagetitle" => "Changement de mot de passe", "cheminVueBody" => "Utilisateur/mailed.php"]);
    }

    public static function updatePassword(){
        if(isset($_GET['email'])){
            self::afficheVue("view.php",["act"=>"update","type"=>"password", "pagetitle" => "Changement de mot de passe", "cheminVueBody" => "Utilisateur/createUpdate.php", "emailAchanger" => $_GET['email']]);
        }
        else{
            self::afficheVue("view.php",["act"=>"update","type"=>"password", "pagetitle" => "Changement de mot de passe", "cheminVueBody" => "Utilisateur/createUpdate.php", "emailAchanger" => $_SESSION['utilisateur']->getEmail()]);
        }
    }

    public static function updated(){
        if(isset($_POST['password'])){
            $password = $_POST['password'];
            UtilisateurRepository::majMdp(UtilisateurRepository::getUtilisateurParEmail($_POST['emailAchanger']), $password);
            self::connect();
        }
        else{
            $email = $_POST['email'];
            $adresse="";
            if(isset($_POST['adresse'])){
                $adresse=$_POST['adresse'];

            }
            $nom = $_POST['nom'];
            $prenom="";
            if(isset($_POST['prenom'])){
                $prenom=$_POST['prenom'];
            }
            UtilisateurRepository::mettreAJourUtilisateur($_SESSION['utilisateur'],htmlspecialchars($email), htmlspecialchars($adresse),htmlspecialchars($nom), htmlspecialchars($prenom));
            if($email=!$_SESSION['utilisateur']->getEmail()){
                self::afficheVue("view.php",[ "pagetitle" => "Profil", "cheminVueBody" => "Utilisateur/created.php"]);
            }
            else{
                self::afficheVue("view.php",[ "pagetitle" => "Profil", "cheminVueBody" => "Utilisateur/profil.php"]);
            }
        }

    }

    public static function connect(){
        if(isset($_POST['email'])){
            $email=htmlspecialchars($_POST['email']);
            $password=htmlspecialchars($_POST['password']);
        }
        else{
            $email="";
            $password="";
        }
        self::afficheVue("view.php",["email"=>$email,"password"=>$password, "cheminVueBody"=>"Utilisateur/connexion.php", "pagetitle"=>"Connexion"]);
    }

    public static function connected(){
        $utilisateur=UtilisateurRepository::getUtilisateurParEmail(htmlspecialchars($_POST['email']));
        $userConnect=false;
        if($utilisateur != null) {
            if (password_verify(Hash::poivrer(htmlspecialchars($_POST['password'])), $utilisateur->getMotDePasse())) {
                $session=Session::getInstance();
                $session->enregistrer('utilisateur', $utilisateur);
                if(str_starts_with($utilisateur->getId(),"C")){
                    if(isset($_COOKIE['panier'])){
                        $panier=unserialize($_COOKIE['panier']);
                        $utilisateur->setPanier($panier);
                    }
                }
                self::afficheVue("view.php", ["pagetitle" => "Bienvenue sur Figu-Vente", "cheminVueBody" => "accueil.php"]);
            }
            else{
                self::connect();
            }
        }
        else{
            self::connect();
        }
    }

    public static function disconnect(){
        $session=Session::getInstance();
        $session->detruire();
        $session=Session::getInstance();
        $session->enregistrer('utilisateur', UtilisateurRepository::getUtilisateurParId("C0"));
        self::afficheVue("view.php", ["pagetitle" => "Bienvenue sur Figu-Vente", "cheminVueBody" => "accueil.php"]);
    }

    public static function forget(){
        self::afficheVue("view.php", ["pagetitle" => "Mot de passe oublié", "cheminVueBody" => "Utilisateur/forget.php"]);
    }


    public static function afficheVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../View/$cheminVue"; // Charge la vue
    }
}