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
    <title>Регистрация | BookShare</title>
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
    $login = $password = $email = $name = $last_name = $gender = $country = $city = $telephone = $facebook = $photo = $about = "";
    $loginErr = $passwordErr = $emailErr = $genderErr = $countryErr = $cityErr = $photoErr = "";

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
        if (empty($_POST["login"])) {
            $loginErr = "Логин обязателен";
        } else {
            $login = test_input($_POST["login"]);
            $sqlCheckUser = $conn->query("SELECT COUNT(login) FROM user WHERE login='$login'");
            if ($sqlCheckUser->fetchColumn() > 0) {
                $loginErr = "Пользователь с таким логином не существует";
                $login = "";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Email обязателен";
        } else {
            $email = test_input($_POST["email"]);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Неправильный формат email";
                $email = "";
            }

            // check if email exists in database, must be unique
            $sqlCheckEmail = $conn->query("SELECT COUNT(email) FROM user WHERE email='$email'");
            if ($sqlCheckEmail->fetchColumn() > 0) {
                $emailErr = "Пользователь с таким email уже существует";
                $email = "";
            }
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Пароль обязателен";
        } else {
            $password = $_POST["password"];
        }

        if (!empty($_POST["name"])) {
            $name = test_input($_POST["name"]);
        }

        if (!empty($_POST["last_name"])) {
            $last_name = test_input($_POST["last_name"]);
        }

        if (empty($_POST["gender"])) {
            $genderErr = "Пол обязателен";
        } else {
            $gender = $_POST["gender"];
        }

        if (empty($_POST["country"])) {
            $countryErr = "Страна обязательна";
        } else {
            $country = test_input($_POST["country"]);
        }

        if (empty($_POST["city"])) {
            $cityErr = "Город обязателен";
        } else {
            $city = test_input($_POST["city"]);
        }

        if (!empty($_POST["telephone"])) {
            $telephone = test_input($_POST["telephone"]);
        }

        if (!empty($_POST["facebook"])) {
            $facebook = test_input($_POST["facebook"]);
        }

        if (!empty($_POST["about"])) {
            $about = test_input($_POST["about"]);
        }

        if ($_FILES["photo"]["size"] > 0) {
            $target_dir = "users_img/";
            $imageFileType = pathinfo(basename($_FILES["photo"]["name"]), PATHINFO_EXTENSION);
            $uploadOk = 1;

            if (empty($login)) {
                $uploadOk = 0;
            } else {
                $photo = $target_dir . str_replace(' ', '_', $login) . "." . $imageFileType;
                $uploadOk = 1;
            }

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if ($check == false) {
                $photoErr = "Файл не является картинкой.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["photo"]["size"] > 500000) {
                $photoErr = "К сожалению, Ваш файл слишком большого размера.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "К сожалению, принимаются только JPG, JPEG, PNG и GIF.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is not set to 0 by an error
            if ($uploadOk == 1) {
                if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo)) {
                    $photoErr = "К сожалению, произошла ошибка при загрузке Вашего файла.";
                }
            }
        }
    }

    // adding to database
    if (!empty($login) && !empty($password) && !empty($email) && !empty($gender) && !empty($country) && !empty($city) && empty($photoErr)) {
        $name = isEmptyString($name);
        $last_name = isEmptyString($last_name);
        $telephone = isEmptyString($telephone);
        $facebook = isEmptyString($facebook);
        $about = isEmptyString($about);
        $photo = !empty($photo) ? "/" . $photo : "/users_img/default_user.png";

        $sql =
            "INSERT INTO user (login, password, email, name, last_name, gender, country, city, telephone, facebook, photo, about)
        VALUES ('$login', '$password', '$email', '$name', '$last_name', '$gender', '$country', '$city', '$telephone', '$facebook', '$photo', '$about')";

        $conn->exec($sql);
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

        <p><span class="error">* обязательное поле</span></p>


        <form method="post" enctype="multipart/form-data"
              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="border-form">
                <div class="form">
                    <label>Предпочитаемый логин:</label> <input type="text" name="login" value="<?php echo $login; ?>">
                    <span class="error">* <?php echo $loginErr; ?></span>

                    <div></div>
                    <label>Email:</label> <input type="text" name="email" value="<?php echo $email; ?>">
                    <span class="error">* <?php echo $emailErr; ?></span>

                    <div></div>

                    <div></div>
                    <label>Пароль:</label> <input type="password" name="password"
                                                          value="<?php echo $password; ?>">
                    <span class="error">* <?php echo $passwordErr; ?></span>

                    <div></div>


                    <label>Имя:</label> <input type="text" name="name" value="<?php echo $name; ?>">

                    <div></div>

                    <label>Фамилия:</label> <input type="text" name="last_name" value="<?php echo $last_name; ?>">

                    <div></div>

                    <label>Пол:</label>
                    <input type="radio"
                           name="gender" <?php if (isset($gender) && $gender == "femme") echo "checked"; ?>
                           value="femme"> Женский:
                    <input type="radio" name="gender" <?php if (isset($gender) && $gender == "homme") echo "checked"; ?>
                           value="homme"> Мужской:
                    <span class="error">* <?php echo $genderErr; ?></span>

                    <div></div>

                    <label>Страна:</label> <input type="text" name="country" value="<?php echo $country; ?>">
                    <span class="error">* <?php echo $countryErr; ?></span>

                    <div></div>

                    <label>Город:</label> <input type="text" name="city" value="<?php echo $city; ?>">
                    <span class="error">* <?php echo $cityErr; ?></span>

                    <div></div>
                    <div></div>

                    <label>Телефон:</label> <input type="text" name="telephone" value="<?php echo $telephone; ?>">

                    <div></div>

                    <label>Facebook:</label> <input type="text" name="facebook" value="<?php echo $facebook; ?>">

                    <div></div>

                    <label>Расскажите о себе: <span></span> <textarea name="about" rows="5"
                                                                        cols="45"><?php echo $about; ?></textarea>
                    </label>

                    <div></div>

                    <div></div>
                    <label>Фото: </label> <input type="file" name="photo" id="photo"> <span
                        class="error"> <?php echo $photoErr; ?></span>

                    <div></div>
                    <input type="submit" name="submit" value="Зарегистрироваться" id="button">
                </div>
            </div>
        </form>
    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>