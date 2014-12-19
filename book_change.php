<?php
session_start();

// only logged user can change the book
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
    <title>Modifier le livre | BookShare</title>
</head>
<body>

<?php

include("connect.inc.php");

try {
    // connect to the database
    $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // get id of the book from URL
    $id = $_GET["id"];

    // access to book change only to user who's book it is
    $stmt = $conn->query("SELECT id FROM book WHERE id='$id' AND user='$login'");
    if ($stmt->rowCount() == 0) {
        header("Location: user_log.php");
    }

    // define variables and set to empty values
    $name = $author = $language = $genre = $review = $photo = "";
    $photoErr = "";
    $conditions = array();

    // function to clear input data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES);
        return $data;
    }

    // check fields
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["name"])) {
            $name = test_input($_POST["name"]);
            $conditions[] = "name='$name'";
        }

        if (!empty($_POST["author"])) {
            $author = test_input($_POST["author"]);
            $conditions[] = "author='$author'";
        }

        if (!empty($_POST["language"])) {
            $language = $_POST["language"];
            $conditions[] = "language='$language'";
        }

        if (!empty($_POST["genre"])) {
            $genre = $_POST["genre"];
            $conditions[] = "genre='$genre'";
        }

        if (!empty($_POST["review"])) {
            $review = test_input($_POST["review"]);
            $conditions[] = "review='$review'";
        }

        if ($_FILES["photo"]["size"] > 0) {
            $target_dir = "books_img/";
            $imageFileType = pathinfo(basename($_FILES["photo"]["name"]), PATHINFO_EXTENSION);
            $uploadOk = 1;

            // changing the name of the picture due to the changement of book's name
            if (empty($name)) {
                $sqlGetName = $conn->query("SELECT name FROM book WHERE user='$login'");
                $name_now = $sqlGetName->fetchColumn();
                $photo = $target_dir . str_replace(' ', '_', $login) . "-" . str_replace(' ', '_', $name_now) . "1." . $imageFileType;
            } else {
                $photo = $target_dir . str_replace(' ', '_', $login) . "-" . str_replace(' ', '_', $name) . "." . $imageFileType;
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

            // allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                $uploadOk = 0;
            }

            // check if $uploadOk is not set to 0 by an error
            if ($uploadOk == 1) {
                $conditions[] = "photo='$photo'";
                if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo)) {
                    $photoErr = "Désolé, il y a une problème lors du chargement de votre fichier.";
                }
            }
        }
    }

    // updating database
    if (count($conditions) > 0 && empty($photoErr)) {
        $photo = "/" . $photo;
        $sql = "UPDATE book SET " . implode(',', $conditions) . " WHERE id='$id'";
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
        <h2 class="headline">Remplissez les données qui vous voudrais changer</h2>
        <h3 class="headline">(laissez les champs vides si vous ne les voulez pas changer)</h3>

        <form method="post" enctype="multipart/form-data" action="#">
            <div class="border-form">
                <div class="form">
                    <label>Titre:</label> <input type="text" name="name" value="<?php echo $name ?>">
                    <div></div>

                    <label>Auteur:</label> <input type="text" name="author" value="<?php echo $author ?>">
                    <div></div>

                    <label>Langue:</label> <select name="language">
                        <option value=""> </option>
                        <option value="français">français</option>
                        <option value="russe">russe</option>
                        <option value="ukrainien">ukrainien</option>
                        <option value="anglais">anglais</option>
                        <option value="allemand">allemand</option>
                        <option value="espagnol">espagnol</option>
                    </select>
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
                    <span class="error"> <?php echo $photoErr ?></span>
                    <div></div>

                    <input type="submit" name="submit" value="Modifier" id="button">
                </div>
            </div>
        </form>
    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>