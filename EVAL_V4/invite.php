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
require_once 'php/db_groupe.inc.php';
require_once 'php/myFct.inc.php';
use Participer\Participer;
use Participer\ParticiperRepository;
use Utilisateur\UtilisateurRepository;
use Groupe\GroupeRepository;

$participerRepository = new ParticiperRepository();
$utilisateurRepository = new UtilisateurRepository();
$groupeRepository = new GroupeRepository();
$message = "";

$g = $groupeRepository->showGroupById($_GET['gid']);

if(isset($_POST['submitBtn'])) {
    $participer = new Participer();
    $participer->gid = $_GET['gid'];
    $participer->estConfirme = 0;
    $u = $utilisateurRepository->getUtilisateurByData(htmlentities($_POST['inviteCourriel']), $message);

    if($u !== false) {

        if($u->uid == $_SESSION['uid']) {
            $message = "Vous ne pouvez pas vous inviter vous-même";

        } else if(verifierParticipeDeja($participerRepository, $u->uid, $_GET['gid'])) {
            $message = "Ce membre a déjà été invité ou participe déjà au groupe";
        } else {
            $participer->uid = $u->uid;
            $error = $participerRepository->setParticipe($participer, $message);
            $uInviteur = $utilisateurRepository->getUtilisateurById($_SESSION['uid']);
            envoiMailInvitation($u->courriel, $uInviteur->courriel, $g->nom, $message);
        }
    } else {
        $message = "Cet utilisateur n'existe pas";
    }
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
                <h2>
                    Indiquez l'adresse mail du membre à inviter dans <?php echo $g->nom ?>
                </h2>
                <?php
                if(!empty($message)) {
                    echo '
                    <section class="alertbox">
                        <h3 class="connexion-message">'.$message .'</h3>
                    </section>
                    ';
                }
                ?>
                <section class="textbox">
                    <i class="fas fa-user-plus"></i>
                    <label for="username"></label>
                    <input type="text" id="username" placeholder="Adresse email" name="inviteCourriel" required>
                </section>

                <input class="btn" type="submit" name="submitBtn" value="Envoyer invitation">

            </fieldset>
        </form>
    </section>
</main>
</body>
</html>