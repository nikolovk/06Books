<?php
include 'includes/connection.php';


$data = array();
if (isset($_GET['book_id'])) {
    $book_id = (int) $_GET['book_id'];
    $sqlBook = 'SELECT book_title, b.book_id, a.author_name
        FROM books AS b
        INNER JOIN books_authors AS ba ON b.book_id = ba.book_id
        INNER JOIN authors AS a ON a.author_id = ba.author_id
        WHERE b.book_id =' . $book_id;
    $query = mysqli_query($connection, $sqlBook);
    if (!$query) {
        echo 'Connection problem';
        echo mysqli_error($connection);
        exit;
    }
    while ($row = mysqli_fetch_assoc($query)) {
        $data['book']['book_id'] = $row['book_id'];
        $data['book']['title'] = $row['book_title'];
        $data['book']['authors'][] = $row['author_name'];
    }
    if (count($data['book']) > 0) {
        $sqlComments = 'SELECT c.content, c.date, u.name
                        FROM comments AS c
                        INNER JOIN books AS b ON c.book_id = b.book_id
                        INNER JOIN users AS u ON c.user_id = u.id
                        WHERE b.book_id =' . $book_id;
        $commentsQuery = mysqli_query($connection, $sqlComments);
        if (!$commentsQuery) {
            echo 'Connection problem';
            echo mysqli_error($connection);
            exit;
        }
        while ($row = mysqli_fetch_assoc($commentsQuery)) {
            $data['comments']['content'][] = $row['content'];
            $data['comments']['name'][] = $row['name'];
            $data['comments']['date'][] = new DateTime($row['date'],new DateTimeZone('EET'));
        }
    } else {
        $data['hasBook'] = false;
    }
} else {
    $data['hasBook'] = false;
}

include 'includes/header.php';
print_r($data);

include 'includes/footer.php';
