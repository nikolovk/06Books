<?php
session_start();

$connection = mysqli_connect('localhost', 'php', 'php', 'books');
mysqli_set_charset($connection, 'utf8');
if (!$connection) {
    echo 'No connection with database';
    exit;
}
