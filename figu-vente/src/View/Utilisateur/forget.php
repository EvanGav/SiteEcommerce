<form method="POST" action="frontController.php" class="formConnect">
        <h1>Mot de passe oublié</h1>
        <input type='hidden' name='action' value='emailPassword'>
        <input type='hidden' name='controller' value='utilisateur'>
    <div class="infosConnect">
        <label for="email">Adresse email</label>
        <input type=email placeholder=monemail@gmail.com name=email id=email required/>
    </div>
        <p>
            <input type="submit" value="Envoyer"/>
        </p>
</form>