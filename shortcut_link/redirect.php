<?php
include('db.php');

function redirectToUrl($short)
{
    global $db;
    $short_exist = $db->query("SELECT * FROM links WHERE short='$short'");
    while ($row = $short_exist->fetch_assoc()) {
        if (empty($row['link'])) {
            echo 'Not found';
        } else {
            header('Location: ' . $row['link']);
            die();
        }
    }
}
