<h1>Mes Produits</h1>
<?php
use App\Model\Repository\ProduitRepository;

echo"<div class='mesProduits'>";
$produits=ProduitRepository::getProduitsParVendeur($_SESSION['utilisateur']->getId());
foreach($produits as $produit){
    $id=$produit->getId();
    $nom=$produit->getNom();
    $utilisateur=$_SESSION['utilisateur']->getId();

    echo "<a href='frontController.php?controller=produit&action=read&id=$id&utilisateur=$utilisateur' class='produitDuVendeur'>$nom</a>";
}
?>

<button type="button" onclick="window.location.href='frontController.php?controller=produit&action=create'">Ajouter un produit</button>
</div>