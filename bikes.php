<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$title = "Bikes we sell";
$cssHrefs = array(
    "/styles/product.css");
$jsSrcs = array(
    "/scripts/product.js");
$navBar = new NavBar(GetNavLinks(), "/bikes.php");
$content = "content/bikes.php";

$footerLinks = getFooterLinks();

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content, $footerLinks);
?>
