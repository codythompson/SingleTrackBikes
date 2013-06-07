<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$title = "Single Track Bikes - Flagstaff Arizona";
$cssHrefs = array(
    "/styles/st-carousel.css");
$jsSrcs = array();
$navBar = new NavBar(GetNavLinks(), 0);
$content = "content/home.php";

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content);
?>
