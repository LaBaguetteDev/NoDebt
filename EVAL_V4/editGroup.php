<?php
session_start();
if (!isset($_SESSION['uid']) || !isset($_GET['gid'])) {
    header('Location: index.php');
}

require_once 'php/db_groupe.inc.php';

use Groupe\Groupe;
use Groupe\GroupeRepository;

$gid = $_GET['gid'];

$groupeRepository = new GroupeRepository();
$group = $groupeRepository->showGroupById($gid);

$message = "";
if (isset($_POST['submitBtn'])) {
    if (empty($_POST['groupName']) || empty($_POST['devise'])) {
        $message = "Un champ est manquant";
    } else if (strcmp($_POST['devise'], 'euro') !== 0 && strcmp($_POST['devise'], 'dollar') !== 0) {
        $message = "Devise invalide";
    } else {
        $groupe = new Groupe();
        $groupe->nom = htmlentities($_POST['groupName']);
        $groupe->devise = htmlentities($_POST['devise']);

        $groupeRepository->updateGroup($gid, $groupe, $message);
    }
}

$titre = 'Editer Groupe';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>
                    Edition groupe
                </h1>
                <?php
                if (!empty($message)) {
                    echo '
                    <section class="alertbox">
                        <h2 class="connexion-message">' . $message . '</h2>
                    </section>
                    ';
                }
                ?>
                <section class="textbox">
                    <i class="fas fa-users"></i>
                    <label for="username">Nom du groupe</label>
                    <input name="groupName" type="text" id="username" placeholder="Nom" required
                           value="<?php echo $group->nom ?>">
                </section>
                <section class="textbox">
                    <i class="fas fa-money-bill-wave"></i>
                    <label for="particiDep">Devise</label>
                    <select id="particiDep" name="devise" required>
                        <option value="" disabled hidden>Choisissez une devise</option>
                        <option value="euro" <?php if (strcmp($group->devise, 'euro') == 0) echo 'selected' ?>>Euro €
                        </option>
                        <option value="dollar" <?php if (strcmp($group->devise, 'dollar') == 0) echo 'selected' ?>>
                            Dollar $
                        </option>
                    </select>
                </section>

                <input class="btn" type="submit" name="submitBtn" value="Confirmer">
            </fieldset>
        </form>
    </section>
</main>
</body>
</html>