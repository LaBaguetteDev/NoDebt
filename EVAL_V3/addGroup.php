<?php

require_once 'php/db_groupe.inc.php';
require_once 'php/db_participe.inc.php';

use Groupe\Groupe;
use Groupe\GroupeRepository;
use Participer\Participer;
use Participer\ParticiperRepository;

session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}
$message = "";
$nom = '';
$devise = '';
$uid = $_SESSION['uid'];
$groupeRepository = new GroupeRepository();
$participerRepository = new ParticiperRepository();

if(isset($_POST['submitBtn'])) {
    $groupe = new Groupe();
    $groupe->nom = htmlentities($_POST['groupName']);
    $groupe->devise = htmlentities($_POST['devise']);
    $groupe->createur = $uid;

    $groupeRepository->storeGroupe($groupe, $message);

    $participer = new Participer();
    $participer->gid = $groupe->gid;
    $participer->uid = $uid;
    $participer->estConfirme = true;

    $participerRepository->setParticipe($participer);
}

$titre = 'Créer Groupe';
include("inc/header.inc.php");

?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Créer groupe
                </h1>
                <?php
                if(!empty($message)) {
                    echo '
                    <article class="alertbox">
                        <h3 class="connexion-message">'.$message.'</h3>
                    </article>
                    ';
                }
                ?>
                <article class="textbox">
                    <i class="fas fa-users"></i>
                    <label for="username">Nom du groupe</label>
                    <input name="groupName" type="text" id="username" placeholder="Nom" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-money-bill-wave"></i>
                    <label for="particiDep">Devise</label>
                    <select id="particiDep" name="devise" required>
                        <option value="" disabled selected hidden>Choisissez une devise</option>
                        <option value="euro">Euro €</option>
                        <option value="dollar">Dollar $</option>
                    </select>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Confirmer">
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>