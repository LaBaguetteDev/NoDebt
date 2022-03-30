<?php
$titre = 'Edition mot de passe';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post" action="myGroups.php">
            <fieldset class="fieldset-box">
                <h1>
                    Modifier mot de passe
                </h1>
                <?php
                if(!empty($message)) {
                    echo '<p class="connexion-message">'.$message.'</p>';
                }
                ?>
                <article class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" placeholder="••••••••" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Confirmer mot de passe</label>
                    <input type="password" id="password" placeholder="••••••••" required>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Modifier">

            </fieldset>
        </form>
    </section>
</main>
</body>
</html>