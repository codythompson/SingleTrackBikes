<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$title = "Bikes we sell";
$cssHrefs = array(
    "/styles/bikes.css");
$jsSrcs = array();
$navBar = new NavBar(GetNavLinks(), 1);
$content = "content/bikes.php";

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content);
?>
