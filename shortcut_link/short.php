<?php
include('db.php');

if (isset($_POST['link']) && !empty($_POST['link'])) {
    $link = $_POST['link'];
    ShortLink($link);
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function ShortLink($link)
{
    global $db;
    $link_exist = $db->query("SELECT link FROM links WHERE link='$link'");
    if (!empty($link_exist->fetch_assoc())) {
        $temp = $db->query("SELECT * FROM links WHERE link='$link'");
        while ($row = $temp->fetch_assoc()) {
            header("Location: index?" . $row['short']);
        }
    } else {
        $short = generateRandomString();
        $short_exist = $db->query("SELECT short FROM links WHERE short='$short'");
        if (!empty($short_exist->fetch_assoc())) {
            ShortLink($link);
        } else {
            $db->query("INSERT INTO links (link, short) VALUES ('$link', '$short')");
        }
    }
}

$db->close();
