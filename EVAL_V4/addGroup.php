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

    if(empty($_POST['groupName']) || empty($_POST['devise'])) {
        $message = "Un champ est manquant";
    } else if(strcmp($_POST['devise'], 'euro') !== 0 || strcmp($_POST['devise'], 'dollar') !== 0) {
        $message = "Devise invalide";
    } else {
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
                    <section class="alertbox">
                        <h2 class="connexion-message">'.$message.'</h2>
                    </section>
                    ';
                }
                ?>
                <section class="textbox">
                    <i class="fas fa-users"></i>
                    <label for="username">Nom du groupe</label>
                    <input name="groupName" type="text" id="username" placeholder="Nom" required value="<?php if (isset($_POST['groupName'])) echo $_POST['groupName'] ?>">
                </section>
                <section class="textbox">
                    <i class="fas fa-money-bill-wave"></i>
                    <label for="particiDep">Devise</label>
                    <select id="particiDep" name="devise" required>
                        <option selected value="euro">Euro €</option>
                        <option value="dollar">Dollar $</option>
                    </select>
                </section>

                <input class="btn" type="submit" name="submitBtn" value="Confirmer">
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>