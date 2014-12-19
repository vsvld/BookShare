<?php
session_start();
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
    <title>BookShare</title>
</head>
<body>

<?php include("header.php"); ?>

<div class="wrapper row2">
    <div id="container" class="clear">
        <section><img src="img/home.jpg" alt=""></section>
        <div id="homepage">
            <section id="types" class="clear">
                <article class="one_third">
                    <figure><img src="img/a.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Chercher</h2>
                            <p>Cherchez un livre que vous voudriez bien lire, trouvez un prioritaire de ce livre et obtenez-le gratuit!</p>
                            <footer class="more"><a href="books.php">Livres &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
                <article class="one_third">
                    <figure><img src="img/c.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Partager</h2>
                            <p>Vous avez un livre que vous voudriez offrir aux autres? Inscrivez-vous et ajoutez-le!</p>
                            <footer class="more"><a href="user_reg.php">S'inscrire &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
                <article class="one_third last">
                    <figure><img src="img/b.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Se communiquer</h2>
                            <p>Trouvez vos amis des intérêts et communiquez avec eux!</p>
                            <footer class="more"><a href="users.php">Utilisateurs &raquo;</a></footer>
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