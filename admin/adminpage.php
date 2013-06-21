<?php
require_once("auth.php");

function makePage($contentFile, $scriptSrcs = array()) {

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

<script type="text/javascript" src="/scripts/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="/scripts/bootstrap.js"></script>

<?php
    foreach($scriptSrcs as $src) {
?>
    <script type="text/javascript" src="<?php echo $src ?>"></script>
<?php
    }
?>

</head>

<body>

<div>
    <ul class="nav nav-pills">
        <li>
            <form action="login.php" method="post">
                <input type="hidden" name="form_type" value="logout" />
                <input class="btn btn-info" type="submit" value="logout" />
            </form>
        </li>
    </ul>
</div>

<?php
    require($contentFile);
?>

</body>
</html>
<?php
}
?>
