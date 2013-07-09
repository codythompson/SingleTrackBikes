<?php
require("../cms/dbconn.php");
require_once("../cms/datalayer.php");
require_once("auth.php");

$succMess = null;
$errMess = array();

if (isset($_POST["form_type"]) && $_POST["form_type"] === "pwordreset") {
    $pword1 = $_POST["pword1"];
    $pword2 = $_POST["pword2"];

    if (empty($pword1)) {
        $errMess[] = "You must provide a new password";
    }
    else if (empty($pword2)) {
        $errMess[] = "You must provide a new password";
    }
    else if ($pword1 !== $pword2) {
        $errMess[] = "The passwords must match";
    }
    else {
        $salt = generateSalt();
        $hash = crypt($pword1, "$2a$10$" . $salt);
        $user = $_SESSION["_user_name_"];

        $query = "update singletrack.user " .
            "set user_salt = ?, " .
            "user_hash = ? ".
            "where user_name = ?";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
        }
        $stmt->bind_param("sss", $salt, $hash, $user);
        $stmt->execute();
        if ($stmt->affected_rows == 1) {
            $succMess = "Successfully updated password.";
        }
        else {
            $errMess[] = "A database error occurred while updating your password<br/>" .
                "This may have occurred if you entered the same password as before.";
        }
    }
}

?>
<h1>Reset the admin password here</h1>

<?php
if (!empty($succMess)) {
?>
    <div class="alert alert-success"><?php echo $succMess; ?></div>
<?php
}
if (!empty($errMess)) {
    foreach($errMess as $mess) {
?>
    <div class="alert alert-danger"><?php echo $mess; ?></div>
<?php
    }
}
?>

<form action="reset.php" method="POST">
    <input type="hidden" name="form_type" value="pwordreset" />
    <label for="pword1">New Password</label>
    <input type="password" name="pword1" id="pword1" />
    <label for="pword2">Again</label>
    <input type="password" name="pword2" id="pword2" />
    <br/>
    <input type="submit" value="Reset Password" class="btn btn-success" />
</form>
