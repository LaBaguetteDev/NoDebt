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
    <a href="#"><img src="img/logo.png" alt="logo" class="logo"></a>
    <label for="nav-toggle"></label><input type="checkbox" id="nav-toggle" class="nav-toggle">
    <nav>
        <ul>
            <?php
            $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
            if($curPageName != 'index.php' && $curPageName != 'forgotPass.php' && $curPageName != 'register.php') {
                echo'
                <li><a href="addGroup.php">Créer groupe</a></li>
                <li><a href="myGroups.php">Mes groupes</a></li>
                <li><a href="editProfile.php"><i class="fas fa-user-circle"></i>&nbsp;Florian</a></li>       
                <li><a href="#">Déconnexion</a></li>
                ';
            }
            ?>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</header>