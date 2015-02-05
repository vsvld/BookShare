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
    <title>О нас | BookShare</title>
</head>
<body>

<?php include("header.php"); ?>

<div class="wrapper row2">
    <div id="container" class="clear">

        <h2 class="headline">Команда BookShare</h2>
        <div class="line"></div>

        <!-- Upper image -->
        <section><img src="/img/about.jpg" alt=""></section>

        <!-- main content -->
        <div id="homepage">
            <section id="types" class="clear">
                <article class="one_third">
                    <figure><img src="/img/vk.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>VK</h2>
                            <p>Мы будем рады поообщаться с теми, кто заинтересован в нашем проекте.
                                По всем вопросам и предложениям вы можете связаться с Алиной :) </p>
                            <footer class="more"><a href="http://vk.com/alina_ten">Написать Алине &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
                <article class="one_third">
                    <figure><img src="/img/Gmail.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Email</h2>
                            <p>Если вы нашли ошибку или у вас есть вопросы к службе технической поддержки,
                                пожалуйста, свяжитесь с нами по адресу: support@bookshare.freeiz.com. Мы ответим на всё!</p>
                            <footer class="more"><a href="mailto:support@bookshare.freeiz.com">Отправить email &raquo;</a></footer>
                        </figcaption>
                    </figure>
                </article>
                <article class="one_third last">
                    <figure><img src="/img/facebook.jpg" width="290" height="180" alt="">
                        <figcaption>
                            <h2>Facebook</h2>
                            <p>Если у вас есть вопросы относительно сотрудничества, по проектам развития
                                или расширения партнерской базы, свяжитесь с Всеволодом :)</p><br>
                            <footer class="more"><a href="https://www.facebook.com/vsevololod">Написать Всеволоду &raquo;</a></footer>
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