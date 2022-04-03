<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

$titre = 'Editer Groupe';
include("inc/header.inc.php");


?>
<main>
    <section>
        <form method="post" action="myGroups.php">
            <fieldset class="fieldset-box">
                <h1>
                    Créer groupe
                </h1>
                <article class="textbox">
                    <i class="fas fa-users"></i>
                    <label for="username">Nom du groupe</label>
                    <input type="text" id="username" placeholder="Nom" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-money-bill-wave"></i>
                    <label for="particiDep">Devise</label>
                    <select id="particiDep" name="particiDep" required>
                        <option value="" disabled selected hidden>Choisissez une devise</option>
                        <option value="Nom Prenom">Euro €</option>
                        <option value="Nom Prenom">Dollar $</option>
                    </select>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Confirmer">
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>