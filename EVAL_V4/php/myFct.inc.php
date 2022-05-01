<?php
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'db_utilisateur.inc.php';
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
    } else if($utilisateurRepository->existInDB($mail, $message)) { //TODO
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
        if(empty($messageToSend)) {
            $errors = 'Votre message est vide';
        }

        if(!empty($errors)) {
            $message = $errors;
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

function envoiMailMdp($email, $password, &$message) {
    try {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('contact@nodebt.com');
        $mail->addAddress($email);
        $mail->addReplyTo("no-reply@nodebt.com");
        $mail->isHTML(false);
        $mail->Subject = "NoDebt - Reinitialisation mot de passe";
        $mail->Body = "Voici votre nouveau mot de passe : '$password', n'oubliez pas de le changer le plus rapidement possible";
        $mail->send();
        $message =  "Votre nouveau mot de passe vous a été envoyé par mail";
    } catch (Exception $ex) {
        $message = "Une erreur est survenue lors de l'envoi du message : " . $mail->ErrorInfo;
    }
}

function envoiMailInvitation($emailInvite, $emailInviteur, $groupName, &$message) {
    try {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('contact@nodebt.com');
        $mail->addAddress($emailInvite);
        $mail->addCC($emailInviteur);
        $mail->addReplyTo("no-reply@nodebt.com");
        $mail->isHTML(false);
        $mail->Subject = "NoDebt - Vous avez reçu une invitation!";
        $mail->Body = "Vous avez reçu une invitation à rejoindre le groupe $groupName. Veuillez vous connecter à NoDebt pour accepter ou refuser l'invitation.";
        $mail->send();
        $message = "Invitation envoyée";
    } catch (Exception $ex) {
        $message = "Une erreur est survenue lors de l'envoi du message : " . $mail->ErrorInfo;
    }
}

function verifierParticipeDeja($participerRepository, $uid, $gid) {
    $participes = $participerRepository->getParticipeByUserId($uid);

    foreach ($participes as $p) {
        if($p->gid == $gid && $p->uid == $uid) {
            return true;
        }
    }

    return false;
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function verifySearch($dateDeb, $dateFin, $montMin, $montMax, &$message) {
    $regexDate = "/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d|3[0-1])$/";

    if((empty($dateDeb) && !empty($dateFin)) || (!empty($dateDeb) && empty($dateFin))) {
        $message = "La date de début ou de fin n'a pas été remplie.";
        return false;
    } else if((empty($montMin) && !empty($montMax)) || (!empty($montMin) && empty($montMax))) {
        $message = "Le montant minimum ou maximum n'a pas été rempli.";
        return false;
    } else if(strtotime($dateDeb) > strtotime($dateFin)) {
        $message = "La date de fin spécifiée est antérieure à la date de début spécifiée.";
        return false;
    } else if(intval($montMin) > intval($montMax)) {
        $message = "Le montant maximum est inférieur au montant minimum.";
        return false;
    } else if(!is_numeric($montMin) || !is_numeric($montMax)) {
        $message = "Le montant est invalide";
        return false;
    } else if(!preg_match($regexDate, $dateDeb) || !preg_match($regexDate, $dateFin)) {
        $message = "La date est invalide";
        return false;
    } else {
        return true;
    }
}