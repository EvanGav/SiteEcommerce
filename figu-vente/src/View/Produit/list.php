<?php

use App\Model\Repository\ImageRepository;
use App\Model\Repository\ProduitRepository;
if(isset($filtre)){
    if($filtre==""){
        $produits = ProduitRepository::getProduitsInArray();
    }
    else{
        $produits = ProduitRepository::getProduitsInArrayFiltre($filtre);
    }
}
else{
    $produits = ProduitRepository::getProduitsInArray();
}
foreach ($produits as $product) {
    // Génération du code HTML pour chaque produit
    $id= $product->getId();
    $utilisateur=$_SESSION['utilisateur']->getId();
    echo '<div class="listeProduit">';
    $images = ImageRepository::getImagesParProduit($product);
    foreach ($images as $image) {
        $path = $image->getPath();
        $nom = $image->getNom();
        echo "<a href='frontController.php?controller=produit&action=read&id=$id&utilisateur=$utilisateur'><img src='$path' alt='$nom'></a>";
    }
    $nom= $product->getNom();
    echo "<div class='infosListeProduit'><a href='frontController.php?controller=produit&action=read&id=$id&utilisateur=$utilisateur'>$nom</a>";
    echo "<label>".$product->getPrix() . '€</label></div>';
    echo '</div>';
}

?>