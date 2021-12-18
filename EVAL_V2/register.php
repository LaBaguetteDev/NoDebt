<?php
$titre = 'Inscription';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post" action="index.php">
            <fieldset class="fieldset-box">
                <h1 class="register-screen-title">
                    Créer un nouveau compte
                </h1>
                <h2>
                    Vos identifiants
                </h2>
                <article class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="email">Adresse mail</label>
                    <input type="email" name="email" id="email" placeholder="jean@exemple.com" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="passwordRep">Confirmer mot de passe</label>
                    <input type="password" name="password" id="passwordRep" placeholder="••••••••" required>
                </article>

                <h2>
                    Vos infos personnelles
                </h2>
                <article class="textbox">
                    <i class="fas fa-id-card"></i>
                    <label for="second_name">Nom</label>
                    <input type="text" name="second_name" id="second_name" placeholder="Dupont" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-signature"></i>
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" placeholder="Jean" required>
                </article>

                <input class="btn" type="submit" name="create" value="Créer un compte">

                <p class="register-mention">Tous les champs sont obligatoires !</p>

                <article class="help-connect">
                    <a href="index.php">Se connecter</a>
                </article>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>