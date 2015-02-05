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
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <meta charset="utf-8"/>
    <meta name="Author" content="Vsevolod Alokhin, Alina Ten"/>
    <meta name="Keywords" content="book, livre, share, partager, bookcrossing"/>
    <meta name="Description" content="Cherchez et partagez livres gratuit"/>
    <title>Ajouter un livre | BookShare</title>
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
            $nameErr = "Nom est obligatoire";
        } else {
            $name = test_input($_POST["name"]);
        }

        if (empty($_POST["author"])) {
            $authorErr = "Auteur est obligatoire";
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
                $photoErr = "Fichier n'est pas une image.";
                $uploadOk = 0;
            }

            // check file size
            if ($_FILES["photo"]["size"] > 500000) {
                $photoErr = "Désolé, votre fichier est trop volumineux.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                $uploadOk = 0;
            }
            // check if $uploadOk is not set to 0 by an error
            if ($uploadOk == 1) {
                if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo)) {
                    $photoErr = "Désolé, il y a une problème lors du chargement de votre fichier.";
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
        <h2 class="headline">Ajouter un livre pour partager</h2>

        <p><span class="error">* champ obligatoire</span></p>

        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="border-form">
                <div class="form">
                    <label>Titre:</label> <input type="text" name="name" value="<?php echo $name ?>">
                    <span class="error">* <?php echo $nameErr; ?></span>
                    <div></div>

                     <label>Auteur:</label> <input type="text" name="author" value="<?php echo $author ?>">
                    <span class="error">* <?php echo $authorErr; ?></span>
                    <div></div>

                     <label>Langue:</label> <select name="language">
                        <option value="français">français</option>
                        <option value="russe">russe</option>
                        <option value="ukrainien">ukrainien</option>
                        <option value="anglais">anglais</option>
                        <option value="allemand">allemand</option>
                        <option value="espagnol">espagnol</option>
                    </select>
                    <span class="error">*</span>
                    <div></div>

                     <label>Genre:</label> <select name="genre">
                        <option value=""> </option>
                        <option value="nouvelle">la nouvelle</option>
                        <option value="conte">le conte</option>
                        <option value="roman d'aventure">le roman d'aventure</option>
                        <option value="fantastique">le fantastique</option>
                        <option value="policier">policier</option>
                        <option value="noir">noir</option>
                        <option value="sentimental">sentimental</option>
                        <option value="poésie">la poésie</option>
                        <option value="comédie">la comédie</option>
                        <option value="tragédie">la tragédie</option>
                        <option value="tragi-comédie">la tragi-comédie</option>
                        <option value="mystère">le mystère</option>
                        <option value="vaudeville">le vaudeville</option>
                    </select>
                    <div></div>

                    <label>Votre avis:</label> <textarea name="review" rows="5" cols="40"><?php echo $review ?></textarea>
                    <div></div>

                    <label>L'image:</label> <input type="file" name="photo" id="photo">
                    <span class="error"><?php echo $photoErr ?></span>
                    <div></div>

                    <input type="submit" name="submit" value="Ajouter" id="button">
                </div>
            </div>
        </form>

    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>