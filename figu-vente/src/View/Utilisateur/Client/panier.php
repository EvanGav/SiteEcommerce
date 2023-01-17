<?php

use App\Lib\Cookie;
use App\Model\Repository\ProduitRepository;

$panier=Cookie::lire('panier');
if(isset($panier)){
    foreach ($panier as $idProduit=>$qte){
        if($qte>0){
            $produit=ProduitRepository::getProduitParId($idProduit);
            $nom=$produit->getNom();
            $prix=$produit->getPrix();
            echo "<div class='panierInfo'>";
            echo "<div class='produitPanier'>";
            echo "<p>$nom</p>";
            echo "<label>x$qte</label></div>";
            echo "<p>$prix €</p>";
            echo "<form method=POST action=frontController.php>";
            echo "<input type='hidden' name='action' value='removefromcart'>";
            echo "<input type='hidden' name='controller' value='client'>";
            echo "<input type='hidden' name='id' value='$idProduit'>";
            echo "<p>";
            echo "<label for=quantite>Quantité</label> :";
            echo "<input type=number name=quantite value=$qte min=1 max=". $produit->getStockFromDb()." id=quantite />";
            echo "</p>";
            echo "<p>";
            echo "<input type=submit value='Supprimer la quantité du panier'/>";
            echo "</p>";
            echo "</div>";
        }

    }
}


if($_SESSION['utilisateur']->getID() != 'C0'){
    echo "<button type='button' class=boutonCommande onclick=window.location.href='frontController.php?controller=client&action=acheter'>Commander</button>";

}
else{
    echo "<button type='button' class='boutonCommande' onclick=window.location.href='frontController.php?controller=utilisateur&action=connect'>Se connecter pour commander</button>";
}
?>