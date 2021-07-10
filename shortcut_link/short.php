<?php
/*
    TODO

Oddawać klucz do index
*/
    include('db.php');

    if(isset($_POST['link'])) {
        $link = $_POST['link'];
        generateShortLink($db, $link);
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateShortLink($db, $link) {
        $link_exist = $db->query("SELECT link FROM links WHERE link='$link'");
        if(!empty($link_exist->fetch_assoc())) {
            $temp = $db->query("SELECT * FROM links WHERE link='$link'");
            while($row = $temp->fetch_assoc()) {
                echo 'Link: ' . $row['link'] . '<br>';
                echo 'Klucz: ' . $row['short'];
            }
        }
        else {
            $short = generateRandomString();
            $short_exist = $db->query("SELECT short FROM links WHERE short='$short'");
            if(!empty($short_exist->fetch_assoc())) {
                generateShortLink($db, $link);
            } else {
                $db->query("INSERT INTO links (link, short) VALUES ('$link', '$short')");
            }
        }
    }

    /*function checkIfExist($db, $link) {
        if($db->query("SELECT EXISTS(SELECT short FROM links WHERE link='$link')")) {
            $temp = $db->query("SELECT short FROM links WHERE EXISTS(SELECT link FROM links WHERE link='$link')");
            while($row = $temp->fetch_assoc()) {
                echo $row['short'];
            }
        } else {
            echo 'OK->OK';
            generateShortLink($db, $link);
        }
    }*/

    $db->close();
?>