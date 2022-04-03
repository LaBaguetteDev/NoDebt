<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

$titre = 'Inviter';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Inviter un membre
                </h1>
                <h3>
                    Indiquez l'adresse mail du membre à inviter
                </h3>
                <article class="textbox">
                    <i class="fas fa-user-plus"></i>
                    <label for="username"></label>
                    <input type="text" id="username" placeholder="Adresse email" required>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Envoyer invitation">

            </fieldset>
        </form>
    </section>
</main>
</body>
</html>