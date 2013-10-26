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
    if (isset($data['book']) && count($data['book']) > 0) {
        if ($_POST && isset($_SESSION['isLogged']) && $_SESSION['isLogged'] === true) {
            $trimedComment = htmlspecialchars(trim($_POST['comment']));
            $content = mysqli_real_escape_string($connection, $trimedComment);
            if (empty($content) || mb_strlen($content, 'utf8') < 10) {
                $data['errors']['content'] = 'Коментарът трябва да бъде поне 10 символа!';
                $data['newComment'] = $content;
            } else {
                $sqlInsert = 'INSERT INTO comments (content,user_id,book_id,date) 
                            VALUES ("' . $content . '",' . $_SESSION['id'] . ',' . $book_id . ',
                           "' . (new DateTime('NOW', new DateTimeZone('EET')))->format('Y-m-d H:i:s') . '")';
                $query = mysqli_query($connection, $sqlInsert);
                if (!$query) {
                    echo 'Connection problem';
                    echo mysqli_error($connection);
                    exit;
                }
            }
        }


        $sqlComments = 'SELECT c.content, c.date, u.name, u.id
                        FROM comments AS c
                        INNER JOIN books AS b ON c.book_id = b.book_id
                        INNER JOIN users AS u ON c.user_id = u.id
                        WHERE b.book_id =' . $book_id .
                ' ORDER BY c.date';
        $commentsQuery = mysqli_query($connection, $sqlComments);
        if (!$commentsQuery) {
            echo 'Connection problem';
            echo mysqli_error($connection);
            exit;
        }
        while ($row = mysqli_fetch_assoc($commentsQuery)) {
            $data['comments']['content'][] = $row['content'];
            $data['comments']['name'][] = $row['name'];
            $data['comments']['id'][] = $row['id'];
            $data['comments']['date'][] = new DateTime($row['date'], new DateTimeZone('EET'));
        }
    } else {
        $data['errors']['hasBook'] = false;
    }
} else {
    $data['errors']['hasBook'] = false;
}
include 'includes/header.php';
if (isset($data['errors']['hasBook']) && $data['errors']['hasBook'] === false) {
    echo '<p class="error">Няма такава книга!</p>';
}
if (isset($data['book']) && count($data['book']) > 0) {
    ?>
    <h2><?= $data['book']['title'] ?></h2>
    <div>
        <h3>Автори</h3>
        <ul>
            <?php
            foreach ($data['book']['authors'] as $authorName) {
                echo '<li>' . $authorName . '</li>';
            }
            ?>
        </ul>
    </div>
    <div>
        <h3>Коментари</h3>
        <?php
        if (isset($data['comments'])) {
            $countComments = count($data['comments']['content']);
            for ($index = 0; $index < $countComments; $index++) {
                ?>
                <div class="row">
                    <p class="content"><?= $data['comments']['content'][$index] ?></p>
                    <p><em>
                            <strong>Автор:</strong> <a href="comments.php?user_id=<?= $data['comments']['id'][$index] ?>" /><?= $data['comments']['name'][$index] ?></a>
                            <strong>Дата:</strong> <?= $data['comments']['date'][$index]->format('Y-m-d H:i') ?>
                        </em></p>
                    <hr />
                </div>
                <?php
            }
        } else {
            echo '<div>Няма коментари</div>';
        }
        if (isset($_SESSION['isLogged']) && $_SESSION['isLogged'] === true) {
            ?>
            <div>
                <h3>Нов Коментар</h3>
                <form action="" method="POST">
                    <p>
                        <label for="comment"></label>
                        <textarea name="comment" class="form-control" rows="5"><?= (isset($data['newComment']) ? $data['newComment'] : '') ?></textarea>
                        <span class="text-danger"><?= (isset($data['errors']['content']) ? $data['errors']['content'] : '') ?></span>
                    </p>
                    <button type="submit" class="btn btn-default">Добави</button>
                </form>
            </div>
        <?php } ?>
    </div>
    <?php
}

include 'includes/footer.php';