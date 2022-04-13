<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

if (!isset($_GET['gid'])) {
    header('Location: index.php');
}

require_once 'php/db_utilisateur.inc.php';
require_once 'php/db_depense.inc.php';

use Utilisateur\UtilisateurRepository;
use Depense\Depense;
use Depense\DepenseRepository;

$gid = $_GET['gid'];
$utilisateurRepository = new UtilisateurRepository();
$utilisateur = $utilisateurRepository->getUtilisateurById($_SESSION['uid']);
$utilisateurs = $utilisateurRepository->getUtilisateursFromGid($gid);

$depenseRepository = new DepenseRepository();
$message = "";

$d = null;
if (isset($_GET['did'])) {
    $d = $depenseRepository->getDepenseById($_GET['did']);
}

if (isset($_POST['submitBtn'])) {

    if(!isset($_POST['date']) || !isset($_POST['montant']) || !isset($_POST['tag']) || !isset($_POST['libelle']) || !isset($_POST['uid'])) {
        $message = "Un champ est manquant";
    } else if(!is_numeric($_POST['montant'])) {
        $message = "Montant invalide";
    } else {
        $depense = new Depense();
        $depense->date = htmlentities($_POST['date']);
        $depense->montant = intval(htmlentities($_POST['montant']));
        $depense->tag = htmlentities($_POST['tag']);
        $depense->libelle = htmlentities($_POST['libelle']);
        $depense->gid = $gid;
        $depense->uid = $_POST['uid'];

        if(is_null($d)) {
            $depenseRepository->storeDepense($depense, $message);
        } else {
            $depense->did = $_GET['did'];
            $depenseRepository->updateDepense($depense, $message);
        }
    }
}

$titre = 'Gerer dépense';
$minDate = date("Y-m-d");
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Gérer dépense
                </h1>
                <article>
                    <?php
                    if (!empty($message)) {
                        echo '
                    <article class="alertbox">
                        <h3 class="connexion-message">' . $message . '</h3>
                    </article>
                    <article class="help-connect">
                            <a href="group.php?gid='. $gid .'">Revenir au groupe</a>
                    </article>
                    ';
                    }
                    ?>
                </article>
                <article class="textbox">
                    <i class="fas fa-calendar-day"></i>
                    <label for="dateDep">Date</label>
                    <input name="date" type="date" id="dateDep"
                           value=<?php if (is_null($d)) echo $minDate; else echo $d->date; ?> min="<?php echo $minDate ?>"
                           required>
                </article>
                <article class="textbox">
                    <i class="fas fa-coins"></i>
                    <label for="montantDep">Montant</label>
                    <input name="montant" type="number" min="0" step="0.01" id="montantDep" placeholder="Montant"
                           required value="<?php if (!is_null($d)) echo $d->montant ?>">
                </article>
                <article class="textbox">
                    <i class="fas fa-signature"></i>
                    <label for="libelleDep">Libellé</label>
                    <input name="libelle" id="libelleDep" placeholder="Libellé" required
                           value="<?php if (!is_null($d)) echo $d->libelle ?>">
                </article>
                <article class="textbox">
                    <i class="fas fa-tags"></i>
                    <label for="tagsDep">Tags</label>
                    <input name="tag" id="tagsDep" placeholder="Tags" value="<?php if (!is_null($d)) echo $d->tag ?>">
                </article>
                <article class="textbox">
                    <i class="fas fa-user-tag"></i>
                    <label for="particiDep">Participant</label>
                    <select id="particiDep" name="uid" required>
                        <?php
                        if (is_null($d)) {
                            echo '<option selected
                                value="'. $utilisateur->uid .'">'. $utilisateur->nom . ' ' . $utilisateur->prenom .'</option>';
                        } else {
                            $utilisateurDepense = $utilisateurRepository->getUtilisateurById($d->uid);
                            echo '<option selected
                                value="'. $utilisateurDepense->uid .'">'. $utilisateurDepense->nom . ' ' . $utilisateurDepense->prenom .'</option>';
                        }
                        foreach ($utilisateurs as $u) {
                            if ($u->uid !== $_SESSION['uid']) {
                                echo '<option value="' . $u->uid . '">' . $u->nom . ' ' . $u->prenom . '</option>';
                            }
                        }
                        ?>
                    </select>
                </article>
                <!--
                <article class="textbox">
                    <i class="fas fa-file-alt"></i>
                    <label for="tagsDep">Ajouter un scan</label>
                    <input class="fileScan" type="file" name="scan" accept="image/*, .jpg, .png, .pdf">
                </article>
                -->

                <input class="btn" type="submit" name="submitBtn" value="Confirmer">
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>