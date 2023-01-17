<h1>Liste des Utilisteurs</h1>
<?php
use App\Model\Repository\AdminRepository;
use App\Model\Repository\ClientRepository;
use App\Model\Repository\VendeurRepository;

$users=array();
$users["Admins"]=AdminRepository::getAdminsInArray();
$users["Clients"]=ClientRepository::getClientsInArray();
$users["Vendeurs"]=VendeurRepository::getVendeursInArray();

echo "<h2>Admins</h2>";
echo "<p>";
foreach($users["Admins"] as $user){
    echo "<a href=frontController.php?controller=Utilisateur&action=read&email=".$user->getEmail()."> ". $user->getNom()." ".$user->getPrenom()."</a><br>";
}
echo "</p>";
echo "<p>";
echo "<h2>Clients</h2>";
foreach($users["Clients"] as $user){
    echo "<a href=frontController.php?controller=Utilisateur&action=read&email=".$user->getEmail()."> ". $user->getNom()." ".$user->getPrenom()."</a><br>";
}
echo "</p>";
echo "<p>";
echo "<h2>Vendeurs</h2>";
foreach($users["Vendeurs"] as $user){
    echo "<a href=frontController.php?controller=Utilisateur&action=read&email=".$user->getEmail()."> ". $user->getNom()."</a><br>";
}
echo "</p>";
?>