<form method="POST" action="frontController.php" class="formConnect">
        <input type='hidden' name='action' value='connected'>
        <input type='hidden' name='controller' value='utilisateur'>
        <h1>Connexion</h1>
        <div class="infosConnect">
            <label for="email">Adresse email</label>
            <?php
            if($email!="") {
                echo "<input type=email value=$email name=email id=email required/>";
             }
             else{
                echo "<input type=email placeholder=monemail@gmail.com name=email id=email required/>";
             }
            ?>
            <label for="password">Mot de passe</label>
            <?php
            if($password!="") {
                echo "<input type=password value=$password name=password id=password required/>";
             }
             else{
                echo "<input type=password placeholder=2ecOAcoGgcuJpg66 name=password id=password required/>";
             }
            ?>
        </div>
            <input type="submit" value="Envoyer"/>
        <p>
            <a href="frontController.php?controller=utilisateur&action=forget">Mot de passe oublié ?</a>
        </p>
</form>