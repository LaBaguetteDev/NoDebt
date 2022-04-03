<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('Location: index.php');
}

$titre = 'Groupe';
include("inc/header.inc.php");
?>
<main class="groupsMain">
  <h1>Vacances au Costa Rica</h1>
  <section>
    <form method="get" class="searchForm">
      <article class="textbox">
        <label for="expSearch"></label>
        <input type="search" id="expSearch" placeholder="Chercher une dépense">
        <button class="searchBtn">
          <i class="fas fa-search"></i>
        </button>
      </article>
      <article class="searchFormAv">
        <details>
          <summary>Recherche avancée</summary>
          <label for="montMin">Montant minimum</label>
          <input id="montMin" name="montMin" type="number" min="0" step="0.01" placeholder="0€">

          <label for="montMax">Montant maximum</label>
          <input id="montMax" name="montMax" type="number" min="0" step="0.01" placeholder="0€">

          <label for="dateDeb">Date début</label>
          <input id="dateDeb" name="montMax" type="date">

          <label for="dateFin">Date fin</label>
          <input id="dateFin" name="montMax" type="date">
        </details>
      </article>
    </form>
  </section>

  <section>
    <article class="groupsArticle">
      <h3>Participants</h3>
      <table class="groupsTab">
        <tr>
          <td>Participant</td>
          <td>Montant dépensé</td>
        </tr>
        <tr>
          <td>Nom Prénom</td>
          <td>25.00€</td>
        </tr>
        <tr>
          <td>Nom Prénom</td>
          <td>25.00€</td>
        </tr>
        <tr>
          <td>Nom Prénom</td>
          <td>25.00€</td>
        </tr>
        <tr>
          <td>Nom Prénom</td>
          <td>25.00€</td>
        </tr>
      </table>
      <h3>Statistiques</h3>
      <table class="groupsTab">
        <tr>
          <td>Dépenses totales</td>
          <td>Différence à la dépense moyenne</td>
        </tr>
        <tr>
          <td>250.00€</td>
          <td>60.50€</td>
        </tr>
      </table>
      <a href="manageExp.php" class="btnConsult">
        <i class="fas fa-wallet"></i>
        Gérer dépenses
      </a>
      <a href="confirmPay.php" class="btnConsult">
          <i class="fas fa-clipboard-check"></i>
            Confirmer versement
      </a>
      <ul class="groupLinks">
        <li>
          <a href="invite.php">
            <i class="fas fa-user-plus"></i>
            Inviter
          </a>
        </li>
        <li>
          <a href="groupEdit.php">
            <i class="fas fa-pen"></i>
            Modifier
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fas fa-sign-out-alt"></i>
            Quitter
          </a>
        </li>
      </ul>
      <h3>Historique</h3>
      <table class="groupsTab">
        <tr>
          <td>Date</td>
          <td>Auteur</td>
          <td>Dépense</td>
          <td>Editer</td>
        </tr>
        <tr>
          <td>02-02-2022 12:34</td>
          <td>Nom Prénom</td>
          <td>20.50€</td>
          <td>
            <a href="manageExp.php"><i class="fas fa-pen"></i></a>
            <a><i class="fas fa-times"></i></a>
          </td>
        </tr>
        <tr>
          <td>02-02-2022 12:34</td>
          <td>Nom Prénom</td>
          <td>20.50€</td>
          <td>
            <a href="manageExp.php" aria-label="Modifier"><i class="fas fa-pen"></i></a>
            <a><i class="fas fa-times"></i></a>
          </td>
        </tr>
        <tr>
          <td>02-02-2022 12:34</td>
          <td>Nom Prénom</td>
          <td>20.50€</td>
          <td>
            <a href="manageExp.php"><i class="fas fa-pen"></i></a>
            <a><i class="fas fa-times"></i></a>
          </td>
        </tr>
        <tr>
          <td>02-02-2022 12:34</td>
          <td>Nom Prénom</td>
          <td>20.50€</td>
          <td>
            <a href="manageExp.php"><i class="fas fa-pen"></i></a>
            <a><i class="fas fa-times"></i></a>
          </td>
        </tr>
        <tr>
          <td>02-02-2022 12:34</td>
          <td>Nom Prénom</td>
          <td>20.50€</td>
          <td>
            <a href="manageExp.php"><i class="fas fa-pen"></i></a>
            <a><i class="fas fa-times"></i></a>
          </td>
        </tr>
        <tr>
          <td>02-02-2022 12:34</td>
          <td>Nom Prénom</td>
          <td>20.50€</td>
          <td>
            <a href="manageExp.php"><i class="fas fa-pen"></i></a>
            <a><i class="fas fa-times"></i></a>
          </td>
        </tr>
      </table>
    </article>
  </section>
</main>
</body>
</html>