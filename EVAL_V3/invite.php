<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

if(!isset($_GET['gid'])) {
    header('Location: index.php');
}

require_once 'php/db_utilisateur.inc.php';
require_once 'php/db_participe.inc.php';
use Participer\Participer;
use Participer\ParticiperRepository;
use Utilisateur\UtilisateurRepository;

$participerRepository = new ParticiperRepository();
$utilisateurRepository = new UtilisateurRepository();

$message = "";

if(isset($_POST['submitBtn'])) {
    $participer = new Participer();
    $participer->gid = $_GET['gid'];
    $participer->estConfirme = false;
    $u = $utilisateurRepository->getUtilisateurByData($_POST['inviteCourriel'], $message);

    if($u !== false) {

        if($u->uid == $_SESSION['uid']) {
            $message = "Vous ne pouvez pas vous inviter vous-même";

        } else if(verifierParticipeDeja($participerRepository, $u->uid, $_GET['gid'])) {
            $message = "Ce membre a déjà été invité ou participe déjà au groupe";
        } else {
            $participer->uid = $u->uid;
            $participerRepository->setParticipe($participer);
            $message = "Invitation envoyée";
        }
    } else {
        $message = "Cet utilisateur n'existe pas";
    }
}

function verifierParticipeDeja($participerRepository, $uid, $gid) {
    $participes = $participerRepository->getParticipeByUserId($uid);

    foreach ($participes as $p) {
        if($p->gid == $gid && $p->uid == $uid) {
            return true;
        }
    }

    return false;
}

$titre = 'Inviter';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Inviter un membre
                </h1>
                <h3>
                    Indiquez l'adresse mail du membre à inviter
                </h3>
                <?php
                if(!empty($message)) {
                    echo '
                    <article class="alertbox">
                        <h3 class="connexion-message">'.$message .'</h3>
                    </article>
                    ';
                }
                ?>
                <article class="textbox">
                    <i class="fas fa-user-plus"></i>
                    <label for="username"></label>
                    <input type="text" id="username" placeholder="Adresse email" name="inviteCourriel" required>
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Envoyer invitation">

            </fieldset>
        </form>
    </section>
</main>
</body>
</html>