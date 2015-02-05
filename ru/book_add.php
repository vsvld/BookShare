<?php
session_start();

if (isset($_SESSION["user"])) {
    $login = $_SESSION["user"];
} else {
    header("Location: user_log.php");
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
    <title>Добавить книгу | BookShare</title>
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
    $name = $author = $language = $genre = $review = $photo = "";
    $nameErr = $authorErr = $photoErr = "";

    // function to clear input data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES);
        return $data;
    }

    // function to change from empty string to NULL
    function isEmptyString($val) {
        if ($val === '') {
            $val = "NULL";
        }
        return $val;
    }

    // checks fields
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "Имя обязательно";
        } else {
            $name = test_input($_POST["name"]);
        }

        if (empty($_POST["author"])) {
            $authorErr = "Автор обязателен";
        } else {
            $author = test_input($_POST["author"]);
        }

        $language = $_POST["language"];

        if (!empty($_POST["genre"])) {
            $genre = $_POST["genre"];
        }

        if (!empty($_POST["review"])) {
            $review = test_input($_POST["review"]);
        }

        if ($_FILES["photo"]["size"] > 0) {
            $target_dir = "books_img/";
            $imageFileType = pathinfo(basename($_FILES["photo"]["name"]), PATHINFO_EXTENSION);
            $uploadOk = 1;

            if (empty($name)) {
                $uploadOk = 0;
            } else {
                $photo = $target_dir . str_replace(' ', '_', $login) . "-" . str_replace(' ', '_', $name) . "." . $imageFileType;
                $uploadOk = 1;
            }

            // check if image file is a actual image or fake image
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if ($check == false) {
                $photoErr = "Файл не является картинкой.";
                $uploadOk = 0;
            }

            // check file size
            if ($_FILES["photo"]["size"] > 500000) {
                $photoErr = "К сожалению, Ваш файл слишком большого размера.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "К сожалению, принимаются только JPG, JPEG, PNG и GIF.";
                $uploadOk = 0;
            }
            // check if $uploadOk is not set to 0 by an error
            if ($uploadOk == 1) {
                if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo)) {
                    $photoErr = "К сожалению, произошла ошибка при загрузке Вашего файла.";
                }
            }
        }
    }

    // adding to database
    if (!empty($name) && !empty($author) && !empty($language) && empty($photoErr)) {
        $genre = isEmptyString($genre);
        $review = isEmptyString($review);
        $photo = !empty($photo) ? "/" . $photo : "/books_img/default_book.jpg";

        $sql =
            "INSERT INTO book (name, author, language, genre, user, review, photo)
        VALUES ('$name', '$author', '$language', '$genre', '$login', '$review', '$photo')";

        $conn->exec($sql);
        header("Location: my_books.php");
    }


} catch (PDOException $e) {
    echo "<p>Erreur: " . $e->getMessage() . "</p>\n";
}

include("header.php");

?>

<div class="wrapper row2">
    <div id="container" class="clear">
        <h2 class="headline">Добавить книгу, чтобы поделиться</h2>

        <p><span class="error">* обязательное поле</span></p>

        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="border-form">
                <div class="form">
                    <label>Название:</label> <input type="text" name="name" value="<?php echo $name ?>">
                    <span class="error">* <?php echo $nameErr; ?></span>
                    <div></div>

                     <label>Автор:</label> <input type="text" name="author" value="<?php echo $author ?>">
                    <span class="error">* <?php echo $authorErr; ?></span>
                    <div></div>

                     <label>Язык:</label> <select name="language">
                        <option value="français">французский</option>
                        <option value="russe">русский</option>
                        <option value="ukrainien">украинский</option>
                        <option value="anglais">английский</option>
                        <option value="allemand">немецкий</option>
                        <option value="espagnol">испанский</option>
                    </select>
                    <span class="error">*</span>
                    <div></div>

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
                    <div></div>

                    <label>Ваше мнение:</label> <textarea name="review" rows="5" cols="40"><?php echo $review ?></textarea>
                    <div></div>

                    <label>Картинка:</label> <input type="file" name="photo" id="photo">
                    <span class="error"><?php echo $photoErr ?></span>
                    <div></div>

                    <input type="submit" name="submit" value="Добавить" id="button">
                </div>
            </div>
        </form>

    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>