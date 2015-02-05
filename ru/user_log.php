<?php
session_start();
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
    <title>Вход | BookShare</title>
</head>
<body>

<?php

include("connect.inc.php");

try {
    // connect to the database
    $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // define variables and set to empty values
    $login = $password = "";
    $loginErr = $passwordErr = "";

    // checks fields
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["login"])) {
            $loginErr = "Введите Ваш логин";
        } else {
            $login = $_POST["login"];
            $sqlCheckUser = $conn->query("SELECT COUNT(login) FROM user WHERE login='$login'");
            if ($sqlCheckUser->fetchColumn() == 0) {
                $loginErr = "Пользователь с таким логином не существует";
                $login = "";
            }
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Введите Ваш пароль";
        } else {
            $password = $_POST["password"];
            $sqlCheckPass = $conn->query("SELECT password FROM user WHERE login='$login'");
            if ($sqlCheckPass->fetchColumn() != $password) {
                $passwordErr = "Неправильный пароль";
                $password = "";
            }
        }

    }

    // if everything ok, logging in
    if (!empty($login) && !empty($password)) {
        $_SESSION["user"] = $login;
        header("Location: user.php");
    }


} catch (PDOException $e) {
    echo "<p>Erreur: " . $e->getMessage() . "</p>\n";
}

include("header.php");

?>

<div class="wrapper row2">
    <div id="container" class="clear">
        <h2 class="headline">Пожалуйста, введите Ваши данные</h2>

        <form method="post" enctype="multipart/form-data"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="border-form">
                <div class="form" id="filtcent2">
                    <h1 class="big">Логин:</h1>

                    <div></div>
                    <input type="text" name="login" value="<?php echo $login; ?>">
                    <span class="error"> <?php echo $loginErr; ?></span>

                    <div></div>

                    <h1 class="big">Пароль:</h1>

                    <div></div>
                    <input type="password" name="password">
                    <span class="error"> <?php echo $passwordErr; ?></span>

                    <div></div>
                    <br><br>
                    <input type="submit" name="submit" value="Войти" id="button1">
                </div>
            </div>

        </form>
    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>