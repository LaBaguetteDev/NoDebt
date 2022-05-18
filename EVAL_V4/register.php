<?php
session_start();
if(!empty($_POST['securite'])) {
    header('Location: index.php&message=1');
}

$titre = 'Inscription';
include("inc/header.inc.php");
require_once 'php/myFct.inc.php';
require_once 'php/db_utilisateur.inc.php';

use Utilisateur\Utilisateur;
use Utilisateur\UtilisateurRepository;

$courriel = '';
$nom = '';
$prenom = '';
$motPasse = '';
$noError = '';
$utilisateurRepository = new UtilisateurRepository();

$message = '';
$valid = true;
$utilisateur2 = true;

if (isset($_POST['create'])) {
    if (strcmp($_POST['password'], $_POST['passwordv']) !== 0) {
        $message = 'Confirmation du mot de passe incorrect';
    } else {
        $utilisateur = new Utilisateur();
        $utilisateur->courriel = htmlentities($_POST['email']);
        $utilisateur->nom = htmlentities($_POST['second_name']);
        $utilisateur->prenom = htmlentities($_POST['first_name']);
        $utilisateur->motPasse = htmlentities($_POST['password']);
        $mailOk = $utilisateurRepository->existInDB(htmlentities($_POST['email']), $message);
        if (!$mailOk) {
            if (strlen($utilisateur->motPasse) >= 4) {
                $valid = newAccountIsValid($utilisateur->courriel = htmlentities($_POST['email']), $utilisateur->motPasse = htmlentities($_POST['password']),
                    htmlentities($_POST['passwordv']), $message);
                if ($valid) {
                    $utilisateur->motPasse = hash('sha256', $utilisateur->motPasse);
                    $utilisateur2 = $utilisateurRepository->storeMember($utilisateur, $message);
                    $_SESSION['prenom'] = $utilisateur->prenom;
                    $_SESSION['uid'] = $utilisateur->uid;

                    header('Location: myGroups.php');
                } else {
                    $message = "Vos coordonnées ne sont pas valides";
                }
            }
        } else {
            $message = "Un compte est déjà existant avec cet adresse mail";
        }
    }
}
?>
<main>
    <section>
        <form method="post" action="register.php">
            <fieldset class="fieldset-box">
                <h1 class="register-screen-title">
                    Créer un nouveau compte
                </h1>
                <h2>
                    Vos identifiants
                </h2>
                <section class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="email">Adresse mail</label>
                    <input type="email" name="email" id="email" placeholder="jean@exemple.com" required>
                </section>
                <section class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                </section>
                <section class="textbox">
                    <i class="fas fa-lock"></i>
                    <label for="passwordv">Confirmer mot de passe</label>
                    <input type="password" name="passwordv" id="passwordv" placeholder="••••••••" required>
                </section>

                <h2>
                    Vos infos personnelles
                </h2>
                <section class="textbox">
                    <i class="fas fa-id-card"></i>
                    <label for="second_name">Nom</label>
                    <input type="text" name="second_name" id="second_name" placeholder="Dupont" required>
                </section>
                <section class="textbox">
                    <i class="fas fa-signature"></i>
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" placeholder="Jean" required>
                </section>

                <input class="btn" type="submit" name="create" value="Créer un compte">

                <p class="register-mention">Tous les champs sont obligatoires !</p>

                <?php
                if(!empty($message)) {
                    echo '<p class="register-message">'.$message.'</p>';
                }
                ?>

                <section class="help-connect">
                    <a href="index.php">Se connecter</a>
                </section>
                <label class="securite"><span></span><input type="text" name="securite" value=""/></label>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>