<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

$titre = 'Modifier profil';
include("inc/header.inc.php");

require 'php/db_utilisateur.inc.php';

use Utilisateur\UtilisateurRepository;

$utilisateurRepository = new UtilisateurRepository();
$message = "";
$utilisateur = $utilisateurRepository->getUtilisateurById($_SESSION['uid'], $message);

//TODO Appeler la DB lors du clic sur modifier
?>
<main>
    <section>
        <form method="post" action="register.php">
            <fieldset class="fieldset-box">
                <h1 class="register-screen-title">
                    Modifier votre profil
                </h1>
                <h2>
                    Vos identifiants
                </h2>
                <article class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="email">Adresse mail</label>
                    <input type="email" name="email" id="email" placeholder="jean@exemple.com" required value="<?php echo $utilisateur->courriel?>">
                </article>

                <h2>
                    Vos infos personnelles
                </h2>
                <article class="textbox">
                    <i class="fas fa-id-card"></i>
                    <label for="second_name">Nom</label>
                    <input type="text" name="second_name" id="second_name" placeholder="Dupont" required value="<?php echo $utilisateur->nom?>">
                </article>
                <article class="textbox">
                    <i class="fas fa-signature"></i>
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" placeholder="Jean" required value="<?php echo $utilisateur->prenom?>">
                </article>

                <input class="btn" type="submit" name="create" value="Modifier le profil">

                <p class="register-mention">Tous les champs sont obligatoires !</p>

                <?php
                if(!empty($message)) {
                    echo '<p class="register-message">'.$message.'</p>';
                }
                ?>

                <article class="help-connect">
                    <a href="editPassword.php">Modifier mot de passe</a>
                    <a href="#">Supprimer mon compte</a>
                </article>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>