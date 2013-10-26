<?php
include 'includes/connection.php';


$data = array();
if (isset($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id'];
    $sql = 'SELECT c.content, c.date, b.book_title, b.book_id, u.name
            FROM comments AS c
            INNER JOIN books AS b ON c.book_id = b.book_id
            INNER JOIN users AS u ON c.user_id = u.id
            WHERE u.id =' . $user_id;
    $query = mysqli_query($connection, $sql);
    if (!$query) {
        echo 'Connection problem';
        echo mysqli_error($connection);
        exit;
    }
    while ($row = mysqli_fetch_assoc($query)) {
        $data['name'] = $row['name'];
        $data['comments']['content'][] = $row['content'];
        $data['comments']['date'][] = new DateTime($row['date'], new DateTimeZone('EET'));
        $data['comments']['book_title'][] = $row['book_title'];
        $data['comments']['book_id'][] = $row['book_id'];
    }
} else {
    $data['errors']['hasComments'] = false;
}
include 'includes/header.php';
if (isset($data['errors']['hasBook']) && $data['errors']['hasBook'] === false) {
    echo '<p class="error">Няма коментари!</p>';
}
if (isset($data['comments']) && count($data['comments']) > 0) {
    ?>
<h2>Всички коментари на <strong><?= $data['name'] ?></strong></h2>
    <div>
        <h3>Коментари</h3>
        <?php
        $countComments = count($data['comments']['content']);
        for ($index = 0; $index < $countComments; $index++) {
            ?>
            <div class="comment">
                <p class="content"><?= $data['comments']['content'][$index] ?></p>
                <p><em>
                    <strong>Книга:</strong> <a href="book.php?book_id=<?= $data['comments']['book_id'][$index] ?>" /><?= $data['comments']['book_title'][$index] ?></a>
                    <strong>Дата:</strong> <?= $data['comments']['date'][$index]->format('Y-m-d H:i') ?>
                </em></p>
                <hr />
            </div>
            <?php             
        }
        ?>

    </div>
    <?php
}

include 'includes/footer.php';