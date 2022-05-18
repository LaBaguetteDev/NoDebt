<?php
session_start();

if(!isset($_GET['did'])) {
    header('Location: index.php');
}
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}
if(!empty($_POST['securite'])) {
    header('Location: index.php&message=1');
}
require_once 'php/db_depense.inc.php';
use Depense\DepenseRepository;

$did = $_GET['did'];
$gid = $_GET['gid'];

$depenseRepository = new DepenseRepository();
$depense = $depenseRepository->getDepenseById($did);

$message = "";
if(isset($_POST['oui'])) {
    $depenseRepository->deleteDepense($did, $message);
} else if(isset($_POST['non'])) {
    header('Location: index.php');
}

$titre = 'Connexion';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>Supprimer Dépense</h1>

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
                <h2>Etes-vous sûr de vouloir supprimer la dépense '. $depense->libelle .' ?</h2>

                <input class="btn" type="submit" name="oui" value="Oui">
                <input class="btn" type="submit" name="non" value="Non">
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