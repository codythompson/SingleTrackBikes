<?php
require_once("auth.php");

/*
 * FUNCTIONS
 */
function makeLogInForm($eMess = null) {
    if (!empty($eMess)) {
?>
<div class="alert alert-error">
<?php
        echo $eMess;
?>
</div>
<?php
    }

?>
<form action="login.php" method="post">
user name: <input type="text" name="uname" />
password: <input type="password" name="pword" />
<input type="hidden" name="form_type" value="login" />
<input type="submit" value="Log In" />
</form>
<?php
} //end makeLogInForm

function makeLogOutForm($sMess = null) {
    if (!empty($sMess)) {
?>
<div class="alert alert-success">
<?php
        echo $sMess;
    }
?>
</div>
<form action="login.php" method="post">
<input type="hidden" name="form_type" value="logout" />
<input type="submit" value="logout" />
</form>
<?php
}
//end makeLogOutForm

/*
 * Form Logic
 */
if (isset($_POST["form_type"]) &&
    ($_POST["form_type"] === "login" || $_POST["form_type"] === "logout")) {

    if ($_POST["form_type"] === "login") {
        $uname = "";
        if (isset($_POST["uname"])) {
            $uname = $_POST["uname"];
        }
    
        $pword = "";
        if (isset($_POST["pword"])) {
            $pword = $_POST["pword"];
        }
    
        $loginSuccess = logIn($uname, $pword);
        if ($loginSuccess) {
            makeLogOutForm("<strong>Logged In</strong>");
        }
        else {
            logOut();
            makeLogInForm("<strong>Failed to Log In</strong> please try again");
        }
    }
    else {
        session_destroy();
        makeLogInForm();
    }
}
else {
    $isAuthed = authed(false);
    
    if ($isAuthed) {
        makeLogOutForm();
    }
    else {
        makeLogInForm();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>singletrack admin area login</title>

<link rel="stylesheet" href="/styles/bootstrap.css" />
<script type="text/javascript" src="/scripts/bootstrap.js"></script>
</head>
<body>

</body>
</html>
