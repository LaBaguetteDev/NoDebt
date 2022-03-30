<?php

require_once 'myFct.inc.php';
require_once 'db_utilisateur.inc.php';

use Utilisateur\Utilisateur;
use Utilisateur\UtilisateurRepository;


$courriel = '';
$nom = '';
$prenom = '';
$motPasse = '';
$noError = '';
$utilisateurRepository = new UtilisateurRepository();

$message = '';
$valid = true;
$utilisateur2 = true;

if (isset($_POST['create'])) {
    if (strcmp($_POST['password'], $_POST['passwordv']) !== 0) {
        $message = 'Confirmation du mot de passe incorrect';
    } else {
        $utilisateur = new Utilisateur();
        $utilisateur->courriel = htmlentities($_POST['email']);
        $utilisateur->nom = htmlentities($_POST['second_name']);
        $utilisateur->prenom = htmlentities($_POST['first_name']);
        $utilisateur->motPasse = htmlentities($_POST['password']);
        $mailOk = $utilisateurRepository->existInDB(htmlentities($_POST['email']), $message);
        if (!$mailOk) {
            if (strlen($utilisateur->motPasse) >= 4) {
                $valid = isValid($utilisateur->courriel = htmlentities($_POST['email']), $utilisateur->motPasse = htmlentities($_POST['password']),
                    htmlentities($_POST['passwordv']), $message);
                if ($valid) {
                    $utilisateur->motPasse = hash('sha256', $utilisateur->motPasse);
                    $utilisateur2 = $utilisateurRepository->storeMember($utilisateur, $message);
                    $message = "Inscription terminée";
                } else {
                    $message = "Vos coordonnées ne sont pas valides";
                }
            }
        } else {
            $message = "Un compte est déjà existant avec cet adresse mail";
        }
    }
}

?>
