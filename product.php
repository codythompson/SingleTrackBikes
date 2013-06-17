<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$title = "Single Track Bikes - Products";
$cssHrefs = array(
    "/styles/product.css");
$jsSrcs = array(
    "/scripts/product.js");
$navBar = new NavBar(GetNavLinks(), "/bikes.php");
$content = "content/product.php";

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content);
?>
