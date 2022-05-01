<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

require_once 'php/db_depense.inc.php';
require_once 'php/db_facture.inc.php';

use Depense\DepenseRepository;
use Facture\Facture;
use Facture\FactureRepository;


$depenseRepository = new DepenseRepository();
$factureRepository = new FactureRepository();
$message = "";

$d = null;
if (isset($_GET['did'])) {
    $d = $depenseRepository->getDepenseById($_GET['did']);
}

$factures = $factureRepository->getFacturesByDid($d->did);

if(isset($_POST['submitBtn'])) {
    switch ($_FILES['scan']['error']) {
        case UPLOAD_ERR_NO_FILE :
            $message .= 'Pas de fichier spécifié';
            break;
        case UPLOAD_ERR_INI_SIZE:
            $message .= 'Taille fichier dépassant la limite ';
            break;
        case UPLOAD_ERR_PARTIAL:
            $message .= 'Fichier partiellement téléchargé';
            break;
    }
    $allowed = array('png', 'jpg', 'pdf');
    $ext = pathinfo($_FILES['scan']['name'], PATHINFO_EXTENSION);
    if(!in_array($ext, $allowed)) {
        return 'Fichier non valide';
    }
    $nameFile = pathinfo($_FILES['scan']['name'])['basename'];

    if(move_uploaded_file($_FILES['scan']['tmp_name'], "uploads/$nameFile")) {
        $message = "Fichier envoyé. ";
        $facture = new Facture();
        $facture->scan = "uploads/$nameFile";
        $facture->did = $d->did;
        $factureRepository->storeFacture($facture, $message);
        $factures = $factureRepository->getFacturesByDid($d->did);
    } else {
        $message = "Fichier non envoyé";
    }
}

$titre = 'Factures';
include("inc/header.inc.php");
?>
<main class="groupsMain">

    <h1>Facture(s) de la dépense <?php echo $d->libelle ?> </h1>
    <?php
    if (!empty($message)) {
        echo '
        <section class="alertbox">
            <h3 class="connexion-message">' . $message . '</h3>
        </section>
        ';
    } ?>
    <form class="form-scan" method="POST" enctype="multipart/form-data">
        <fieldset class="fieldset-scan">
            <section class="textbox">
                <i class="fas fa-file-alt"></i>
                <label for="tagsDep">&nbsp;Ajouter une facture</label>
                <input class="fileScan" type="file" name="scan" accept=".jpg, .png, .pdf">
            </section>
            <input class="btn" type="submit" name="submitBtn" value="Envoyer">
        </fieldset>
    </form>

    <article class="groupsSection">
        <?php
        foreach ($factures as $f) {
            echo '
                <img src="'. $f->scan .'" alt="Facture">
                <a href="confirmDeleteScan.php?fid= '. $f->fid .'&did= '. $d->did .'" class="btnConsult"><i class="fas fa-times"></i> Supprimer </a>
            ';
        }

        if(sizeof($factures) == 0) {
            echo '
            <h3>Il n\'y a aucune facture pour cette dépense </h3 >
            ';
        }
        ?>
    </article>
</main>
</body >
</html >