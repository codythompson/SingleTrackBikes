<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$title = "Single Track Bikes - Flagstaff Arizona";
$cssHrefs = array();
$jsSrcs = array();
$navBar = new NavBar(GetNavLinks(), 0);
$content = "";

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content);
?>
