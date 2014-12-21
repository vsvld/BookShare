<?php
session_start();

$login = $_SESSION["user"];
$user = "";

// the page is accessible only for logged user or/and with username from URL
if (!isset($login) && !isset($_GET["user"])) {
    header("Location: user_log.php");
} else {
    $user = isset($_GET["user"]) ? $_GET["user"] : $login;
}

?>

<!DOCTYPE html>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="stylesheet.css"/>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <meta charset="utf-8"/>
    <meta name="Author" content="Vsevolod Alokhin, Alina Ten"/>
    <meta name="Keywords" content="book, livre, share, partager, bookcrossing"/>
    <meta name="Description" content="Cherchez et partagez livres gratuit"/>
    <title>Votre profil | BookShare</title>
</head>
<body>

<?php

include("connect.inc.php");

try {
    // connect to the database
    $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // select user
    $sqlSelectUser = $conn->query("SELECT * FROM user WHERE login='$user'");
    $rowUser = $sqlSelectUser->fetchObject();

} catch (PDOException $e) {
    echo "<p>Erreur: " . $e->getMessage() . "</p>\n";
}

include("header.php");

?>

<div class="wrapper row2">
    <div id="container" class="clear">

        <h2 class="headline">Profil</h2>
        <div class="line"></div>

        <div class="fl_left">
            <div class="max200">
                <a href="<?php echo $rowUser->photo ?>" target="_blank"><img src="<?php echo $rowUser->photo ?>" alt=""/></a>
            </div>

            <?php if ($login == $user) { ?>
            <div>
                <p id="edit"><a href="user_change.php?login=<?php echo $login ?>">Modifier votre profil</a></p>
                <p class="center"><a href="book_add.php">Ajouter un livre</a></p>
                <p class="center"><a href="my_books.php">Mes livres</a></p>
            </div>
            <?php } else { ?>
                <div>
                    <p class="center"><a href="my_books.php?user=<?php echo $user ?>">Livres</a></p>
                </div>
            <?php } ?>
        </div>

        <div class="fl_left width">
            <p><strong>Login:</strong> <?php echo $user ?></p>
            <p><strong>Email:</strong> <?php echo $rowUser->email ?></p>
            <?php if ($rowUser->name != "NULL") { ?> <p><strong>Prénom:</strong> <?php echo $rowUser->name . "</p>"; } ?>
            <?php if ($rowUser->last_name != "NULL") { ?> <p><strong>Nom:</strong> <?php echo $rowUser->last_name . "</p>"; } ?>
            <p><strong>Sexe:</strong> <?php echo $rowUser->gender ?></p>
            <p><strong>Pays:</strong> <?php echo $rowUser->country ?></p>
            <p><strong>Ville:</strong> <?php echo $rowUser->city ?></p>
            <?php if ($rowUser->telephone != "NULL") { ?> <p><strong>Téléphone:</strong> <?php echo $rowUser->telephone . "</p>"; } ?>
            <?php if ($rowUser->facebook != "NULL") { ?> <p><strong>Facebook:</strong> <?php echo $rowUser->facebook . "</p>"; } ?>
            <?php if ($rowUser->about != "NULL") { ?> <p><strong>De moi:</strong> <?php echo $rowUser->about . "</p>"; } ?>
        </div>

    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>