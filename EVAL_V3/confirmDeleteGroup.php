<?php
session_start();

if(!isset($_GET['gid'])) {
    header('Location: index.php');
}
require_once 'php/db_groupe.inc.php';
use Groupe\GroupeRepository;

$gid = $_GET['gid'];

$groupeRepository = new GroupeRepository();
$group = $groupeRepository->showGroupById($gid);

$titre = 'Connexion';
include("inc/header.inc.php");
?>
<main>
    <section>
        <form method="post">
            <fieldset class="fieldset-box">
                <h1>Supprimer groupe</h1>

                <h2>Etes-vous sûr de vouloir supprimer le groupe <?php echo $group->nom ?> ?</h2>

                <input class="btn" type="submit" name="oui" value="Oui">
                <input class="btn" type="submit" name="non" value="Non">
            </fieldset>

        </form>
    </section>
</main>
</body>
</html>