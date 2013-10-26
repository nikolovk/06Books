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
<form action="login.php" method="POST" role="form">
    <p class="text-danger"><?= isset($errors['notLogged']) ? $errors['notLogged'] : '' ?></p>
    <div class="form-group">
        <label for="name">Име:</label>
        <input type="text" id="name" name="name" value="<?= $name ?>" class="form-control" />
    </div>
    <div class="form-group">
        <label for="password">Парола:</label>
        <input type="password" id="password" name="password" class="form-control" />
    </div> 
    <div class="form-group">
    <button type="submit" class="btn btn-default">Вход</button>
    </div>
</form>
<?php
include 'includes/footer.php';