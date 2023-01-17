
<form method="post" action="frontController.php" enctype="multipart/form-data" class="creationProduit">
        <?php
        if($act=="create"){
            echo"<div class='creationProduit'>";
                echo "<h1>Creation Nouveau Produit :</h1>";
                echo"<div class='inputCreateProduit'>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<input type='hidden' name='action' value='created'>";
                        echo "<input type='hidden' name='controller' value='produit'>";
                        echo "<label for=nom>Nom</label>";
                        echo "<input type=text placeholder='Figurine Banpresto Izuku Midoriya – BRAVEGRAPH #1 vol.1' name=nom id=nom required/>";
                    echo"</div>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<label for=description>Description</label> :";
                        echo "<input type=text class='inputDescProduit' placeholder='Ajoutez la figurine (Deku) Izuku Midoriya de la collection Banpresto BRAVEGRAPH vol.1 de figurines My Hero Academia !' name=description id=description required/>";
                    echo"</div>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<label for=prix>Prix</label>";
                        echo "<input type=number placeholder=69 name=prix id=prix required/>";
                    echo"</div>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<label for=stock>Nombre en stock</label>";
                        echo "<input type=number placeholder='33' name=stock id=stock required/>";
                    echo"</div>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<label for=file>Image du produit</label>";
                        echo "<input type=file name=file>";
                    echo"</div>";
                        echo "<input type=submit value=Valider />";
                echo "</div>";
            echo "</div>";
        }
        else{
            $produit=\App\Model\Repository\ProduitRepository::getProduitParId($id);
            echo"<div class='creationProduit'>";
                echo "<h1>Modification Produit :</h1>";
                echo"<div class='inputCreateProduit'>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<input type='hidden' name='action' value='updated'>";
                        echo "<input type='hidden' name='controller' value='produit'>";
                        echo "<input type='hidden' name='id' value='$id'>";
                    echo "</div>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<label for=nom>Nom</label> :";
                        $nom=$produit->getNom();
                        echo "<input type=text value=$nom name=nom id=nom required/>";
                    echo "</div>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<label for=description>Description</label> :";
                        $description=$produit->getDescription();
                        echo "<input type=text value=$description name=description id=description required/>";
                    echo "</div>";
                    echo "<div class='infoCreateProduit'>";
                        echo "<label for=prix>Prix</label>";
                        $prix=$produit->getPrix();
                        echo "<input type=number value=$prix name=prix id=prix required/>";

                    echo "<label for=stock>Nombre en stock</label>";
                    $stock=$produit->getStockFromDb();
                    echo "<input type=number value=$stock name=stock id=stock required/>";
                    echo "<input type=submit value=Valider />";
                echo "</div>";
            echo "</div>";
        }
        ?>
</form>


