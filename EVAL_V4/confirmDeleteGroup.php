<?php
session_start();

if(!isset($_GET['gid'])) {
    header('Location: index.php');
}
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}
if(!empty($_POST['securite'])) {
    header('Location: index.php&message=1');
}
require_once 'php/myFct.inc.php';
require_once 'php/db_versement.inc.php';
require_once 'php/db_groupe.inc.php';
use Groupe\GroupeRepository;
use Versement\VersementRepository;

$gid = $_GET['gid'];

$groupeRepository = new GroupeRepository();
$versementRepository = new VersementRepository();
$group = $groupeRepository->showGroupById($gid);
$versements = $versementRepository->getVersementsofGid($gid);
$message = "";
$isSold = !empty($versements);

if($isSold) {
    if(!verifyGroupCanBeDeleted($versements)) {
        $message = "Tous les versements n'ont pas été confirmés";
    }
} else {
    $message = "Le groupe n'a pas été soldé";
}

if(isset($_POST['oui'])) {
    $groupeRepository->deleteGroup($gid, $message);
}
if(isset($_POST['non'])) {
    header('Location: index.php');
}

$titre = 'Connexion';
include("inc/header.inc.php");

?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>Supprimer groupe</h1>

                <?php
                if(empty($message)) {
                    echo '
                <h2>Etes-vous sûr de vouloir supprimer le groupe '. $group->nom  .'?</h2>

                <input class="btn" type="submit" name="oui" value="Oui">
                <input class="btn" type="submit" name="non" value="Non">
                    ';
                } else {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">'.$message .'</h2>
                        <section class="help-connect">
                            <a href="group.php?gid='. $gid .'">Revenir au groupe</a>
                        </section>
                        
                    </section>
                    ';
                }
                ?>
                <label class="securite"><span></span><input type="text" name="securite" value=""/></label>
            </fieldset>

        </form>
    </section>
</main>
</body>
</html>