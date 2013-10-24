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
        $sql = 'SELECT name, id FROM users WHERE name="' . $name . '" AND password="' . $password . '"';
        $query = mysqli_query($connection, $sql);
        if (!$query) {
            echo 'Connection problem';
            echo mysqli_error($connection);
            exit;
        }
        if (mysqli_num_rows($query) == 1) {
            $row = mysqli_fetch_assoc($query);
            $_SESSION['isLogged'] = TRUE;
            $_SESSION['name'] = $row['name'];
            $_SESSION['id'] = $row['id'];
            header('Location: index.php');
            exit;
        } else {
            $errors['notLogged'] = 'Грешно име или парола.';
        }
    }
}
include 'includes/header.php';
?>
<h2>Вход</h2>
<form action="login.php" method="POST">
    <p class="error"><?= isset($errors['notLogged']) ? $errors['notLogged'] : '' ?></p>
    <p>
        <label for="name">Име:</label>
        <input type="text" name="name" value="<?= $name ?>" />
    </p>
    <p>
        <label for="password">Парола:</label>
        <input type="password" name="password" />
    </p>
    <input type="submit" value="Вход" />
</form>
<?php
include 'includes/footer.php';