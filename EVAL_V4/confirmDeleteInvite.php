<?php
session_start();

if(!isset($_GET['gid'])) {
    header('Location: index.php');
}
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}


require_once 'php/db_groupe.inc.php';
require_once 'php/db_participe.inc.php';
use Groupe\GroupeRepository;
use Participer\ParticiperRepository;


$gid = $_GET['gid'];
$groupeRepository = new GroupeRepository();
$g = $groupeRepository->showGroupById($gid);
$participerRepository = new ParticiperRepository();


$message = "";
if(isset($_POST['oui'])) {
    $participerRepository->supprimerInvite($_SESSION['uid'], $gid, $message);
} else if(isset($_POST['non'])) {
    header('Location: index.php');
}

$titre = 'Refuser invitation';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>Refuser invitation</h1>

                <?php
                if(!empty($message)) {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">'.$message .'</h2>
                        <section class="help-connect">
                            <a href="myGroups.php">Revenir à l\'acceuil</a>
                        </section>
                        
                    </section>
                    ';
                } else {
                    echo '
                <h2>Etes-vous sûr de vouloir supprimer l\'invitation au groupe '. $g->nom .' ?</h2>

                <input class="btn" type="submit" name="oui" value="Oui">
                <input class="btn" type="submit" name="non" value="Non">
                    ';
                }
                ?>
            </fieldset>

        </form>
    </section>
</main>
</body>
</html>