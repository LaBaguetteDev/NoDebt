<?php

require_once 'php/db_groupe.inc.php';
require_once 'php/db_utilisateur.inc.php';
require_once 'php/db_participe.inc.php';
require_once 'php/db_depense.inc.php';

use Groupe\GroupeRepository;
use Utilisateur\UtilisateurRepository;
use Participer\ParticiperRepository;
use Depense\DepenseRepository;

session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
}
$message = "";
$groupRepository = new GroupeRepository();
$utilisateurRepository = new UtilisateurRepository();
$groups = $groupRepository->showGroups();
$participerRepository = new ParticiperRepository();
$participations = $participerRepository->getParticipeByUserId($_SESSION['uid']);

$depensesRepository = new DepenseRepository();

$groupsArray = null;
$myGroupsArray = null;
$inviteGroupsArray = null;

$titre = 'Mes groupes';
include("inc/header.inc.php");
?>
<main class="groupsMain">
    <h1>Mes groupes</h1>
    <section>
        <?php

        while ($group = $groups->fetch(PDO::FETCH_ASSOC)) {
            $groupsArray[] = $group;
            foreach ($participations as $participation) {
                if ($participation->uid == $_SESSION['uid'] && $participation->estConfirme) {
                    $devise = "";
                    if (strcmp($group['devise'], "euro") == 0) {
                        $devise = "€";
                    } else {
                        $devise = "$";
                    }
                    $myGroupsArray[] = $group;
                    $createur = $utilisateurRepository->getUtilisateurById($group['uid']);
                    $depenses = $depensesRepository->getThreeLastDepenseByGid($group['gid']);
                    echo '
            <article class="groupsArticle">
            <h2>' . $group['nom'] . '</h2>
            <h3>Dernières dépenses</h3>
            <table class="groupsTab">
                <tr>
                    <td>Date</td>
                    <td>Auteur</td>
                    <td>Dépense</td>
                </tr>';

                    foreach ($depenses as $d) {
                        $u = $utilisateurRepository->getUtilisateurById($d->uid);
                        echo '
                <tr>
                    <td>' . $d->date . '</td>
                    <td>' . $u->nom . ' ' . $u->prenom . '</td>
                    <td>' . $d->montant . $devise . '</td>
                </tr>';
                    }
                    echo
                    '</table>';
                    echo '
            <h3>Statistiques</h3>
            <table class="groupsTab">
                <tr>
                    <td>Dépenses totales</td>
                    <td>Créateur du groupe</td>
                </tr>
                <tr>
                    <td>' . $depensesRepository->getTotalByGid($group['gid']) . $devise . '</td>
                    <td>' . $createur->prenom . ' ' . $createur->nom . '</td>
                </tr>
            </table>
            <a href="group.php?gid=' . $group['gid'] . '" class="btnConsult">Consulter</a>
        </article>
            ';
                }
            }
        }
        if (sizeof($myGroupsArray) == 0) {
            echo '
            <h3>Vous n\'êtes dans aucun groupe</h3>
            ';
        }

        ?>
    </section>

    <h1>Invitations à un groupe</h1>
    <section>

        <?php
        foreach ($groupsArray as $group) {
            foreach ($participations as $participation) {
                if ($participation->uid == $_SESSION['uid'] && !$participation->estConfirme) {
                    $inviteGroupsArray[] = $group;
                    $devise = "";
                    if (strcmp($group['devise'], "euro") == 0) {
                        $devise = "€";
                    } else {
                        $devise = "$";
                    }
                    $myGroupsArray[] = $group;
                    $createur = $utilisateurRepository->getUtilisateurById($group['uid']);
                    $depenses = $depensesRepository->getThreeLastDepenseByGid($group['gid']);
                    echo '
            <article class="groupsArticle">
            <h2>' . $group['nom'] . '</h2>
            <h3>Dernières dépenses</h3>
            <table class="groupsTab">
                <tr>
                    <td>Date</td>
                    <td>Auteur</td>
                    <td>Dépense</td>
                </tr>';

                    foreach ($depenses as $d) {
                        $u = $utilisateurRepository->getUtilisateurById($d->uid);
                        echo '
                <tr>
                    <td>' . $d->date . '</td>
                    <td>' . $u->nom . ' ' . $u->prenom . '</td>
                    <td>' . $d->montant . $devise . '</td>
                </tr>';
                    }
                    echo
                    '</table>';
                    echo '
            <h3>Statistiques</h3>
            <table class="groupsTab">
                <tr>
                    <td>Dépenses totales</td>
                    <td>Créateur du groupe</td>
                </tr>
                <tr>
                    <td>' . $depensesRepository->getTotalByGid($group['gid']) . $devise . '</td>
                    <td>' . $createur->prenom . ' ' . $createur->nom . '</td>
                </tr>
            </table>
            <a href="group.php?gid=' . $group['gid'] . '" class="btnConsult">Consulter</a>
            <a href="#" class="btnConsult">Rejoindre</a>
    <ul class="groupLinks">
        <li><a href="#">Supprimer invitation</a></li>
    </ul>
        </article>
            ';
                }
            }
        }

        if (sizeof($inviteGroupsArray) == 0) {
            echo '<h3>Vous n\'êtes invité dans aucun groupe</h3>';
        }
        ?>
    </section>
</main>
</body>
</html>