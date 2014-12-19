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
    <title>Livres d'utilisateur | BookShare</title>
</head>
<body>

<?php

include("connect.inc.php");

try {
    // connect to the database
    $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // select my books or books of a user from URL
    $sqlSelectMyBooks = "SELECT id, name, author, language, genre, review, photo FROM book WHERE user='$user'";
    $stmt = $conn->prepare($sqlSelectMyBooks);
    $stmt->execute();
    $total = $stmt->rowCount();

} catch (PDOException $e) {
    echo "<p>Erreur: " . $e->getMessage() . "</p>\n";
}

include("header.php");

?>

<div class="wrapper row2">
    <div id="container" class="clear">

        <h2 class="headline">
            <?php if ($login == $user) {
                echo "Vous avez";
            } else {
                echo $user . " a";
            } ?> ajouté(e) <?php echo $total ?> livre(s)</h2>
        <h3 class="headline"><a href="book_add.php">Ajouter un livre</a></h3>

        <?php while ($row = $stmt->fetchObject()) { ?>
        <div class="border-form">
            <div class="clear">
                <div class="fl_left1">
                    <div class="max200">
                        <a href="<?php echo $row->photo ?>" target="_blank"><img src="<?php echo $row->photo ?>" alt=""/></a>
                    </div>
                    <?php if ($login == $user) { ?>
                        <p class="center"><a href="book_change.php?id=<?php echo $row->id ?>">Modifier ce livre</a><br></p>
                        <p class="center"><a href="book_delete.php?id=<?php echo $row->id ?>">Supprimer ce livre</a><br></p>
                    <?php } ?>
                </div>
                <div class="fl_left1 width">
                    <p><strong>Nom:</strong> <?php echo $row->name ?></p>
                    <p><strong>Auteur:</strong> <?php echo $row->author ?></p>
                    <p><strong>Langue:</strong> <?php echo $row->language ?></p>
                    <?php if ($row->genre != "NULL") { ?> <p><strong>Genre:</strong> <?php echo $row->genre . "</p>"; } ?>
                    <?php if ($row->review != "NULL") { ?> <p><strong>Avis:</strong> <?php echo $row->review . "</p>"; } ?>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>