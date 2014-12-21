<div class="wrapper row1">
    <header id="header" class="clear">
        <div id="hgroup">
            <h1><a href="index.php"><strong id="logo">Book</strong>Share</a></h1>
            <h2>Chercher. Partager. Se communiquer</h2>
        </div>
        <nav>
            <ul>
                <li><a href="books.php">Livres</a></li>
                <li><a href="users.php">Utilisateurs</a></li>
                <li><a href="about.php">À propos de nous</a></li>
                <?php if (isset($_SESSION["user"])) { ?>
                    <li><a href="user.php" class="buttonspecial"><?php echo $_SESSION["user"] ?></a></li>
                    <li class="last"><a href="logout.php" class="buttonspecial">Se déconnecter</a></li>
                <?php } else { ?>
                    <li><a href="user_log.php" class="buttonspecial">Se connecter</a></li>
                    <li class="last"><a href="user_reg.php" class="buttonspecial">S'inscrire</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
</div>