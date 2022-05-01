<?php
session_start();

if(!isset($_GET['fid'])) {
    header('Location: index.php');
}
require_once 'php/db_facture.inc.php';
use Facture\FactureRepository;

$did = $_GET['did'];
$fid = $_GET['fid'];

$factureRepository = new FactureRepository();
$facture = $factureRepository->getFactureByFid($fid);

$message = "";
if(isset($_POST['oui'])) {
    $factureRepository->deleteFacture($fid, $message);
    unlink($facture->scan);
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
                <h1>Supprimer Facture</h1>

                <?php
                if(!empty($message)) {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">'.$message .'</h2>
                        <section class="help-connect">
                            <a href="scan.php?did='. $did .'">Revenir aux factures de la dépense</a>
                        </section>
                    </section>
                    ';
                } else {
                    echo '
                <img src="'. $facture->scan .'" alt="facture">
                <h2>Etes-vous sûr de vouloir supprimer la facture selectionée ?</h2>

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