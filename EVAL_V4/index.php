<?php
session_start();
$titre = 'Connexion';
include("inc/header.inc.php");
require 'php/db_utilisateur.inc.php';
require_once 'php/myFct.inc.php';

use Utilisateur\UtilisateurRepository;

$message = "";
$utilisateurRepository = new UtilisateurRepository();

$valid = true;
if(!empty($_POST['securite']) || isset($_GET['message'])) {
    session_start();
    session_destroy();
    $message = "Une erreur est survenue";
    $valid = false;
}

if(isset($_SESSION['uid'])) {
    header('Location: myGroups.php');
}

if(isset($_POST['submitBtn']) && $valid) {
    $courriel = htmlentities($_POST['courriel']);
    $mdp = htmlentities($_POST['password']);
    $data = array($courriel, $mdp);

    $utilisateur = $utilisateurRepository->getUtilisateurByData($courriel, $message);
    $hashedPass = hash('sha256', $mdp);
    if($utilisateur !== false && strcmp($hashedPass, $utilisateur->motPasse) == 0 && $utilisateur->estActif == 0) {
        envoiMailReactiverCompte($utilisateur->courriel, $utilisateur->uid, $message);
        $message = "Votre compte a été désactivé, pour le réactiver, cliquez sur le lien dans le mail qui vient de vous être envoyé";
    } else if($utilisateur !== false && strcmp($hashedPass, $utilisateur->motPasse) == 0) {
        $_SESSION['prenom'] = $utilisateur->prenom;
        $_SESSION['nom'] = $utilisateur->nom;
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
                <section>
                <?php
                if(!empty($message)) {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">'.$message .'</h2>
                    </section>
                    ';
                }
                ?>
                </section>
                <section class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="username">Adresse mail</label>
                    <input name="courriel" type="text" id="username" placeholder="jean@exemple.com" required>
                </section>
                <section class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Mot de passe</label>
                    <input name="password" type="password" id="password" placeholder="••••••••" required>
                </section>

                <input class="btn" type="submit" name="submitBtn" value="Se connecter">

                <section class="help-connect">
                    <a href="register.php">Créer un compte</a>
                    <a href="forgotPass.php">Mot de passe oublié</a>
                </section>
                <label class="securite"><span></span><input type="text" name="securite" value=""/></label>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>