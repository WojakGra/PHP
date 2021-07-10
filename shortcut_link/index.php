<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Short your link</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
</head>
<body>
    <div class="container">
        <form method="POST" action="short.php" name="short_link">
            <label for="link">Wprowadź link do skrócenia</label>
            <input type="text" name="link" id="link">
            <button type="submit">Skróć</button>
        </form>
        <p id="output"></p>
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