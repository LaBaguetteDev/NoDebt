<?php

require_once 'php/db_groupe.inc.php';
require_once 'php/db_utilisateur.inc.php';

use Groupe\GroupeRepository;
use Utilisateur\UtilisateurRepository;

session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}
$message = "";
$groupRepository = new GroupeRepository();
$utilisateurRepository = new UtilisateurRepository();
$groups = $groupRepository->showGroups();

$createur = $utilisateurRepository->getUtilisateurById($_SESSION['uid'], $message);


$titre = 'Mes groupes';
include("inc/header.inc.php");
?>
<main class="groupsMain">
    <h1>Mes groupes</h1>
    <section>
        <?php

        if($groups->rowCount() == 0) {
            echo '
            <h3>Vous n\'êtes dans aucun groupe</h3>
            ';
        }

        while($group = $groups->fetch(PDO::FETCH_ASSOC)) {
            echo '
            <article class="groupsArticle">
            <h2>'. $group['nom'] .'</h2>
            <h3>Dernières dépenses</h3>
            <table class="groupsTab">
                <tr>
                    <td>Date</td>
                    <td>Auteur</td>
                    <td>Dépense</td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                </tr>
            </table>

            <h3>Statistiques</h3>
            <table class="groupsTab">
                <tr>
                    <td>Dépenses totales</td>
                    <td>Créateur du groupe</td>
                </tr>
                <tr>
                    <td>250.00€</td>
                    <td>'. $createur->prenom . ' ' . $createur->nom .'</td>
                </tr>
            </table>
            <a href="group.php?gid='. $group['gid'] .'" class="btnConsult">Consulter</a>
        </article>
            ';
        }
        ?>
    </section>

    <h1>Invitations à un groupe</h1>
    <section>
        <article class="groupsArticle">
            <h2>Week-end gîte ardennes</h2>
            <h3>Membres dans le groupe</h3>
            <table class="groupsTab">
                <tr>
                    <td>Nom prénom</td>
                </tr>
                <tr>
                    <td>Nom Prénom</td>
                </tr>
            </table>

            <h3>Invitation de Nom Prénom</h3>
            <a href="#" class="btnConsult">Rejoindre</a>
            <ul class="groupLinks">
                <li><a href="#">Supprimer invitation</a></li>
            </ul>
        </article>
    </section>
</main>
</body>
</html>