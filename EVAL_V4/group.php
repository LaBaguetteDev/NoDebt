<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

if (!isset($_GET['gid'])) {
    header('Location: index.php');
}
require_once 'php/db_depense.inc.php';
require_once 'php/db_utilisateur.inc.php';
require_once 'php/db_groupe.inc.php';
require_once 'php/db_participe.inc.php';
require_once 'php/db_versement.inc.php';

use Depense\DepenseRepository;
use Utilisateur\UtilisateurRepository;
use Groupe\GroupeRepository;
use Participer\ParticiperRepository;
use Versement\VersementRepository;

$uid = $_SESSION['uid'];
$gid = $_GET['gid'];

$versementRepository = new VersementRepository();

if (isset($_GET['vid'])) {
    $versementRepository->confirmerVersement($_GET['vid']);
}

$versements = $versementRepository->getVersementsofGid($gid);

$participerRepository = new ParticiperRepository();
$participe = $participerRepository->getConfirmeByUidAndGid($uid, $gid);

if(isset($_GET['accept'])) {
    if($participe->estConfirme !== 1) {
        $participerRepository->confirmerParticipe($uid, $gid, $message);
        $participe = $participerRepository->getConfirmeByUidAndGid($uid, $gid);
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

$utilisateurRepository = new UtilisateurRepository();
$utilisateurs = $utilisateurRepository->getUtilisateursFromGid($gid);

$depenseRepository = new DepenseRepository();
$depenses = $depenseRepository->getAllDepenseByGid($gid);

$total = $depenseRepository->getTotalByGid($gid);
$moyPerUser = $total / sizeof($utilisateurs);


$isSold = !empty($versements);

$titre = 'Groupe';
include("inc/header.inc.php");
?>
<main class="groupsMain">
    <h1><?php echo $group->nom ?></h1>
    <section>
        <form method="get" class="searchForm" action="searchResult.php">
            <input type="hidden" name="gid" value="<?php echo $gid?>">
            <section class="textbox">
                <label for="expSearch"></label>
                <input type="search" name="search" id="expSearch" placeholder="Chercher une dépense" value="<?php if(isset($_COOKIE['search'])) echo $_COOKIE['search']?>">
                <button class="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </section>
            <section class="searchFormAv">
                <details>
                    <summary>Recherche avancée</summary>
                    <label for="montMin">Montant minimum</label>
                    <input id="montMin" name="montMin" type="number" min="0" step="0.01" placeholder="0€" value="<?php if(isset($_COOKIE['montMin'])) echo $_COOKIE['montMin']?>">

                    <label for="montMax">Montant maximum</label>
                    <input id="montMax" name="montMax" type="number" min="0" step="0.01" placeholder="0€" value="<?php if(isset($_COOKIE['montMax'])) echo $_COOKIE['montMax']?>">

                    <label for="dateDeb">Date début</label>
                    <input id="dateDeb" name="dateDeb" type="date" value="<?php if(isset($_COOKIE['dateDeb'])) echo $_COOKIE['dateDeb']?>">

                    <label for="dateFin">Date fin</label>
                    <input id="dateFin" name="dateFin" type="date" value="<?php if(isset($_COOKIE['dateFin'])) echo $_COOKIE['dateFin']?>">
                </details>
            </section>
        </form>
    </section>

    <section>
        <article class="groupsSection">
            <h2>Participants</h2>
            <table class="groupsTab">
                <tr>
                    <td>Participant</td>
                    <td>Montant dépensé</td>
                    <td>Différence à la dépense moyenne</td>
                </tr>

                <?php
                foreach ($utilisateurs as $u) {
                    $uTotal = $depenseRepository->getTotalFromUserByGid($u->uid, $gid);
                    if($uTotal == NULL) $uTotal = 0;
                    echo '
        <tr>
          <td>' . $u->nom . ' ' . $u->prenom . '</td>
          <td>' . $uTotal . $devise . ' </td>
          <td>' . round(($depenseRepository->getTotalFromUserByGid($u->uid, $gid) - $moyPerUser), 2) . $devise . '</td>
        </tr>
              ';
                }
                ?>
            </table>

            <?php
            if($isSold) {
                echo '
                <h2>Versements</h2>
                <table class="groupsTab">
                <tr>
                    <td>Verseur</td>
                    <td>Bénéficiaire</td>
                    <td>Montant</td>
                    <td>Confirmation</td>
                </tr>';

                foreach ($versements as $v) {
                    $verseur = $utilisateurRepository->getUtilisateurById($v->uidVerseur);
                    $benefi = $utilisateurRepository->getUtilisateurById($v->uidBenefi);
                    $confirm = $v->uidBenefi == $_SESSION['uid'];
                    echo '
                <tr>
                    <td>' . $verseur->nom . ' ' . $verseur->prenom . '</td>
                    <td>' . $benefi->nom . ' ' . $benefi->prenom . '</td>
                    <td>' . $v->montant . $devise . '</td>
                    ';
                    if ($confirm && $v->estConfirme == 0) {
                        echo '<td><a href="group.php?gid=' . $gid . '&vid=' . $v->vid . '"><i class="fas fa-check"></i></a></td>';
                    } else {
                        if ($v->estConfirme == 0) {
                            echo '<td>Non confirmé</td>';
                        } else {
                            echo '<td>Confirmé</td>';
                        }
                    }
                    echo '</tr>
                ';
                }
                echo '
                </table>
                ';
            }
            ?>


            <h2>Statistiques</h2>
            <table class="groupsTab">
                <tr>
                    <td>Dépenses totales</td>
                    <td>Moyenne par participant</td>
                </tr>
                <tr>
                    <td><?php echo $total . $devise ?></td>
                    <td><?php echo round($moyPerUser, 2) . $devise ?></td>
                </tr>

            </table>

            <?php
            if ($participe->estConfirme == 1) {
                echo '
                <a href="addExp.php?gid=' . $gid . '" class="btnConsult">
                <i class="fas fa-wallet"></i>
                Ajouter dépense
            </a>';
                if($isSold) {
                    echo '
            <a href="cancelSoldeGroup.php?gid=' . $gid . '" class="btnConsult">
                <i class="fas fa-clipboard-check"></i>
                Annuler solde
            </a> ';
                } else {
                    echo '
                <a href="soldeGroup.php?gid=' . $gid . '" class="btnConsult">
                <i class="fas fa-clipboard-check"></i>
                Solder solde
            </a>
            ';
                }
                echo '
            <ul class="groupLinks">
                <li>
                    <a href="invite.php?gid=' . $gid . '" class="btnConsult">
                        <i class="fas fa-user-plus"></i>
                        Inviter
                    </a>
                </li>
                <li>
                    <a href="editGroup.php?gid=' . $gid . '" class="btnConsult">
                        <i class="fas fa-pen"></i>
                        Modifier
                    </a>
                </li>
                <li>
                    <a href="confirmDeleteGroup.php?gid=' . $gid . '" class="btnConsult">
                        <i class="fas fa-sign-out-alt"></i>
                        Supprimer
                    </a>
                </li>
            </ul>
                ';
            }
            ?>
            <h2>Historique des dépenses</h2>
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
                    if ($participe->estConfirme == 1 && !$isSold) {
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
</body>
</html>