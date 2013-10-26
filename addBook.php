<?php
include 'includes/connection.php';
$errors = array();
$messages = array();
$selectedAuthors = array();
$bookTitle = '';
if ($_POST) {
    $bookTitle = mysqli_escape_string($connection, htmlspecialchars(trim($_POST['bookTitle'])));
    $selectedAuthors = isset($_POST['authors']) ? $_POST['authors'] : array();
    if (mb_strlen($bookTitle, 'UTF-8') >= 3 && count($selectedAuthors) > 0) {
        $result = mysqli_query($connection, 'INSERT INTO books (book_title) VALUES ("' . $bookTitle . '")');
        $bookId = mysqli_insert_id($connection);
        $stmt = mysqli_prepare($connection, 'INSERT INTO books_authors (book_id,author_id) VALUES (?,?)');
        foreach ($selectedAuthors as $authorId) {
            mysqli_stmt_bind_param($stmt, 'ii', $bookId, $authorId);
            mysqli_stmt_execute($stmt);
        }
        if ($result) {
            $messages['success'] = 'Книгата беше добавен успешно!';
            $bookTitle = '';
            $selectedAuthors = array();
        } else {
            $errors['record'] = 'Неуспешен запис!';
        }
    } else {
        $errors['length'] = 'Името трябва да бъде поне 3 символа и трябва да изберете автор!';
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
include 'includes/header.php';
?>

<h2>Добави книга</h2>
<p class="text-success"><?= isset($messages['success']) ? $messages['success'] : '' ?></p>
<p class="text-danger"><?= isset($errors['record']) ? $errors['record'] : '' ?></p>
<p class="text-danger"><?= isset($errors['length']) ? $errors['length'] : '' ?></p>
<form action="addBook.php" method="POST" role="form">
    <div class="form-group">
        <label for="bookTitle">Заглавие:</label>
        <input name="bookTitle" id="bookTitle" value="<?= isset($bookTitle) ? $bookTitle : '' ?>" class="form-control" />
    </div>
    <div class="form-group">
        <select name="authors[]" multiple="multiple" class="form-control">
            <?php foreach ($authors as $key => $value) { ?>
                <option value="<?= $key ?>" <?= in_array($key, $selectedAuthors) ? 'selected=selected' : '' ?>>
                    <?= $value ?></option>
            <?php } ?>            
        </select>
    </div>
   <div class="form-group">
        <button type="submit" class="btn btn-default">Добави</button>
   </div>
</form>
<?php
include 'includes/footer.php';
