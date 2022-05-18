<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

if (!isset($_GET['gid'])) {
    header('Location: index.php');
}
require_once 'php/db_participe.inc.php';
require_once 'php/db_depense.inc.php';
require_once 'php/db_utilisateur.inc.php';
require_once 'php/db_groupe.inc.php';
require_once 'php/db_versement.inc.php';
require_once 'php/myFct.inc.php';

use Participer\ParticiperRepository;
use Depense\DepenseRepository;
use Utilisateur\UtilisateurRepository;
use Groupe\GroupeRepository;
use Versement\VersementRepository;

$gid = $_GET['gid'];
$uid = $_SESSION['uid'];

$search = htmlentities($_GET['search']);
$montMin = htmlentities($_GET['montMin']);
$montMax = htmlentities($_GET['montMax']);
$dateDeb = htmlentities($_GET['dateDeb']);
$dateFin = htmlentities($_GET['dateFin']);

$utilisateurRepository = new UtilisateurRepository();

$participerRepository = new ParticiperRepository();
$participe = $participerRepository->getConfirmeByUidAndGid($uid, $gid);

$depenseRepository = new DepenseRepository();

$versementRepository = new VersementRepository();
$versements = $versementRepository->getVersementsofGid($gid);
$isSold = !empty($versements);

$message = "";

if (!verifySearch($dateDeb, $dateFin, $montMin, $montMax, $message)) {
    $depenses = array();
} else {
    if (empty($montMin) && empty($montMax) && empty($dateDeb) && empty($dateFin)) {
        if (!empty($search)) {
            setcookie("search", $search, time()+(3600*24*30), '/~e200284/');
            $depenses = $depenseRepository->getDepenseByLibelleOrTag($gid, $search);
        } else {
            $depenses = array();
        }
    } else {
        if (empty($dateDeb) || empty($dateFin)) {
            setcookie("montMin", $montMin, time()+(3600*24*30), '/~e200284/');
            setcookie("montMax", $montMax, time()+(3600*24*30), '/~e200284/');
            $depenses = $depenseRepository->getDepenseByMontant($gid, $montMin, $montMax);
        } else if (empty($montMin) || empty($montMax)) {
            setcookie("dateDeb", $dateDeb, time()+(3600*24*30), '/~e200284/');
            setcookie("dateFin", $dateFin, time()+(3600*24*30), '/~e200284/');
            $depenses = $depenseRepository->getDepenseByDate($gid, $dateDeb, $dateFin);
        } else if (empty($search)) {
            setcookie("montMin", $montMin, time() + (3600 * 24 * 30), '/~e200284/');
            setcookie("montMax", $montMax, time() + (3600 * 24 * 30), '/~e200284/');
            setcookie("dateDeb", $dateDeb, time() + (3600 * 24 * 30), '/~e200284/');
            setcookie("dateFin", $dateFin, time() + (3600 * 24 * 30), '/~e200284/');
            $depenses = $depenseRepository->getDepenseByMontantAndDate($gid, $montMin, $montMax, $dateDeb, $dateFin);
        } else {
            setcookie("search", $search, time()+(3600*24*30), '/~e200284/');
            setcookie("montMin", $montMin, time()+(3600*24*30), '/~e200284/');
            setcookie("montMax", $montMax, time()+(3600*24*30), '/~e200284/');
            setcookie("dateDeb", $dateDeb, time()+(3600*24*30), '/~e200284/');
            setcookie("dateFin", $dateFin, time()+(3600*24*30), '/~e200284/');
            $depenses = $depenseRepository->getDepenseByAdvancedSearch($gid, $search, $montMin, $montMax, $dateDeb, $dateFin);
        }
    }
}

$groupeRepository = new GroupeRepository();
$group = $groupeRepository->showGroupById($gid);
$devise = "";
if (strcmp($group->devise, "euro") == 0) {
    $devise = "€";
} else {
    $devise = "$";
}

$titre = 'Recherche';
include("inc/header.inc.php");
?>
<main class="groupsMain">
    <h1>Résultat de la recherche</h1>
    <section>
        <article class="groupsSection">
            <?php
            if (!empty($message)) {
                echo '<h2>' . $message . '</h2>';
            }
            if (empty($depenses)) {
                echo '<h2>Aucune dépense n\'a été trouvée.</h2>';
            } else {
            ?>
            <table class="groupsTab">
                <tr>
                    <td>Date</td>
                    <td>Auteur</td>
                    <td>Montant</td>
                    <td>Libelle</td>
                    <td>Tag</td>
                    <td>Facture</td>
                    <?php
                    if ($participe->estConfirme == 1 && !$isSold) {
                        echo '
                        <td>Editer</td>
                        ';
                    }
                    ?>
                </tr>
                <?php
                }
                foreach ($depenses as $d) {
                    $u = $utilisateurRepository->getUtilisateurById($d->uid);
                    echo '
              <tr>
          <td>' . $d->date . '</td>
          <td>' . $u->nom . ' ' . $u->prenom . '</td>
          <td>' . $d->montant . $devise . '</td>
          <td>' . $d->libelle . '</td>
          <td>' . $d->tag . '</td>
          <td>
                <a href="scan.php?did= ' . $d->did . '">Consulter</a>
          </td>';
                    if ($participe->estConfirme == 1  && !$isSold) {
                        echo '
            <td>
                <a href="addExp.php?gid=' . $gid . '&did=' . $d->did . '"><i class="fas fa-pen"></i></a>
                <a href="confirmDeleteExp.php?did= ' . $d->did . '&gid=' . $gid . '"><i class="fas fa-times"></i></a>
            </td>
        </tr>
              ';
                    }
                }
                ?>
            </table>
        </article>
    </section>
</main>
