<?php
session_start();
if(!empty($_POST['securite'])) {
    header('Location: index.php&message=1');
}
$titre = 'Mot de passe oublié';
include("inc/header.inc.php");

require_once 'php/db_utilisateur.inc.php';
require_once 'php/myFct.inc.php';
use Utilisateur\UtilisateurRepository;

$message = "";

if(isset($_POST['submitBtn'])) {
    $utilisateurRepository = new UtilisateurRepository();
    $mail = htmlentities($_POST['email']);

    if($utilisateurRepository->existInDB($mail, $message)) {
        $newPass = randomPassword();
        $hashedPass = hash('sha256', $newPass);
        $u = $utilisateurRepository->getUtilisateurByData($mail, $message);
        $utilisateurRepository->updatePassword($u->uid, $hashedPass, $message);
        envoiMailMdp($mail, $newPass, $message);

    } else {
        $message = "Aucun compte n'est associé à cette adresse";
    }
}

?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Réinitialiser mot de passe
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
                    <label for="username"></label>
                    <input type="text" id="username" name="email" placeholder="Votre adresse mail" required>
                </section>

                <input class="btn" type="submit" name="submitBtn" value="Réinitialiser">

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