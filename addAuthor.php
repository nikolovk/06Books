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
<p><?= isset($messages['success']) ? $messages['success'] : '' ?></p>
<p><?= isset($errors['record']) ? $errors['record'] : '' ?></p>
<p><?= isset($errors['duplicate']) ? $errors['duplicate'] : '' ?></p>
<p><?= isset($errors['length']) ? $errors['length'] : '' ?></p>
<form action="addAuthor.php" method="POST">
    <p>
        <label for="authorName">Автор:</label>
        <input name="authorName" value="<?= isset($authorName) ? $authorName : '' ?>" />
        <input type="submit" value="Добави" />
    </p>
</form>

<?php if ($countAuthors > 0) { ?>
    <table>
        <tr>
            <th>Автори</th>
        </tr>
        <?php foreach ($authors as $author_id => $author_name) { ?>
            <tr>
                <td><a href="allAuthorBooks.php?author_id=<?= $author_id ?>"><?= $author_name ?></a> </td>
            </tr>
        <?php } ?>
    </table>
<?php } else {
    ?>
    <p>Няма въведени автори</p>
    <?php
}
include 'includes/footer.php';
