<?php
// TODO - check if user exists before adding to db

require("../cms/dbconn.php");
require("../cms/datalayer.php");
require_once("localvars.php");

session_start();

function generateSalt($numChars = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*";

    $salt = "";
    for ($i = 0; $i < $numChars; $i++) {
        $salt .= $chars{mt_rand(0, strlen($chars) - 1)};
    }
    return $salt;
}

function createUser($uname, $pword, $email, $secQ, $secQA) {
    global $mysqli;

    $salt = generateSalt();
    $hash = crypt($pword, "$2a$10$" . $salt);

    $query = "insert into single_track.user ";
    $query .= "(user_name, user_email, user_sec_q, user_sec_q_a, user_salt, user_hash) ";
    $query .= "values (?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        var_dump($mysqli->error);
        return false;
    }
    $stmt->bind_param("ssssss", $uname, $email, $secQ, $secQA, $salt, $hash);
    $stmt->execute();

    return $mysqli->affected_rows == 1;
}

function logIn($uname, $pword) {
    global $mysqli;

    $query = "select u.user_salt, u.user_hash from single_track.user u ";
    $query .= "where u.user_name = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $rows = fetchRows($stmt);
    if (count($rows) == 1) {
        $row = $rows[0];
        $salt = $row["user_salt"];
        $uHash = $row["user_hash"];

        $hash = crypt($pword, "$2a$10$" . $salt);

        if (strcmp($uHash, $hash) == 0) {
            $_SESSION["_user_name_"] = $uname;
            $_SESSION["_user_ticks_"] = 5;

            return true;
        }
    }

    return false;
}

function logOut() {
    session_destroy();
}

function authed($redirect = true) {
    if (isset($_SESSION["_user_name_"])) {
        return $_SESSION["_user_name_"];
    }
    else {
        if ($redirect) {
            header("location: http://" . ST_LOCATION . "/admin/login.php");
            die();
        }
        else {
            return false;
        }
    }
}
?>
