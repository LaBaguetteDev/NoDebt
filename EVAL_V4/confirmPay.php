<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

$titre = 'Confirmer versement';
include("inc/header.inc.php");
?>
<main class="groupsMain">
    <section>
        <article class="groupsSection">
            <h1>Confirmer versements</h1>
            <table class="groupsTab">
                <tr>
                    <td>Date</td>
                    <td>Auteur</td>
                    <td>Dépense</td>
                    <td>Confirmer</td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                    <td>
                        <a><i class="fas fa-check"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                    <td>
                        <a><i class="fas fa-check"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                    <td>
                        <a><i class="fas fa-check"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                    <td>
                        <a><i class="fas fa-check"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                    <td>
                        <a><i class="fas fa-check"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>02-02-2022 12:34</td>
                    <td>Nom Prénom</td>
                    <td>20.50€</td>
                    <td>
                        <a><i class="fas fa-check"></i></a>
                    </td>
                </tr>
            </table>
        </article>
    </section>
</main>
</body>
</html>