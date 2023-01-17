<?php

use App\Model\Repository\ImageRepository;
use App\Model\Repository\ProduitRepository;
$produit=ProduitRepository::getProduitParId($id);
$images=ImageRepository::getImagesParProduit($produit);
echo "<div class='detailProduit'>";
echo "<div class='imageEtDescProduit'>";
foreach ($images as $image){
    $path=$image->getPath();
    $nom=$image->getNom();
    echo "<img src='$path' alt='$nom'>";
}
echo "<h2 class=DescriptionPourDescProduit>Description</h2>";
echo "<p class='descProduit'>".$produit->getDescription()."</p></div>";

echo "<div class=infos>";
echo "<div><h1>".$produit->getNom()."</h1><div>";
echo "<p class='prix'>".$produit->getPrix()."€</p>";
echo "<p class='stock'>".$produit->getStock()." restants</p>";


if(!str_starts_with($utilisateur,"C")){
    echo "<div class='boutonsPanier'>";
    if($utilisateur==$produit->getVendeur()->getId()){
        echo "<div class='boutonsModifProduit'> <button type=button onclick=window.location.href='frontController.php?controller=produit&action=update&id=$id'>Modifier les informations </button>";
        echo "<button type=button onclick=window.location.href='frontController.php?controller=produit&action=delete&id=$id'>Supprimer le produit</button></div>";
    }
    echo "</div>";
}
else{
    echo "<div class='boutonsPanier'>";
    echo "<form method=POST action=frontController.php>";
    echo "<input type='hidden' name='action' value='addtocart'>";
    echo "<input type='hidden' name='controller' value='client'>";
    echo "<input type='hidden' name='id' value='$id'>";
    echo "<label for=quantite class='qte'>Quantité</label>";
    echo "<input type=number name=quantite value=1 min=1 max=". $produit->getStockFromDb()." id=quantite />";
    echo "<input type=submit value='Ajouter au panier'/>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

}
