<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    $titre = 'Contact';
    include("inc/header.inc.php");

    $errors = [];

    function envoiMail() {
        if(!empty($_POST)) {
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            if(empty($email)) {
                $errors = 'Votre email est vide';
            } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors = 'email non valide';
            }
            if(empty($subject)) {
                $errors = 'Votre sujet est vide';
            }
            if(empty($message)) {
                $errors = 'Votre message est vide';
            }

            if(!empty($errors)) {
                $allErrors = join('<br>', $errors);
                echo "<p style='color: red;'>{$allErrors}</p>";
            } else {
                try {
                    $mail = new PHPMailer(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->setFrom('contact@nodebt.com');
                    $mail->addAddress("f.detiffe@student.helmo.be");
                    $mail->addReplyTo("no-reply@nodebt.com");
                    $mail->isHTML(false);
                    $mail->Subject = "Contact NoDebt - " . $subject;
                    $mail->Body = $message;
                    $mail->send();
                    echo "<p style='color: lawngreen'>Message envoyé</p>";
                } catch (Exception $ex) {
                    echo "<p style='color: red'>Une erreur est survenue lors de l'envoi du message : " . $mail->ErrorInfo . "</p>";
                }
            }
        }
    }

?>
<main>
    <section>
        <form method="POST" action="contact.php" id="contact-form">
            <fieldset class="fieldset-box">
                <h1>
                    Nous contacter
                </h1>
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
                    <?php
                        envoiMail();
                    ?>
                    <a href="index.php">Se connecter</a>
                </article>
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>