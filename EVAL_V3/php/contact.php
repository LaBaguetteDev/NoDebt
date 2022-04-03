<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];

$message = "";
if(isset($_POST['submitBtn'])) {
    envoiMail($message);
}


function envoiMail(&$message) {
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
                $mail->addCC($email);
                $mail->addReplyTo("no-reply@nodebt.com");
                $mail->isHTML(false);
                $mail->Subject = "Contact NoDebt - " . $subject;
                $mail->Body = $message;
                $mail->send();
                $message =  "Message envoyé";
            } catch (Exception $ex) {
                $message = "Une erreur est survenue lors de l'envoi du message : " . $mail->ErrorInfo;
            }
        }
    }
}

