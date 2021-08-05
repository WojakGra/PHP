<?php
include('db.php');
$local = $_SERVER['HTTP_REFERER'];
$url = (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
if ($url !== ''){
    $url_explode = explode('/', $url);

    $short_exist = $db->query("SELECT link FROM links WHERE short='$url_explode[0]'");
        while ($row = $short_exist->fetch_assoc()) {
            if (!empty($row['link'])) {
                header('Location: ' . $row['link']);
                die();
            }
        }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Short your link</title>
    <script src="js/jquery.js"></script>
    <script src="js/jquery-validate.js"></script>
</head>

<body>
    <div class="container">
        <form method="POST" name="short_link">
            <label for="url">Wprowadź link do skrócenia</label>
            <input type="text" name="url" id="url">
            <button type="submit">Skróć</button>
        </form>
        <a id="output"></a>
    </div>
    <script>
        $(document).ready(function() {
            $.validator.addMethod('validUrl', function(value, element) {
                var url = $.validator.methods.url.bind(this);
                return url(value, element) || url('http://' + value, element);
            }, 'Please enter a valid URL');

            $("form[name='short_link']").validate({
                rules: {
                    url: {
                        required: true,
                        validUrl: true
                    }
                },
                messages: {
                    url: 'Please enter a valid URL'
                },
                submitHandler: function(form) {
                    console.log(form['url'].value);
                    $.post('short.php', {data: form['url'].value}, function(result) {
                        console.log(result);
                        $('#output').attr('href','<?php echo $local;?>'+result['short']);
                        $('#output').append('<?php echo $local;?>'+result['short']);
                    }, 'json');
                }
            });
        });
    </script>
</body>

</html>