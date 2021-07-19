<?php
/*
    Naprawić to gówno
*/
print_r($_SERVER['ORIG_PATH_INFO']);
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
    $url = explode('/', $_SERVER['QUERY_STRING']);

    require_once('redirect.php');
    redirectToUrl(implode($url));
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
        <form method="POST" action="short.php" name="short_link">
            <label for="link">Wprowadź link do skrócenia</label>
            <input type="text" name="link" id="link">
            <button type="submit">Skróć</button>
        </form>
        <?php
            if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
                echo "<p id='output' href='" . $_SERVER['REQUEST_SCHEME'] . $_SERVER['SERVER_NAME'] . str_replace("index?", "", $_SERVER['REQUEST_URI']) . "'>". $_SERVER['REQUEST_SCHEME'] . $_SERVER['SERVER_NAME'] . str_replace("index?", "", $_SERVER['REQUEST_URI']) . "</p>";
            }
        ?>
    </div>
    <script>
        $(document).ready(function() {
            $.validator.addMethod('validUrl', function(value, element) {
                var url = $.validator.methods.url.bind(this);
                return url(value, element) || url('http://' + value, element);
            }, 'Please enter a valid URL');

            $("form[name='short_link']").validate({
                rules: {
                    link: {
                        required: true,
                        validUrl: true
                    }
                },
                messages: {
                    link: 'Please enter a valid URL'
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>