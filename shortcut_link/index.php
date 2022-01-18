<?php
include("db.php");
$url = isset($_SERVER["QUERY_STRING"]) && !empty($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : "";
if ($url !== "") {
  $url_explode = explode("/", $url);
  if (preg_match("/^[A-Za-z0-9]+$/i", $url_explode[0]) === 1) {
    $short_exist = $db->prepare("SELECT link FROM links WHERE short=:short");
    $short_exist->execute(['short' => $url_explode[0]]);
    while ($row = $short_exist->fetch()) {
      if (!empty($row["link"])) {
        header("Location: " . $row["link"]);
        $db = null;
        die();
      }
    }
  }
}
$db = null;
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Skróć swój link</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="./img/svg/logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="./css/style.min.css">
  <script src="js/jquery.js"></script>
  <script src="js/jquery-validate.js"></script>
</head>

<body>
  <div class="layer"></div>
  <a class="skip-link sr-only" href="#skip-target">Skip to content</a>
  <div class="page-flex">
    <div class="main-wrapper">
      <nav class="main-nav--bg">
        <div class="container main-nav">
          <div class="main-nav-end">
          </div>
        </div>
      </nav>
      <main class="main users chart-page" id="skip-target">
        <div class="container">
          <h2 class="main-title">Skróć link</h2>
          <div class="row stat-cards">
            <div class="col-md-6">
              <form method="POST" class="form" name="short_link">
                <label class="form-label-wrapper">
                  <p class="form-label">Wprowadź link do skrócenia</p>
                  <input type="text" class="form-input" name="url" id="url">
                </label>
                <button type="submit" class="form-btn primary-default-btn transparent-btn">Skróć</button>
              </form>
            </div>
            <div class="col-md-6">
              <div id="link-output">
                <a id="output" class="link-info"></a>
              </div>
            </div>
          </div>
        </div>
      </main>
      <footer class="footer">
        <div class="container footer--flex">
          <div class="footer-start">
            <p><?php echo Date("Y"); ?> © Wojak</p>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!-- Icons library -->
  <script src="plugins/feather.min.js"></script>
  <script>
    $(document).ready(function() {
      $.validator.addMethod("validUrl", function(value, element) {
        var url = $.validator.methods.url.bind(this);
        return url(value, element) || url("http://" + value, element);
      }, "Wpisz prawidłowy link");

      $("form[name='short_link']").validate({
        rules: {
          url: {
            required: true,
            validUrl: true
          }
        },
        messages: {
          url: "Wpisz prawidłowy link"
        },
        submitHandler: function(form) {
          $.post('short.php', {
            link: form["url"].value,
          }, function(result) {
            if (result.ERROR) {
              $("#output").attr("href", "/");
              $("#link-output").attr("style", "margin-top:1rem;text-align:center;background-color:#fff;border-radius:10px;padding:40px 40px 56px;");
              $("#output").text("Podaj prawidłowy link!");
            } else {
              $("#output").attr("href", result["short"]);
              $("#link-output").attr("style", "margin-top:1rem;text-align:center;background-color:#fff;border-radius:10px;padding:40px 40px 56px;");
              $("#output").text("<?php echo $_SERVER['SERVER_NAME']; ?>/" + result["short"]);
            }
          }, "json");
        }
      });
    });
  </script>
</body>

</html>