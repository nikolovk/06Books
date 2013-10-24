
<!DOCTYPE html>
<html>
    <head>
        <title>Books</title>
        <link rel="stylesheet" href="style.css">
        <meta charset="UTF-8">       
    </head>
    <body>
        <p>
            <a href="index.php">Всички Книги</a> |
            <a href="addBook.php">Нова Книга</a> | 
            <a href="addAuthor.php">Нов Автор</a>
            <?php if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == true) { ?>
                | <a href="logout.php">Изход</a>
            <br /><p>Здравейте <?= $_SESSION['name'] ?>!</p>
        <?php } else { ?>
            |   <a href="register.php">Регистрация</a>
            |   <a href="login.php">Вход</a>
        <?php } ?>
    </p>  

