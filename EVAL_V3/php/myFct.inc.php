<?php
require "db_utilisateur.inc.php";
use Utilisateur\UtilisateurRepository;

function isValid($mail,$mot_de_passe, $mdpv, &$message) {
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