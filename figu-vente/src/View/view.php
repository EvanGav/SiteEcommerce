<!DocType html>
<?php
require __DIR__ ."/../../vendor/autoload.php";

use App\Lib\Cookie;
use App\Lib\Session;
use App\Model\Repository\UtilisateurRepository;
use App\Model\Repository\ClientRepository;

$session=Session::getInstance();
if(!$session->contient("utilisateur")){
    $session->enregistrer("utilisateur",ClientRepository::getClientParId("C0"));
}
if (str_starts_with($session->lire("utilisateur")->getId(),"C") && !Cookie::contient("panier")){
    Cookie::enregistrer("panier",$session->lire("utilisateur")->getPanier(),time()+3600*24*365);
}
else if(!str_starts_with($session->lire("utilisateur")->getId(),"C") && Cookie::contient("panier")){
    if(Cookie::contient("panier")){
        Cookie::supprimer("panier");
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title class="top"><?php
        echo $pagetitle; ?></title>
</head>
<body>
<header class="header">
    <ul class="container">
        <img src="https://webinfo.iutmontp.univ-montp2.fr/~gavrielie/eCommerce/web-project/figu-vente/web/assets/logo.png" alt="logo" class="logo">
        <div class="navigation">
            <ul class="nav-list">
                <li>
                    <a href="frontController.php">Accueil</a>
                </li>
                <?php
                if ($_SESSION['utilisateur']->getID() != 'C0') {
                    echo '<li><a href=frontController.php?controller=Utilisateur&action=profil>Profil</a></li>';
                    echo '<li><a href=frontController.php?controller=Utilisateur&action=disconnect>Déconnexion</a></li>';
                }
                else {
                    echo '<li><a href=frontController.php?controller=Utilisateur&action=connect>Connexion</a></li>';
                    echo '<li><a href=frontController.php?controller=Utilisateur&action=choisirTypeUtilisateur>Inscription</a></li>';
                }
                if (str_starts_with($_SESSION['utilisateur']->getID(), 'C')) {
                    echo '<a href="frontController.php?controller=Client&action=gererPanier">Panier </a>';
                    echo '<a href="frontController.php?controller=Client&action=historique">Historique </a>';
                }
                else if(str_starts_with($_SESSION['utilisateur']->getID(),'V')) {
                    echo '<a href="frontController.php?controller=Vendeur&action=gererProduits">Produits en vente</a>';
                }
                else{
                    echo '<a href="frontController.php?controller=Utilisateur&action=readAll">Liste des utilisateurs</a>';
                }
                ?>
            </div>
                <div>
                        <form method="GET" action="frontController.php" class="searchbar">
                            <input type='hidden' name='action' value='acceuil'>
                            <input type='hidden' name='controller' value='generic'>
                            <input type="text" name="filtre" placeholder="Rechercher">
                            <button type="submit">Go</button>
                        </form>

                </div>
            </ul>
        </div>
</header>

<main>
    <?php
    require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
<footer class="footer">
    <div>

    </div>
        Copyright - Gavrieli Evan , Mélotte Quentin, Azzin Hamza
</footer>
</body>
</html>