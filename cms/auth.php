<?php
function CreateUser($uname, $email, $pword) {
    //TODO NEED TO
    //create salt and properly hash
}

function LogIn($uname, $pword) {
    global $mysqli;

}

function Authed($redirect == null) {
    if (isset($_SESSION["_user_name_"])) {
        return $_SESSION["_user_name_"];
    }
    else {
        //TODO REDIRECT
        return false;
    }
}
?>
