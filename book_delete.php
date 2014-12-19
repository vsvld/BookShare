<?php
session_start();

if (isset($_SESSION["user"])) {
    $login = $_SESSION["user"];

    include("connect.inc.php");

    try {
        // connect to the database
        $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
        $conn->exec("set names utf8");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_GET["id"];

        $stmt = $conn->query("SELECT id FROM book WHERE id='$id' AND user='$login'");
        if ($stmt->rowCount() == 1) {
            $conn->query("DELETE FROM book WHERE id='$id'");
        }

        header("Location: my_books.php");

    } catch (PDOException $e) {
        echo "<p>Erreur: " . $e->getMessage() . "</p>\n";
    }

} else {
    header("Location: user_log.php");
}