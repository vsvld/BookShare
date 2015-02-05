<div class="wrapper row1">
    <header id="header" class="clear">
        <div id="hgroup">
            <h1><a href="index.php"><strong id="logo">Book</strong>Share</a></h1>
            <h2>Ищите. Делитесь. Общайтесь</h2>
        </div>
        <nav>
            <ul>
                <li><a href="books.php">Книги</a></li>
                <li><a href="users.php">Пользователи</a></li>
                <li><a href="about.php">О нас</a></li>
                <?php if (isset($_SESSION["user"])) { ?>
                    <li><a href="user.php" class="buttonspecial"><?php echo $_SESSION["user"] ?></a></li>
                    <li class="last"><a href="logout.php" class="buttonspecial">Выйти</a></li>
                <?php } else { ?>
                    <li><a href="user_log.php" class="buttonspecial">Войти</a></li>
                    <li class="last"><a href="user_reg.php" class="buttonspecial">Регистрация</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
</div>