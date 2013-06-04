<?php
require_once("cms/datalayer.php");
require_once("cms/page.php");

$title = "Single Track Bikes - Flagstaff Arizona";
$navLinks = GetNavLinks();
$activeLinkIndex = 2;
$content = "";

MakePage($title, $navLinks, $activeLinkIndex, $content);
?>
