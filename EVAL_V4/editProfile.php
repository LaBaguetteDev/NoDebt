<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

$titre = 'Modifier profil';
include("inc/header.inc.php");

require_once 'php/db_utilisateur.inc.php';
require_once 'php/myFct.inc.php';
use Utilisateur\Utilisateur;
use Utilisateur\UtilisateurRepository;

if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

$uid = $_SESSION['uid'];

$utilisateurRepository = new UtilisateurRepository();
$curentUtilisateur = $utilisateurRepository->getUtilisateurById($uid);
$message = "";

if(isset($_POST['modify'])) {

    if(empty($_POST['email']) || empty($_POST['second_name']) || empty($_POST['first_name'])) {
        $message = "Un champ est manquant";
    } else if(editAccountIsValid($_POST['email'], $message)) {
        $utilisateur = new Utilisateur();
        $utilisateur->courriel = htmlentities($_POST['email']);
        $utilisateur->nom = htmlentities($_POST['second_name']);
        $utilisateur->prenom = htmlentities($_POST['first_name']);


        $utilisateurRepository->updateUtilisateur($utilisateur, $uid, $message);
        $curentUtilisateur = $utilisateurRepository->getUtilisateurById($uid);
    }
}
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1 class="register-screen-title">
                    Modifier votre profil
                </h1>
                <?php
                if(!empty($message)) {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">'.$message.'</h2>
                    </section>
                    ';
                }
                ?>
                <h2>
                    Vos identifiants
                </h2>
                <section class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="email">Adresse mail</label>
                    <input type="email" name="email" id="email" placeholder="jean@exemple.com" required value="<?php echo $curentUtilisateur->courriel?>">
                </section>

                <h2>
                    Vos infos personnelles
                </h2>
                <section class="textbox">
                    <i class="fas fa-id-card"></i>
                    <label for="second_name">Nom</label>
                    <input type="text" name="second_name" id="second_name" placeholder="Dupont" required value="<?php echo $curentUtilisateur->nom?>">
                </section>
                <section class="textbox">
                    <i class="fas fa-signature"></i>
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" placeholder="Jean" required value="<?php echo $curentUtilisateur->prenom?>">
                </section>

                <input class="btn" type="submit" name="modify" value="Modifier le profil">

                <p class="register-mention">Tous les champs sont obligatoires !</p>

                <section class="help-connect">
                    <a href="editPassword.php">Modifier mot de passe</a>
                    <a href="confirmDeleteAccount.php">Supprimer mon profil</a>
                </section>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>