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
    <title>Пользователи | BookShare</title>
</head>
<body>

<?php

include("connect.inc.php");

try {
    // connect to the database
    $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // helping function
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $login = $name = $last_name = $gender = $country = $city = "";
    $conditions = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (!empty($_POST["login"])) {
            $login = test_input($_POST["login"]);
            $conditions[] = "u.login LIKE '%$login%'";
        }

        if (!empty($_POST["name"])) {
            $name = test_input($_POST["name"]);
            $conditions[] = "u.name LIKE '%$name%'";
        }

        if (!empty($_POST["last_name"])) {
            $last_name = test_input($_POST["last_name"]);
            $conditions[] = "u.last_name LIKE '%$last_name%'";
        }

        if (!empty($_POST["gender"])) {
            $gender = $_POST["gender"];
            $conditions[] = "u.gender='$gender'";
        }

        if (!empty($_POST["country"])) {
            $country = test_input($_POST["country"]);
            $conditions[] = "u.country LIKE '%$country%'";
        }

        if (!empty($_POST["city"])) {
            $city = test_input($_POST["city"]);
            $conditions[] = "u.city LIKE '%$city%'";
        }
    }

    // query for selecting all users with their details plus counting their books
    $sqlSelectUsers = "
        SELECT u.login, u.name, u.last_name, u.gender, u.country, u.city, u.photo, IFNULL(b.count, 0) AS count
        FROM user AS u
        LEFT JOIN (
            SELECT user, COUNT(*) AS count
            FROM book
            GROUP BY user
        ) AS b
        ON u.login = b.user";

    if (count($conditions) > 0) {
        $sqlSelectUsers .= " WHERE " . implode(' AND ', $conditions);
    }

    $stmt = $conn->prepare($sqlSelectUsers);
    $stmt->execute();
    $total = $stmt->rowCount();

?>

<?php

} catch (PDOException $e) {
    echo "<p>Erreur: " . $e->getMessage() . "</p>\n";
}

include("header.php");

?>

<div class="wrapper row2">
    <div id="container" class="clear">

        <div class="filters">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label>Логин:</label> <input type="text" name="login" value="<?php echo $login ?>">
                    <label>Имя: <input type="text" name="name" value="<?php echo $name ?>"></label>
                    <label>Фамилия: <input type="text" name="last_name" value="<?php echo $last_name ?>"></label>
                    <label>Пол: <select name="gender">
                            <option value=""> </option>
                            <option value="femme">femelle</option>
                            <option value="homme">mâle</option>
                        </select>
                    </label>
                    <label>Страна: <input type="text" name="country" value="<?php echo $country ?>"></label>
                    <label>Город: <input type="text" name="city" value="<?php echo $city ?>"></label>
                    <input type="submit" name="submit" value="Фильтр">
            </form>
        </div>

        <div class="linetop"></div>
        <h2 class="headline">Всего <?php echo $total ?> пользователей</h2>
        <div class="line"></div>

        <?php while ($row = $stmt->fetchObject()) { ?>

            <div class="one_third1">
                <div class="fl_left1 max100">
                    <a href="<?php echo $row->photo ?>" target="_blank"><img src="<?php echo $row->photo ?>" alt=""/></a>
                </div>

                <div class="fl_left1">
                    <p><strong>Логин:</strong> <?php echo $row->login ?></p>
                    <?php if ($row->name != "NULL" || $row->last_name != "NULL") { ?>
                        <p><strong>Фамилия:</strong>
                            <?php
                                if ($row->name != "NULL") { echo $row->name . " "; }
                                if ($row->last_name != "NULL") { echo $row->last_name; }
                            ?>
                        </p>
                    <?php } ?>
                    <p><strong>Пол:</strong> <?php echo $row->gender ?></p>
                    <p><strong>Местонахождение:</strong> <?php echo $row->country . ", " . $row->city ?></p>
                    <p><strong>Книжек на сайте:</strong><a href="my_books.php?user=<?php echo $row->login ?>"
                            > <?php echo $row->count ?></a></p>
                    <a href="user.php?user=<?php echo $row->login ?>">Посмотреть полный профиль с контактами</a>
                </div>
            </div>

        <?php } ?>

    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>