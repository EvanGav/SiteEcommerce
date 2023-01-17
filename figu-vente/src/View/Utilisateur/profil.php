<h1>Votre profil</h1>
<div class="profil">
<?php
$utilisateur=$_SESSION['utilisateur'];
if(str_starts_with($utilisateur->getId(),"C") || str_starts_with($utilisateur->getId(),"A")){
    echo "<p>Prenom : " . $utilisateur->getPrenom() . "</p>";
}
echo "<p>Nom : " . $utilisateur->getNom() . "</p>";
if(str_starts_with($utilisateur->getId(),"C")){
    echo "<p>adresse : " . $utilisateur->getAddresse() . "</p>";
}
echo "<p>Email : " . $utilisateur->getEmail() . "</p>";
?>

<button type="button" onclick="window.location.href='frontController.php?controller=Utilisateur&action=update'">Modifier Données</button>
<button type="button" onclick="window.location.href='frontController.php?controller=Utilisateur&action=emailPassword'">Modifier Mot de passe</button>
<button type="button" onclick="window.location.href='frontController.php?controller=Utilisateur&action=delete'">Supprimer Compte</button>
</div>
