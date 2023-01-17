<?php

use App\Model\Repository\UtilisateurRepository;

$utilisateur=UtilisateurRepository::getUtilisateurParEmail($email);
if(str_starts_with($utilisateur->getId(),"V")){
    echo "<h1>Profil de ".$utilisateur->getNom()."</h1>";
}
else{
    echo "<h1>Profil de ".$utilisateur->getPrenom()." ".$utilisateur->getNom()."</h1>";
}

echo "<p>Nom : ".$utilisateur->getNom()."</p><br>";
if(!str_starts_with($utilisateur->getId(),"V")){
    echo "<p>Prenom : ".$utilisateur->getPrenom()."</p><br>";
}
if(str_starts_with($utilisateur->getId(),"C")){
    echo "<p>Adresse : ".$utilisateur->getAddresse()."</p><br>";
}
echo "<p>Email : ".$utilisateur->getEmail()."</p><br>";


if(!str_starts_with($utilisateur->getId(),"A")){
    $email=$utilisateur->getEmail();
    echo "<button type=button onclick=window.location.href='frontController.php?controller=Admin&action=ban&email=$email' class='ban'>Bannir l'utilisateur</button>";
}

