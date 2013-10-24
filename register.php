<?php
include 'includes/connection.php';
$errors = array();
$name = '';
if (isset($_SESSION['isLogged'])) {
    header('Location: index.php');
    exit;
} else {
    if ($_POST) {
        $trimedName = htmlspecialchars(trim($_POST['name']));
        $name = mysqli_real_escape_string($connection, $trimedName);
        $trimedPassword = trim($_POST['password']);
        $password = mysqli_real_escape_string($connection, $trimedPassword);
        if (empty($name) || mb_strlen($name) < 3) {
            $errors['name'] = 'Името трябва да бъде поне 3 символа!';
        }
        if (empty($password) || mb_strlen($password) < 3) {
            $errors['password'] = 'Паролата трябва да бъде поне 3 символа!';
        }
        if (count($errors) == 0) {
            $checkQuery = mysqli_query($connection, 'SELECT name FROM users WHERE name ="' . $trimedName . '"');
            if (mysqli_num_rows($checkQuery) == 0) {
                $sql = 'INSERT INTO users (name,password) VALUES ("' . $name . '","' . $password . '")';
                $query = mysqli_query($connection, $sql);
                if (!$query) {
                    echo 'Connection problem';
                    echo mysqli_error($connection);
                    exit;
                }
                $id = mysqli_insert_id($connection);
                if ($id > 0) {
                    $_SESSION['isLogged'] = TRUE;
                    $_SESSION['name'] = $name;
                    $_SESSION['id'] = $id;
                    header('Location: index.php');
                } else {
                    $errors['register'] = 'Unsuccessful register';
                }
            } else {
                $errors['register'] = 'Потребител с това име вече съществува!';
            }
        }
    }
}
include 'includes/header.php';
?>
<h2>Регистрация</h2>
<form action="register.php" method="POST">
    <p class="error"><?= isset($errors['register']) ? $errors['register'] : '' ?></p>
        <p>
        <label for="name">Име:</label>
        <input type="text" name="name" value="<?= $name ?>" />
        <span class="error"><?= isset($errors['name']) ? $errors['name'] : '' ?></span>
    </p>
    <p>
        <label for="password">Парола:</label>
        <input type="password" name="password" />
        <span class="error"><?= isset($errors['password']) ? $errors['password'] : '' ?></span>
    </p>
    <input type="submit" value="Регистрирай" />
</form>
<?php
include 'includes/footer.php'
?>



