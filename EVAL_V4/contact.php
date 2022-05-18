<?php
session_start();
if(!empty($_POST['securite'])) {
    header('Location: index.php&message=1');
}
$titre = 'Contact';
include("inc/header.inc.php");
require_once 'php/myFct.inc.php';
require_once 'php/db_utilisateur.inc.php';

use Utilisateur\UtilisateurRepository;

$createur = false;
if(isset($_SESSION['uid'])) {
    $utilisateurRepository = new UtilisateurRepository();
    $createur = $utilisateurRepository->getUtilisateurById($_SESSION['uid']);
}

$message = "";
if(isset($_POST['submitBtn'])) {

    if(!isset($_POST['email']) || !isset($_POST['subject']) || !isset($_POST['message'])) {
        $message = "Un champ est manquant";
    } else {
        $email = htmlentities($_POST['email']);
        $subject = htmlentities($_POST['subject']);
        $messageToSend = htmlentities($_POST['message']);

        envoiMail($email,$subject, $messageToSend, $message);
    }
}

?>
<main>
    <section>
        <form method="POST" id="contact-form">
            <fieldset class="fieldset-box">
                <h1>
                    Nous contacter
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
                <section class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="email"></label>
                    <input type="text" name="email" id="email" placeholder="Votre adresse mail" required
                           value="<?php if (isset($_POST['email'])) echo $_POST['email'];  elseif($createur !== false) echo $createur->courriel;?>">
                </section>
                <section class="textbox">
                    <i class="fas fa-pen"></i>
                    <label for="subject"></label>
                    <input type="text" name="subject" id="subject" placeholder="Sujet du message" required value="<?php if(isset($_POST['subject'])) echo $_POST['subject'] ?>">
                </section>
                <section class="textbox">
                    <i class="fas fa-comment-alt"></i>
                    <label for="message"></label>
                    <textarea name="message" id="message" placeholder="Votre message" required><?php if(isset($_POST['message'])) echo $_POST['message']?></textarea>
                </section>

                <input class="btn" type="submit" name="submitBtn" value="Envoyer">

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