<?php
require_once("auth.php");

function makePage($contentFile) {

    $authed = authed();
    if ($authed === false) {
        return;
    }
?>
<!DOCTYPE html>
<html>
<head>
<title>SingleTrack admin area</title>

<link rel="stylesheet" href="/styles/bootstrap.css" type="text/css" />
<script type="text/javascript" src="/scripts/bootstrap.js"></script>

</head>

<body>

<?php
    require($contentFile);
?>

</body>
</html>
<?php
}
?>
