<?php
require 'db_utilisateur.inc.php';

use Utilisateur\UtilisateurRepository;

$message = "";
$utilisateurRepository = new UtilisateurRepository();

if(isset($_SESSION['uid'])) {
    header('Location: myGroups.php');
}

if(isset($_POST['submitBtn'])) {
    $courriel = htmlentities($_POST['courriel']);
    $mdp = htmlentities($_POST['password']);
    $data = array($courriel, $mdp);

    $utilisateur = $utilisateurRepository->getUtilisateurByData($courriel, $message);
    $hashedPass = hash('sha256', $mdp);
    if($utilisateur !== false && strcmp($hashedPass, $utilisateur->motPasse) == 0) {
        $_SESSION['prenom'] = $utilisateur->prenom;
        $_SESSION['uid'] = $utilisateur->uid;
        header('Location: myGroups.php');
    } else {
        $message = "Votre courriel ou votre mot de passe est incorrect.";
    }
}
?>