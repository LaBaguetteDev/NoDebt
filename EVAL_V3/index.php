<?php
session_start();
$titre = 'Connexion';
include("inc/header.inc.php");
require 'php/db_utilisateur.inc.php';

use Utilisateur\UtilisateurRepository;

$message = "";
$utilisateurRepository = new UtilisateurRepository();

if(isset($_SESSION['uid'])) {
    header('Location: myGroups.php');
}

if(isset($_POST['submitBtn'])) {
    $courriel = htmlentities($_POST['courriel']);
    $mdp = htmlentities($_POST['password']);
    $data = array($courriel, $mdp);

    $utilisateur = $utilisateurRepository->getUtilisateurByData($courriel, $message);
    $hashedPass = hash('sha256', $mdp);
    if($utilisateur !== false && strcmp($hashedPass, $utilisateur->motPasse) == 0) {
        $_SESSION['prenom'] = $utilisateur->prenom;
        $_SESSION['uid'] = $utilisateur->uid;
        header('Location: myGroups.php');
    } else {
        $message = "Votre courriel ou votre mot de passe est incorrect.";
    }
}
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Connexion
                </h1>
                <article>
                <?php
                if(!empty($message)) {
                    echo '
                    <article class="alertbox">
                        <h3 class="connexion-message">'.$message .'</h3>
                    </article>
                    ';
                }
                ?>
                </article>
                <article class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="username">Adresse mail</label>
                    <input name="courriel" type="text" id="username" placeholder="jean@exemple.com" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Mot de passe</label>
                    <input name="password" type="password" id="password" placeholder="••••••••" required>
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