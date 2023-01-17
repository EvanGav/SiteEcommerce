<form method="POST" action="frontController.php" class="formConnect">
        <?php
        if($act=="create"){
            echo "<h1>Inscription</h1>";
            echo "<input type='hidden' name='action' value='created'>";
            echo "<input type='hidden' name='controller' value='utilisateur'>";
            echo"<input type='hidden' name='type' value='$type'>";
            echo "<div class='infosConnect'>";
            if($type!="Vendeur"){
                echo "<label for=prenom>Prenom</label> ";
                echo "<input type=text placeholder=Alexandre name=prenom id=prenom required/>";
            }
                echo "<label for=nom>Nom</label> ";
                echo "<input type=text placeholder=Benalla name=nom id=nom required/>";
            if($type=="Client"){
                echo "<label for=adresse>Adresse</label> ";
                echo "<input type=text placeholder='145 rue de la paix' name=adresse id=adresse required/>";
            }
                echo "<label for=email>Adresse email</label> ";
                echo "<input type=email placeholder=monemail@gmail.com name=email id=email required/>";
                echo "<label for=password>Mot de passe</label> ";
                echo "<input type=password placeholder=2ecOAcoGgcuJpg66 name=password id=password required/>";
                echo"</div>";
            echo "<p>";
                echo "<input type=submit value=Envoyer />";
            echo "</p>";
        }
        else{
            echo"<h1>Modification des données</h1>";
            echo "<input type='hidden' name='action' value='updated'>";
            echo "<input type='hidden' name='controller' value='utilisateur'>";
            echo "<input type='hidden' name='emailAchanger' value='$emailAchanger'>";
            echo "<div class='infosConnect'>";
            if($type=="donnee") {
                echo "<div class='infosConnect'>";
                $utilisateur = \App\Model\Repository\UtilisateurRepository::getUtilisateurParEmail($emailAchanger);
                $email = $utilisateur->getEmail();
                $nom = $utilisateur->getNom();
                if (str_starts_with($utilisateur->getId(), "C") || str_starts_with($utilisateur->getId(), "A")) {
                    $prenom = $utilisateur->getPrenom();
                    echo "<label for=prenom>Prenom</label> ";
                    echo "<input type=text placeholder=Alexandre name=prenom id=prenom value='$prenom' required/>";
                }
                echo "<label for=nom>Nom</label> ";
                echo "<input type=text placeholder=Benalla name=nom id=nom value='$nom' required/>";
                if (str_starts_with($utilisateur->getId(), "C")) {
                    $adresse = $utilisateur->getAddresse();
                    echo "<label for=adresse>Adresse</label> ";
                    echo "<input type=text name=adresse id=adresse value='$adresse' required/>";
                }
                echo "<label for=email>Adresse email</label> ";
                echo "<input type=email placeholder=monemail@gmail.com name=email id=email value='$email' required/>";
                echo "</div>";
            }
            else{
                echo "<div class='infosConnect'>";
                echo "<label for=password>Nouveau mot de passe</label> ";
                echo "<input type=password placeholder=2ecOAcoGgcuJpg66 name=password id=password required/>";
                echo "</div>";
            }
            echo "<p>";
            echo "<input type=submit value=Envoyer />";
            echo "</p>";
        }
        ?>

</form>
