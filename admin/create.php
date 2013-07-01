<?php
// THIS PAGE SHOULD NEVER BE UPLOADED TO A LIVE SERVER!!!!!!!!!!!!
// THIS PAGE SHOULD NEVER BE UPLOADED TO A LIVE SERVER!!!!!!!!!!!!
// THIS PAGE SHOULD NEVER BE UPLOADED TO A LIVE SERVER!!!!!!!!!!!!
// THIS PAGE SHOULD NEVER BE UPLOADED TO A LIVE SERVER!!!!!!!!!!!!
// THIS PAGE SHOULD NEVER BE UPLOADED TO A LIVE SERVER!!!!!!!!!!!!

require_once("auth.php");

$succMess = null;
$errMess = array();

if (isset($_POST["form_type"]) && $_POST["form_type"] === "do_not_reset") {
    //
    $code = "FLGGLF";

    $uname = $_POST["uname"];
    $pword1 = $_POST["pword1"];
    $pword2 = $_POST["pword2"];
    $scode = $_POST["scode"];

    if (empty($scode)) {
        $errMess[] = "You must provide the special code.";
    }
    else if ($scode !== $code) {
        $errMess[] = "you provided an incorrect special code.";
    }
    else if (empty($uname)) {
        $errMess[] = "You must provide a username.";
    }
    else if (empty($pword1)) {
        $errMess[] = "You must provide a password.";
    }
    else if (empty($pword2)) {
        $errMess[] = "You must provide re-type the password.";
    }
    else if ($pword1 !== $pword2) {
        $errMess[] = "The two passwords must match.";
    }
    else {
        $result = createUser($uname, $pword1, "blah", "blah", "blah");
        if ($result === true) {
            $succMess = "Successfully created user <strong>$uname</strong> " .
                "<a href='index.php' class='btn btn-info'>Login</a>";
        }
        else {
            $errMess[] = "An error occurred.";
        }
    }
    unset($code);
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/styles/bootstrap.css" type="text/css" />
</head>
<body>

<?php
foreach($errMess as $mess) {
?>
    <div class="alert alert-danger"><?php echo $mess; ?></div>
<?php
}

if (!empty($succMess)) {
?>
    <div class="alert alert-success"><?php echo $succMess; ?></div>
<?php
}
?>

<form action="create.php" method="POST">
<input type="hidden" name="form_type" value="do_not_reset" />
SPECIAL CODE:
<input type="text" name="scode" />
<br/>

username:
<input type="text" name="uname" />

<br/>
password: 
<input type="password" name="pword1" />

<br/>
password (re-type): 
<input type="password" name="pword2" />

<input type="submit" value="create user" class="btn btn-success" />

</form>
<body>
</html>
