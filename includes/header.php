
<!DOCTYPE html>
<html>
    <head>
        <title>Books</title>
<!--        <link rel="stylesheet" href="style.css">-->
        <meta charset="UTF-8">       
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="navbar navbar-inverse">
            <div class="container">
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Всички Книги</a></li>
                        <li><a href="addAuthor.php">Нов Автор</a></li>
                        <li><a href="addBook.php">Нова Книга</a></li>
                        <?php if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == true) { ?>
                            <li><a href="logout.php">Изход</a></li>
                        <?php } else { ?>
                            <li><a href="register.php">Регистрация</a></li>
                            <li><a href="login.php">Вход</a></li>
                        <?php } ?>
                    </ul>
                <?php if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == true) { ?>
                    <p class="navbar-text navbar-right">Здравейте <?= $_SESSION['name'] ?>!</p>
                <?php } ?>
                </div><!--/.nav-collapse -->
            </div>
        </div>
        <div class="container">

