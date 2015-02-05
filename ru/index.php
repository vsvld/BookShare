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
    <title>BookShare</title>
</head>
<body>

<?php include("header.php"); ?>

<div class="wrapper row2">
    <div id="container" class="clear">
        <section><img src="/img/home.jpg" alt=""></section>
        <div id="homepage">
            <section id="types" class="clear">
                <article class="one_third">
                    <figure><img src="/img/a.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Ищите</h2>
                            <p>Ищите книги, которые вы хотели бы прочесть, находите желаемые и получайте их бесплатно!</p>
                            <br>
                            <footer class="more"><a href="books.php">Книги &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
                <article class="one_third">
                    <figure><img src="/img/c.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Делитесь</h2>
                            <p>У вас есть книги, которыми вы готовы поделиться с другими? Регистрируйтесь и добавляйте их!</p>
                            <footer class="more"><a href="user_reg.php">Регистрация &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
                <article class="one_third last">
                    <figure><img src="/img/b.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Общайтесь</h2>
                            <p>Находите друзей по интересам и общайтесь с ними!</p>
                            <br>
                            <footer class="more"><a href="users.php">Пользователи &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
            </section>
        </div>
    </div>
</div>

<?php include("footer.html"); ?>

</body>
</html>