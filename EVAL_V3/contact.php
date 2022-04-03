<?php
session_start();
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
$titre = 'Contact';
include("inc/header.inc.php");

require_once 'php/contact.php';
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
                    <article class="alertbox">
                        <h3 class="connexion-message">'.$message.'</h3>
                    </article>
                    ';
                }
                ?>
                <article class="textbox">
                    <i class="fas fa-at"></i>
                    <label for="email"></label>
                    <input type="text" name="email" id="email" placeholder="Votre adresse mail" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-pen"></i>
                    <label for="subject"></label>
                    <input type="text" name="subject" id="subject" placeholder="Sujet du message" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-comment-alt"></i>
                    <label for="message"></label>
                    <textarea name="message" id="message" placeholder="Votre message" required></textarea>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Envoyer">

                <article class="help-connect">
                    <a href="index.php">Se connecter</a>
                </article>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>