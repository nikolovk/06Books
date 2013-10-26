<?php
include 'includes/connection.php';
$errors = array();
$messages = array();
if ($_POST) {
    $authorName = mysqli_escape_string($connection, htmlspecialchars(trim($_POST['authorName'])));
    if (mb_strlen($authorName, 'UTF-8') >= 3) {
        $query = mysqli_query($connection, 'SELECT author_name FROM authors WHERE author_name = "' . $authorName . '"');
        if ($query && mysqli_num_rows($query) == 0) {
            $result = mysqli_query($connection, 'INSERT INTO authors (author_name) VALUES ("' . $authorName . '")');
            if ($result) {
                $messages['success'] = 'Авторът беше добавен успешно!';
                $authorName = '';
            } else {
                $errors['record'] = 'Неуспешен запис!';
            }
        } else {
            $errors['duplicate'] = 'Има автор със същото име!';
        }
    } else {
        $errors['length'] = 'Името трябва да бъде поне 3 символа!';
    }
}
$sql = 'SELECT authors.author_name, authors.author_id FROM authors';
$query = mysqli_query($connection, $sql);
$authors = array();
if (!$query) {
    echo 'Connection problem';
    echo mysqli_error($connection);
    exit;
}
while ($row = mysqli_fetch_assoc($query)) {
    $authors[$row['author_id']] = $row['author_name'];
}
$countAuthors = count($authors);
include 'includes/header.php';
?>

<h2>Добави автор</h2>
<p class="text-success"><?= isset($messages['success']) ? $messages['success'] : '' ?></p>
<p class="text-danger"><?= isset($errors['record']) ? $errors['record'] : '' ?></p>
<p class="text-danger"><?= isset($errors['duplicate']) ? $errors['duplicate'] : '' ?></p>
<p class="text-danger"><?= isset($errors['length']) ? $errors['length'] : '' ?></p>
<form action="addAuthor.php" method="POST"  role="form">
    <div class="form-group">
        <label for="authorName">Автор:</label>
        <input name="authorName" id="authorName" value="<?= isset($authorName) ? $authorName : '' ?>" class="form-control" />
    </div>
    <div class="form-group">       
        <button type="submit" class="btn btn-default">Добави</button>

    </div>
</form>

<?php if ($countAuthors > 0) { ?>
    <div class="container">
        <h3>Автори</th>
            <ul class="list-unstyled">
                <?php foreach ($authors as $author_id => $author_name) { ?>
                    <li><a href="index.php?author_id=<?= $author_id ?>"><?= $author_name ?></a> </li>
                <?php } ?>
            </ul>
    </div>
<?php } else {
    ?>
    <p class="text-danger">Няма въведени автори</p>
    <?php
}
include 'includes/footer.php';
