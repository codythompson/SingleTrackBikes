<?php
require_once("cms/datalayer.php");

$rows = getProductInfo(6, 2);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
var_dump($rows);
?>
</body>
</html>
