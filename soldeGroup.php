<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

if (!isset($_GET['gid'])) {
    header('Location: index.php');
}
if(!empty($_POST['securite'])) {
    header('Location: index.php&message=1');
}
require_once 'php/db_depense.inc.php';
require_once 'php/db_utilisateur.inc.php';
require_once 'php/db_groupe.inc.php';
require_once 'php/db_participe.inc.php';
require_once 'php/db_versement.inc.php';
require_once 'php/myFct.inc.php';

use Depense\DepenseRepository;
use Utilisateur\UtilisateurRepository;
use Groupe\GroupeRepository;
use Participer\ParticiperRepository;
use Versement\Versement;
use Versement\VersementRepository;

$uid = $_SESSION['uid'];
$gid = $_GET['gid'];


if (isset($_POST['non'])) {
    header('Location: index.php');
}

$participerRepository = new ParticiperRepository();
$participe = $participerRepository->getConfirmeByUidAndGid($uid, $gid);


$groupeRepository = new GroupeRepository();
$group = $groupeRepository->showGroupById($gid);

$devise = "";
if (strcmp($group->devise, "euro") == 0) {
    $devise = "€";
} else {
    $devise = "$";
}

$utilisateurRepository = new UtilisateurRepository();
$utilisateurs = $utilisateurRepository->getUtilisateursFromGid($gid);

$depenseRepository = new DepenseRepository();
$depenses = $depenseRepository->getAllDepenseByGid($gid);

$total = $depenseRepository->getTotalByGid($gid);
$moyPerUser = $total / sizeof($utilisateurs);

$differenceArray = array();

foreach ($utilisateurs as $u) {
    $diff = round(($depenseRepository->getTotalFromUserByGid($u->uid, $gid) - $moyPerUser), 2);
    $differenceArray[$u->uid] = $diff;
}

$uidArray = array();
foreach ($utilisateurs as $u) {
    $uidArray[] = $u->uid;
}

if (empty($depenses)) {
    $message = "Il n'y a aucune dépense pour ce groupe";
} else if (sizeof($utilisateurs) == 1) {
    $message = "Il n'y a qu'un seul utilisateur dans ce groupe";
} else {
    $versements = array();
    list($versements, $differenceArray) = calculerVersements($differenceArray, $versements);

    foreach ($versements as $v) {
        $v->gid = $gid;
        $v->estConfirme = 0;
    }
}


if (isset($_POST['oui'])) {
    $message = "";
    $versementRepository = new VersementRepository();
    $versementRepository->storeVersements($versements, $message);
}

$titre = 'Solder groupe';
include("inc/header.inc.php");
?>
<main class="groupsMain">
    <h1>Solder groupe</h1>
    <section>

        <form method="post">
            <fieldset class="fieldset-box">
                <h2>Versements à effectuer</h2>
                <?php
                if (!empty($message)) {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">' . $message . '</h2>
                        <section class="help-connect">
                            <a href="group.php?gid=' . $gid . '">Revenir au groupe</a>
                        </section>
                    </section>
                    ';
                }
                ?>
                <?php
                if (isset($versements)) {
                    echo '
                        <table class="groupsTab">
                <tr>
                    <td>Verseur</td>
                    <td>Bénéficiaire</td>
                    <td>Montant</td>
                </tr>';
                    foreach ($versements as $v) {

                        $verseur = $utilisateurRepository->getUtilisateurById($v->uidVerseur);
                        $benefi = $utilisateurRepository->getUtilisateurById($v->uidBenefi);
                        echo '    
                    <tr>
                        <td>' . $verseur->nom . ' ' . $verseur->prenom . '</td>
                        <td>' . $benefi->nom . ' ' . $benefi->prenom . '</td>
                        <td>' . $v->montant . $devise . '</td>
                    </tr>
                    ';
                    }
                    echo '</table>';
                }
                ?>


                <?php
                if (empty($message)) {
                    echo '
                <input class="btn" type="submit" name="oui" value="Confirmer solde">
                <input class="btn" type="submit" name="non" value="Annuler">
                    ';
                }
                ?>
                <label class="securite"><span></span><input type="text" name="securite" value=""/></label>
            </fieldset>
        </form>
    </section>
</main>
