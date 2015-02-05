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
    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <meta charset="utf-8"/>
    <meta name="Author" content="Vsevolod Alokhin, Alina Ten"/>
    <meta name="Keywords" content="book, livre, share, partager, bookcrossing"/>
    <meta name="Description" content="Ищите и делитесь книгами бесплатно"/>
    <title>Ваш профиль | BookShare</title>
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

        <h2 class="headline">Профиль</h2>
        <div class="line"></div>

        <div class="fl_left">
            <div class="max200">
                <a href="<?php echo $rowUser->photo ?>" target="_blank"><img src="<?php echo $rowUser->photo ?>" alt=""/></a>
            </div>

            <?php if ($login == $user) { ?>
            <div>
                <p id="edit"><a href="user_change.php?login=<?php echo $login ?>">Изменить Ваш профиль</a></p>
                <p class="center"><a href="book_add.php">Добавить книгу</a></p>
                <p class="center"><a href="my_books.php">Мои книги</a></p>
            </div>
            <?php } else { ?>
                <div>
                    <p class="center"><a href="my_books.php?user=<?php echo $user ?>">Книги</a></p>
                </div>
            <?php } ?>
        </div>

        <div class="fl_left width">
            <p><strong>Логин:</strong> <?php echo $user ?></p>
            <p><strong>Email:</strong> <?php echo $rowUser->email ?></p>
            <?php if ($rowUser->name != "NULL") { ?> <p><strong>Имя:</strong> <?php echo $rowUser->name . "</p>"; } ?>
            <?php if ($rowUser->last_name != "NULL") { ?> <p><strong>Фамилия:</strong> <?php echo $rowUser->last_name . "</p>"; } ?>
            <p><strong>Пол:</strong> <?php echo $rowUser->gender ?></p>
            <p><strong>Страна:</strong> <?php echo $rowUser->country ?></p>
            <p><strong>Город:</strong> <?php echo $rowUser->city ?></p>
            <?php if ($rowUser->telephone != "NULL") { ?> <p><strong>Телефон:</strong> <?php echo $rowUser->telephone . "</p>"; } ?>
            <?php if ($rowUser->facebook != "NULL") { ?> <p><strong>Facebook:</strong> <?php echo $rowUser->facebook . "</p>"; } ?>
            <?php if ($rowUser->about != "NULL") { ?> <p><strong>Обо мне:</strong> <?php echo $rowUser->about . "</p>"; } ?>
        </div>

    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>