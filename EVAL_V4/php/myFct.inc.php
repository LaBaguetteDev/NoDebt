<?php
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'db_utilisateur.inc.php';
require_once 'db_versement.inc.php';
use Utilisateur\UtilisateurRepository;
use Versement\VersementRepository;
use Versement\Versement;
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
    if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $message = "Votre email est invalide";
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

function calculerVersements(array $differenceArray, array $versements)
{
    foreach ($differenceArray as $currentUid => $currentD) {
        // Vérifier que la dépense courante n'est pas négative
        if ($currentD > 0) {
            while ($currentD != 0) {
                $initialD = $currentD;
                $firstNeg = getFirstNegative($differenceArray); // Récupérer la première différence négative
                $currentD += $firstNeg[1]; // Soustraire la dépense négative à la dépense courante

                // Si la soustraction donne un résultat négatif
                if ($currentD < 0) {
                    $prob = abs($currentD);
                    $firstNeg[1] = $currentD;

                    // Création de l'objet versement
                    $versement = new Versement();
                    $versement->uidVerseur = $firstNeg[0];
                    $versement->uidBenefi = $currentUid;
                    $versement->montant = $initialD;
                    $versements[] = $versement;

                    // Mise de la dépense courante à 0
                    $currentD += $prob;

                    // Actualiser le tableau initial
                    actualizeFirstNeg($differenceArray, $firstNeg);
                    actualizeCurrent($differenceArray, $currentUid, $currentD);

                    // Si la soustraction ne suffit pas à mettre la dépense courante à 0
                } else if ($currentD > 0) {

                    // Création de l'objet versement
                    $versement = new Versement();
                    $versement->uidVerseur = $firstNeg[0];
                    $versement->uidBenefi = $currentUid;
                    $versement->montant = abs($firstNeg[1]);
                    $versements[] = $versement;

                    // Actualiser le tableau initial
                    $firstNeg[1] = 0;
                    actualizeFirstNeg($differenceArray, $firstNeg);
                    actualizeCurrent($differenceArray, $currentUid, $currentD);
                } else {
                    $versement = new Versement();
                    $versement->uidVerseur = $firstNeg[0];
                    $versement->uidBenefi = $currentUid;
                    $versement->montant = abs($firstNeg[1]);
                    $versements[] = $versement;
                }
            }
        }
    }
    return array($versements, $differenceArray);
}

function getFirstNegative($differenceArray) {
    foreach ($differenceArray as $uid => $d) {
        if($d < 0) {
            return array($uid, $d);
        }
    }
    return NULL;
}
function actualizeFirstNeg(&$differenceArray, $firstNeg) {
    foreach ($differenceArray as $uid => $d) {
        if($firstNeg[0] == $uid) {
            $differenceArray[$uid] = $firstNeg[1];
        }
    }
}
function actualizeCurrent(&$differenceArray, $currentUid, $currentD) {
    foreach ($differenceArray as $uid => $d) {
        if($currentUid == $uid) {
            $differenceArray[$uid] = $currentD;
        }
    }
}

function verifyGroupCanBeDeleted($versements) {
    foreach ($versements as $v) {
        if($v->estConfirme == 0) {
            return false;
        }
    }

    return true;
}

function verifyAccountCanBeDeleted($uid, $groups, $participations) {
    $myGroupsArray = null;
    $versementRepository = new VersementRepository();

    while ($group = $groups->fetch(PDO::FETCH_ASSOC)) {
        foreach ($participations as $participation) {
            if ($participation->uid == $uid && $participation->estConfirme) {
                $v = $versementRepository->getVersementsofGid($group['gid']);
                if(empty($v)) {
                    return false;
                }
            }
        }
    }

    return true;

}