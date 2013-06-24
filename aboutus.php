<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$title = "Single Track Bikes - About Us";
$cssHrefs = array(
    "/styles/st-carousel.css");
$jsSrcs = array();
$navBar = new NavBar(GetNavLinks(), "/aboutus.php");
$content = "content/aboutus.php";

$footerLinks = getFooterLinks();

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content, $footerLinks);
?>
