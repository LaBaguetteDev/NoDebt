<?php
session_start();

if(!isset($_GET['uid'])) {
    header('Location: index.php');
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
$utilisateurRepository->setActif($_GET['uid']);

$titre = 'Réactiver compte';
include("inc/header.inc.php");

?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>Réactiver compte</h1>
                    <section class="alertbox">
                        <h2 class="connexion-message">Votre compte a bien été réactivé, veuillez vous reconnecter</h2>
                        <section class="help-connect">
                            <a href="index.php">Se connecter</a>
                        </section>
                    </section>
            </fieldset>

        </form>
    </section>
</main>
</body>
</html>