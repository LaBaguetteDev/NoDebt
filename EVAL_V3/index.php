<?php
$titre = 'Connexion';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post" action="myGroups.php">
            <fieldset class="fieldset-box">
                <h1>
                    Connexion
                </h1>
                <article class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="username">Adresse mail</label>
                    <input type="text" id="username" placeholder="jean@exemple.com" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" placeholder="••••••••" required>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Se connecter">

                <article class="help-connect">
                    <a href="register.php">Créer un compte</a>
                    <a href="forgotPass.php">Mot de passe oublié</a>
                </article>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>