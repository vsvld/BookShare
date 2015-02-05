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
    <title>Книги | BookShare</title>
</head>
<body>

<?php

include("connect.inc.php");

try {
    // connect to the database
    $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // function to clear input data
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = $author = $language = $genre = $country = $city = ""; // define variables and set to empty values
    $conditions = array(); // array of sorting conditions

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $sort = " ORDER BY book.id " . $_POST["sort"];

        if (!empty($_POST["name"])) {
            $name = test_input($_POST["name"]);
            $conditions[] = "book.name LIKE '%$name%'";
        }

        if (!empty($_POST["author"])) {
            $author = test_input($_POST["author"]);
            $conditions[] = "book.author LIKE '%$author%'";
        }

        if (!empty($_POST["language"])) {
            $language = test_input($_POST["language"]);
            $conditions[] = "book.language='$language'";
        }

        if (!empty($_POST["genre"])) {
            $genre = test_input($_POST["genre"]);
            $conditions[] = "book.genre='$genre'";
        }

        if (!empty($_POST["country"])) {
            $country = test_input($_POST["country"]);
            $conditions[] = "user.country LIKE '%$country%'";
        }

        if (!empty($_POST["city"])) {
            $city = test_input($_POST["city"]);
            $conditions[] = "user.city LIKE '%$city%'";
        }
    }

    // select all books available
    $sqlSelectBooks = "
        SELECT book.id, book.name, book.author, book.language, book.genre, book.user, book.review, book.photo, user.country, user.city
        FROM book
        INNER JOIN user ON book.user = user.login";

    // filter books
    if (count($conditions) > 0) {
        $sqlSelectBooks .= " WHERE " . implode(' AND ', $conditions) . $sort;
    } else {
        $sqlSelectBooks .= $sort;
    }

    $stmt = $conn->prepare($sqlSelectBooks);
    $stmt->execute();
    $total = $stmt->rowCount(); // total number of books from query

} catch (PDOException $e) {
    echo "<p>Erreur: " . $e->getMessage() . "</p>\n";
}

include("header.php");

?>

<div class="wrapper row2">
    <div id="container" class="clear">
        <div class="filters">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div id="filtcent"><label>Pays:</label> <input type="text" name="country" value="<?php echo $country ?>">
                <label>Город:</label> <input type="text" name="city" value="<?php echo $city ?>">
                <label>Название:</label><input type="text" name="name" value="<?php echo $name ?>">
                <label>Автор:</label><input type="text" name="author" value="<?php echo $author ?>">
                <label>Язык:</label> <select name="language">
                    <option value=""> </option>
                    <option value="français">французский</option>
                    <option value="russe">русский</option>
                    <option value="ukrainien">украинский</option>
                    <option value="anglais">английский</option>
                    <option value="allemand">немецкий</option>
                    <option value="espagnol">испанский</option>
                </select>
                <br><br>
                <label>Жанр:</label> <select name="genre">
                    <option value=""> </option>
                    <option value="nouvelle">Рассказы</option>
                    <option value="conte">История</option>
                    <option value="roman d'aventure">Приключенческий роман</option>
                    <option value="fantastique">Фантастика</option>
                    <option value="policier">Детектив</option>
                    <option value="noir">Ужасы</option>
                    <option value="sentimental">Роман</option>
                    <option value="poésie">Поэзия</option>
                    <option value="comédie">Комедия</option>
                    <option value="tragédie">Трагедия</option>
                    <option value="tragi-comédie">Траги-комедия</option>
                    <option value="mystère">Мистерия</option>
                    <option value="vaudeville">Водевиль</option>
                </select>
                Сортировка: <select name="sort">
                        <option value="DESC">Сначала новые книги</option>
                        <option value="ASC">Сначала старые книги</option>
                    </select>
                <input type="submit" name="submit" value="Фильтр"></div>
            </form>
        </div>

        <div class="linetop"></div>
        <h2 class="headline">Всего доступно <?php echo $total ?> книг</h2>
        <div class="line"></div>

        <?php while ($row = $stmt->fetchObject()) { ?>
            <div class="one_third1">
                <div class="fl_left1 max100">
                    <a href="<?php echo $row->photo ?>" target="_blank"><img src="<?php echo $row->photo ?>"
                                                                             alt=""/></a>
                </div>
                <div class="fl_left1">
                    <p><strong>Название:</strong> <?php echo $row->name ?></p>
                    <p><strong>Автор:</strong> <?php echo $row->author ?></p>
                    <p><strong>Язык:</strong> <?php echo $row->language ?></p>
                    <?php if ($row->genre != "NULL") { ?><p><strong>Жанр:</strong> <?php echo $row->genre . "</p>"; } ?>
                    <p><strong>Пользователь:</strong> <a href="user.php?user=<?php echo $row->user ?>"><?php echo $row->user ?></a></p>
                    <?php if ($row->review != "NULL") { ?><p><strong>Мнение:</strong> <?php echo $row->review . "</p>"; } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>