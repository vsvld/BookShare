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
    <title>À propos de nous | BookShare</title>
</head>
<body>

<?php include("header.php"); ?>

<div class="wrapper row2">
    <div id="container" class="clear">

        <h2 class="headline">L'équipe de BookShare </h2>
        <div class="line"></div>

        <!-- Upper image -->
        <section><img src="img/about.jpg" alt=""></section>

        <!-- main content -->
        <div id="homepage">
            <section id="types" class="clear">
                <article class="one_third">
                    <figure><img src="img/vk.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>VK</h2>
                            <p>Nous sommes heureux de communiquer avec tous ceux qui sont intéressés par notre projet.
                                De toutes les questions et suggestions vous pouvez contacter Alina :) </p>
                            <footer class="more"><a href="http://vk.com/alina_ten">Écrire à Alina &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
                <article class="one_third">
                    <figure><img src="img/Gmail.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Gmail</h2>
                            <p>Si vous trouvez un bug ou vous avez des questions au support technique,
                                contactez-nous à: support@bookshare.freeiz.com. Nous répondons à tous!</p>
                            <footer class="more"><a href="mailto:support@bookshare.freeiz.com">Envoyer un email &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
                <article class="one_third last">
                    <figure><img src="img/facebook.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Facebook</h2>
                            <p>Si vous avez une question sur la coopération, le développement de projets
                                et l'expansion de la base d'affiliation, contacter Vsevolod :)</p><br>
                            <footer class="more"><a href="https://www.facebook.com/vsevololod">Écrire à Vsevolod &raquo;</a></footer>
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