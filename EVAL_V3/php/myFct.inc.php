<?php
require "db_utilisateur.inc.php";
use Utilisateur\UtilisateurRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function newAccountIsValid($mail,$mot_de_passe, $mdpv, &$message) {
    $memberRepository = new UtilisateurRepository();
    if ($mot_de_passe !== $mdpv) {
        $message .= 'Votre mot de passe diffère de sa confirmation.<br>';
        return false;
    }
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $message .= 'Votre adresse courriel est invalide.<br>';
        return false;
    }
    if ($memberRepository->existInDB($mail, $message)){
        $message .= 'Vous êtes déjà inscrit avec ce courriel.<br>';
        return false;
    }
    return true;
}

function editAccountIsValid($mail, &$message) {
    $utilisateurRepository = new UtilisateurRepository();
    if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $message = "Votre email est invalide";
        return false;
    } else if($utilisateurRepository->existInDB($mail, $message)) {
        $message = "Un compte existe déjà avec cette adresse mail";
        return false;
    }
    return true;
}



function envoiMail($email, $subject, $messageToSend, &$message) {
    $errors = [];
    if(!empty($_POST)) {
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
                $mail->Body = $messageToSend;
                $mail->send();
                $message =  "Message envoyé";
            } catch (Exception $ex) {
                $message = "Une erreur est survenue lors de l'envoi du message : " . $mail->ErrorInfo;
            }
        }
    }

}