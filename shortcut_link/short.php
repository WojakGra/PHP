<?php
include('db.php');

$link = (isset($_POST['link'])) && !empty($_POST['link']) ? $_POST['link'] : '';
$linkParsed = parse_url($link);
if (!empty($linkParsed['scheme'])) {
    if ("http" !== $linkParsed['scheme'] && "https" !== $linkParsed['scheme']) {
        echo json_encode(['ERROR' => true]);
        $db = null;
        die();
    }
} else {
    $link = 'http://' . $link;
}

$loop = 1;

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

while ($loop === 1) {
    $link_exist = $db->prepare("SELECT link FROM links WHERE link=:link");
    $link_exist->execute(['link' => $link]);
    if (!empty($link_exist->fetch())) {
        $temp = $db->prepare("SELECT short FROM links WHERE link=:link");
        $temp->execute(['link' => $link]);
        $result = $temp->fetch();
        echo json_encode($result);
        $loop = 0;
    } else {
        $short = generateRandomString(random_int(5, 10));
        $short_exist = $db->prepare("SELECT short FROM links WHERE short=:short");
        $short_exist->execute(['short' => $short]);
        if (empty($short_exist->fetch())) {
            $db->prepare("INSERT INTO links (link, short) VALUES (:link, :short)")->execute(['link' => $link, 'short' => $short]);
        }
    }
}

$db = null;
