<?php
require_once("auth.php");

function makePage($contentFile, $cssHrefs = array(), $scriptSrcs = array()) {

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
<link rel="stylesheet" href="/styles/admin.css" type="text/css" />

<script type="text/javascript" src="/scripts/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="/scripts/bootstrap.js"></script>
<script type="text/javascript" src="/scripts/admin.js"></script>
<script type="text/javascript" src="/scripts/nicEdit.js"></script>

<?php
    foreach($scriptSrcs as $src) {
?>
    <script type="text/javascript" src="<?php echo $src ?>"></script>
<?php
    }
?>

<?php
    foreach($cssHrefs as $href) {
?>
    <link rel="stylesheet" type="text/css" href="<?php echo $href ?>" />
<?php
    }
?>

</head>

<body>

<div>
    <form action="login.php" method="post">
        <input type="hidden" name="form_type" value="logout" />
        <input class="btn btn-danger" type="submit" value="logout" />
    </form>
    <a href="/admin" class="btn btn-info">Admin Home</a>
</div>

<?php
    require($contentFile);
?>

</body>
</html>
<?php
}
?>
