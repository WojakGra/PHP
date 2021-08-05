<?php
include('db.php');

$link = (isset($_POST['data']) && !empty($_POST['data'])) ? $_POST['data'] : '';
$loop = 1;

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

while($loop == 1){
    $link_exist = $db->query("SELECT link FROM links WHERE link='$link'");
    if (!empty($link_exist->fetch_assoc())) {
        $temp = $db->query("SELECT * FROM links WHERE link='$link'");
        $result = $temp->fetch_assoc();
        echo json_encode($result);
        $loop = 0;
    } else {
        $short = generateRandomString();
        $short_exist = $db->query("SELECT short FROM links WHERE short='$short'");
        if (empty($short_exist->fetch_assoc())) {
            $db->query("INSERT INTO links (link, short) VALUES ('$link', '$short')");
        }
    }
}

$db->close();
