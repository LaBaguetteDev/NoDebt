<!DOCTYPE html>
<html lang="fr">
<head>
    <title>NoDebt - <?php echo $titre; ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0-2/js/all.min.js"></script>
</head>
<body>
<header>
    <a href="index.php"><img src="img/logo.png" alt="logo" class="logo"></a>
    <label for="nav-toggle"></label><input type="checkbox" id="nav-toggle" class="nav-toggle">
    <nav>
        <ul>
            <?php
            if(isset($_SESSION['uid'])) {
                echo'
                <li><a href="addGroup.php">Créer groupe</a></li>
                <li><a href="myGroups.php">Mes groupes</a></li>
                <li><a href="editProfile.php"><i class="fas fa-user-circle"></i>&nbsp;'. $_SESSION['prenom'] . ' ' . $_SESSION['nom'] .'</a></li>       
                <li><a href="php/logout.php">Déconnexion</a></li>
                ';
            }
            ?>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</header>