<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
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
                <article class="textbox">
                    <i class="fas fa-calendar-day"></i>
                    <label for="dateDep">Date</label>
                    <input type="date" id="dateDep" value=<?php echo $minDate?> min="<?php echo $minDate?>" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-coins"></i>
                    <label for="montantDep">Montant</label>
                    <input type="number" min="0" step="0.01" id="montantDep" placeholder="Montant" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-signature"></i>
                    <label for="libelleDep">Libellé</label>
                    <input id="libelleDep" placeholder="Libellé" required>
                </article>
                <article class="textbox">
                    <i class="fas fa-tags"></i>
                    <label for="tagsDep">Tags</label>
                    <input id="tagsDep" placeholder="Tags">
                </article>
                <article class="textbox">
                    <i class="fas fa-user-tag"></i>
                    <label for="particiDep">Participant</label>
                    <select id="particiDep" name="particiDep" required>
                        <option selected value="Nom Prenom">Detiffe Florian</option>
                        <option value="Nom Prenom">Nom Prénom</option>
                    </select>
                </article>
                <article class="textbox">
                    <i class="fas fa-file-alt"></i>
                    <label for="tagsDep">Ajouter un scan</label>
                    <input class="fileScan" type="file" name="scan" accept="image/*, .jpg, .png, .pdf">
                </article>

                <input class="btn" type="submit" name="submitBtn" value="Confirmer">
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>