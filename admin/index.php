<?php
require("../cms/auth.php");

$loggingIn = (isset($_POST["login_form"]) && $_POST["login_form"] == "true");
$logInSuccess = false;

if ($loggingIn) {
    $loggingIN = true;

    if (isset($_POST["uname"]) && isset($_POST["pword"])) {
        $uname = $_POST["uname"];
        $pword = $_POST["pword"];

        $logInSuccess = logIn($uname, $pword);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>ADMIN SECTION</title>

    <link rel="styleshee" href="/styles/bootstrap.css" type="text/css" />
</head>
<body>
<?php

// LOG IN FORM
if (!$loggingIn) {
?>
<form action="index.php" method="post">
user name: <input type="text" name="uname">
password: <input type="password" name="pword">
<input type="hidden" name="login_form" value="true">
<input type="submit">
</form>
<?php
}
else {
    if ($logInSuccess) {
?>
<div class="alert alert-success">
    <strong>Success!</strong> You are logged in!
</div>
<?php
    }
    else {
?>
<div class="alert alert-error">
    <strong>Failure!</strong> You are not logged in!
</div>
<?php
    }
}
?>
</body>
</html>
