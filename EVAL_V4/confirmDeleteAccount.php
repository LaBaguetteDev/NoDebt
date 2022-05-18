<?php
session_start();

if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}
if(!empty($_POST['securite'])) {
    header('Location: index.php&message=1');
}
require_once 'php/myFct.inc.php';
require_once 'php/db_groupe.inc.php';
require_once 'php/db_utilisateur.inc.php';
require_once 'php/db_participe.inc.php';
use Groupe\GroupeRepository;
use Utilisateur\UtilisateurRepository;
use Participer\ParticiperRepository;

$groupRepository = new GroupeRepository();
$utilisateurRepository = new UtilisateurRepository();
$u = $utilisateurRepository->getUtilisateurById($_SESSION['uid']);
$groups = $groupRepository->showGroups();
$participerRepository = new ParticiperRepository();
$participations = $participerRepository->getParticipeByUserId($_SESSION['uid']);

$message ="";
if(!verifyAccountCanBeDeleted($_SESSION['uid'], $groups, $participations)) {
    $message = "Vous êtes dans un ou plusieurs groupe encore non soldé";
}

if(isset($_POST['oui'])) {
    $utilisateurRepository->setInactif($_SESSION['uid'], $message);
    envoiMailCompteDesactive($u->courriel, $message);
    session_destroy();
    header('Location: index.php');
}

if(isset($_POST['non'])) {
    header('Location: index.php');
}

$titre = 'Connexion';
include("inc/header.inc.php");

?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>Supprimer profil</h1>

                <?php
                if(empty($message)) {
                    echo '
                <h2>Etes-vous sûr de vouloir supprimer votre profil ?</h2>

                <input class="btn" type="submit" name="oui" value="Oui">
                <input class="btn" type="submit" name="non" value="Non">
                    ';
                } else {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">'.$message .'</h2>
                        <section class="help-connect">
                            <a href="myGroups.php">Revenir à l\'acceuil</a>
                        </section>
                        
                    </section>
                    ';
                }
                ?>
                <label class="securite"><span></span><input type="text" name="securite" value=""/></label>
            </fieldset>

        </form>
    </section>
</main>
</body>
</html>