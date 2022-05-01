<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

$titre = 'Edition mot de passe';
include("inc/header.inc.php");
require_once 'php/db_utilisateur.inc.php';
use Utilisateur\UtilisateurRepository;

$message = "";
if(isset($_POST['submitBtn'])) {
    $utilisateurRepository = new UtilisateurRepository();
    $password = htmlentities($_POST['password']);
    $passwordV = htmlentities($_POST['passwordV']);
    if(strcmp($password, $passwordV) !== 0) {
        $message = "Votre mot de passe diffère de sa confirmation";
    } else {
        $utilisateurRepository->updatePassword($_SESSION['uid'], hash('sha256', $password), $message);
        $message = "Votre mot de passe a bien été modifié";
    }
}

?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Modifier mot de passe
                </h1>
                <section class="alertbox">
                    <?php
                    if(!empty($message)) {
                        echo '<h2 class="connexion-message">'.$message.'</h2>';
                    }
                    ?>
                </section>
                <section class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </section>
                <section class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Confirmer mot de passe</label>
                    <input type="password" id="password" name="passwordV" placeholder="••••••••" required>
                </section>

                <input class="btn" type="submit" name="submitBtn" value="Modifier">

            </fieldset>
        </form>
    </section>
</main>
</body>
</html>