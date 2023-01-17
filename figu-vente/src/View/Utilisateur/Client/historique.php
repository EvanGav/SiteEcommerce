<?php
$achats=$_SESSION['utilisateur']->getHistorique();
$dates=array();
foreach($achats as $achat){
    echo "<div class='commande'>";
    echo "<label>".$achat['dateAchat']."</label>";
    echo "<div class='qteAchete'>";
    echo "<p>".$achat['nomProduit']."</p>";
    echo "<p class='qteHistorique'>x".$achat['quantiteAchete']."</p></div>";
    echo "</div>";
}