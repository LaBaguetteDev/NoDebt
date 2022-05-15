<?php
if (!isset($_GET['gid'])) {
    header('Location: index.php');
}

require_once 'php/db_versement.inc.php';
require_once 'php/db_groupe.inc.php';
use Groupe\GroupeRepository;
use Versement\VersementRepository;

$gid = $_GET['gid'];
$groupeRepository = new GroupeRepository();
$versementRepository = new VersementRepository();

$group = $groupeRepository->showGroupById($gid);

$message = "";
if(isset($_POST['oui'])) {
    $versementRepository->deleteVersementsOfGid($gid, $message);
} else if(isset($_POST['non'])) {
    header('Location: index.php');
}

$titre = "Annuler solde";
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>Annuler solde du groupe</h1>

                <?php
                if(!empty($message)) {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">'.$message .'</h2>
                        <section class="help-connect">
                            <a href="group.php?gid='. $gid .'">Revenir au groupe</a>
                        </section>
                        
                    </section>
                    ';
                } else {
                    echo '
                <h2>Etes-vous sûr de vouloir annuler le solde du groupe '. $group->nom .' ?</h2>

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