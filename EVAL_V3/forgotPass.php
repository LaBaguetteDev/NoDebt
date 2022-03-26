<?php
$titre = 'Mot de passe oublié';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post" action="index.php">
            <fieldset class="fieldset-box">
                <h1>
                    Réinitialiser mot de passe
                </h1>
                <article class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="username"></label>
                    <input type="text" id="username" placeholder="Votre adresse mail" required>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Réinitialiser">

                <article class="help-connect">
                    <a href="index.php">Se connecter</a>
                </article>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>