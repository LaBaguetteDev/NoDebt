<?php
session_start();

if(!isset($_GET['did'])) {
    header('Location: index.php');
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
                    <article class="alertbox">
                        <h3 class="connexion-message">'.$message .'</h3>
                        <article class="help-connect">
                            <a href="group.php?gid='. $gid .'">Revenir au groupe</a>
                        </article>
                        
                    </article>
                    ';
                } else {
                    echo '
                <h2>Etes-vous sûr de vouloir supprimer la dépense '. $depense->libelle .' ?</h2>

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