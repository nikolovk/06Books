<?php
include 'includes/connection.php';


$data = array();
if (isset($_GET['book_id'])) {
    $sql = 'SELECT ';
    $authorId = (int) $_GET['author_id'];
    $sql = 'SELECT books.book_id, books.book_title, authors.author_name, authors.author_id
            FROM books_authors AS ba
            INNER JOIN books_authors AS bba ON ba.book_id = bba.book_id
            INNER JOIN authors ON bba.author_id = authors.author_id
            INNER JOIN books ON ba.book_id = books.book_id
            WHERE ba.author_id = '. $authorId;
} else {
    $data['hasBook'] = false;
}
$query = mysqli_query($connection, $sql);
if (!$query) {
    echo 'Connection problem';
    echo mysqli_error($connection);
    exit;
}
$books = array();
while ($row = mysqli_fetch_assoc($query)) {
    $books[$row['book_id']]['title'] = $row['book_title'];
    $books[$row['book_id']]['authors'][$row['author_id']] = $row['author_name'];
}
$countBooks = count($books);
include 'includes/header.php';
?>

<h2>Всички книги</h2>
<?php if ($countBooks > 0) { ?>
    <table>
        <tr>
            <th>Книга</th>
            <th>Автори</th>
        </tr>
        <?php foreach ($books as $bookId=>$book) { ?>
            <tr>
                <td><a href="book.php?book_id=<?= $bookId ?>"><?= $book['title'] ?></a> </td>
                <td>
                    <?php foreach ($book['authors'] as $authorId => $authorName) { ?>
                        <a href="index.php?author_id=<?= $authorId ?>"><?= $authorName ?></a> 
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p>Няма книги</p>
    <?php
}
include 'includes/footer.php';