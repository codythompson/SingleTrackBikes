<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$title = "Single Track Bikes - Flagstaff Arizona";
$navBar = new NavBar(GetNavLinks(), 2);
$content = "";

MakePage($title, $navBar, $content);
?>
