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

use Depense\DepenseRepository;
use Utilisateur\UtilisateurRepository;
use Groupe\GroupeRepository;
use Participer\ParticiperRepository;

$uid = $_SESSION['uid'];
$gid = $_GET['gid'];

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

$participerRepository = new ParticiperRepository();
$participe = $participerRepository->getConfirmeByUidAndGid($uid, $gid);

$titre = 'Groupe';
include("inc/header.inc.php");
?>
<main class="groupsMain">
    <h1><?php echo $group->nom ?></h1>
    <section>
        <form method="get" class="searchForm">
            <article class="textbox">
                <label for="expSearch"></label>
                <input type="search" id="expSearch" placeholder="Chercher une dépense">
                <button class="searchBtn">
                    <i class="fas fa-search"></i>
                </button>
            </article>
            <article class="searchFormAv">
                <details>
                    <summary>Recherche avancée</summary>
                    <label for="tag">Tag</label>
                    <input id="tag" name="tag" type="text" placeholder="Loisir">

                    <label for="montMin">Montant minimum</label>
                    <input id="montMin" name="montMin" type="number" min="0" step="0.01" placeholder="0€">

                    <label for="montMax">Montant maximum</label>
                    <input id="montMax" name="montMax" type="number" min="0" step="0.01" placeholder="0€">

                    <label for="dateDeb">Date début</label>
                    <input id="dateDeb" name="montMax" type="date">

                    <label for="dateFin">Date fin</label>
                    <input id="dateFin" name="montMax" type="date">
                </details>
            </article>
        </form>
    </section>

    <section>
        <article class="groupsArticle">
            <h3>Participants</h3>
            <table class="groupsTab">
                <tr>
                    <td>Participant</td>
                    <td>Montant dépensé</td>
                    <td>Différence à la dépense moyenne</td>
                </tr>

                <?php
                foreach ($utilisateurs as $u) {

                    echo '
        <tr>
          <td>' . $u->nom . ' ' . $u->prenom . '</td>
          <td>' . $depenseRepository->getTotalFromUserByGid($u->uid, $gid) . $devise . ' </td>
          <td>' . ($depenseRepository->getTotalFromUserByGid($u->uid, $gid) - $moyPerUser) . $devise . '</td>
        </tr>
              ';
                }
                ?>
            </table>

            <h3>Statistiques</h3>
            <table class="groupsTab">
                <tr>
                    <td>Dépenses totales</td>
                    <td>Moyenne par participant</td>
                </tr>
                <tr>
                    <td><?php echo $total . $devise ?></td>
                    <td><?php echo $moyPerUser . $devise ?></td>
                </tr>

            </table>

            <?php
            if ($participe->estConfirme == 1) {
                echo '
                <a href="addExp.php?gid=' . $gid . '" class="btnConsult">
                <i class="fas fa-wallet"></i>
                Ajouter dépense
            </a>
            <a href="confirmPay.php" class="btnConsult">
                <i class="fas fa-clipboard-check"></i>
                Solder groupe
            </a>
            <ul class="groupLinks">
                <li>
                    <a href="invite.php?gid=' . $gid . '">
                        <i class="fas fa-user-plus"></i>
                        Inviter
                    </a>
                </li>
                <li>
                    <a href="editGroup.php?gid=' . $gid . '">
                        <i class="fas fa-pen"></i>
                        Modifier
                    </a>
                </li>
                <li>
                    <a href="confirmDeleteGroup.php?gid=' . $gid . '">
                        <i class="fas fa-sign-out-alt"></i>
                        Supprimer
                    </a>
                </li>
            </ul>
                ';
            }
            ?>
            <h3>Historique des dépenses</h3>
            <table class="groupsTab">
                <tr>
                    <td>Date</td>
                    <td>Auteur</td>
                    <td>Montant</td>
                    <td>Libelle</td>
                    <td>Tag</td>
                    <?php
                    if ($participe->estConfirme == 1) {
                        echo '
                        <td>Scan</td>
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
          <td>' . $d->tag . '</td>';
                    if ($participe->estConfirme == 1) {
                        echo '
            <td>
                <a href="scan.php?did= ' . $d->did . '">Consulter</a>
            </td>
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