<?php
include "_settings.php";
session_start();

header("Content-Type: text/html; charset=utf-8");
header("Content-Security-Policy: frame-ancestors none");

$lang = "sp";
$content = array("common" => simplexml_load_file($settings["paths"]["langs"]."sp/_global.xml"));
?>
<!DOCTYPE html>
<html>
<head>
    <?php require $settings["paths"]["layouts"]."meta.php"; ?>
</head>
<body>

<?php
if(empty($_SESSION["characters_admin"]) || $_SESSION["characters_admin"] != "45hy(9oKjhG6y-098uJHGGht;l09iHjk") {
    echo "<form action='files/php/controllers/admin.php' method='post'>
                <input type='hidden' name='action' value='login' />
                <input type='hidden' name='p' />
          </form>
          <script>
            q = prompt('Password', '');
            if(q) {
                document.forms[0].p.value = q;
                document.forms[0].submit();
            }
            else window.location = 'about:blank';
          </script>";
}
else {
    $survey = Helper::getData($connection, array("table" => "gn_survey", "fields" => array("COUNT(*) as total")));
    $contact = Helper::getData($connection, array("table" => "gn_contact", "fields" => array("COUNT(*) as total")));
    $facebook = number_format((int)file_get_contents("files/inc/facebook.inc"));
    $google = number_format((int)file_get_contents("files/inc/google.inc"));
    $twitter = number_format((int)file_get_contents("files/inc/twitter.inc"));
    echo "
            REAL VISITORS: ".number_format((int)file_get_contents("files/inc/visitors.inc") - (int)$settings["fake_data"]["visitors"])."<br/>
            REAL CHARACTERS: ".number_format((int)file_get_contents("files/inc/characters.inc") - (int)$settings["fake_data"]["characters"])."<br/>
            REAL EXPORTS: ".number_format((int)file_get_contents("files/inc/exports.inc") - (int)$settings["fake_data"]["exports"])."<hr/>
            FACEBOOK SHARES: ".$facebook."<br/>
            GOOGLE SHARES: ".$google."<br/>
            TWITTER SHARES: ".$twitter."<hr/>
            GENERAL SHARES: ".($facebook + $google + $twitter)."<hr/>
            SURVEYS TAKEN: ".number_format($survey[0]["total"] - $settings["fake_data"]["votes"])."<br/>
            MESSAGES RECEIVED: ".number_format($contact[0]["total"])."<hr/>
        ";
}
?>

</body>
</html>
