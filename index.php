<?php
define("ST_MISC_KEYWORDS_NAME", "Keywords");
define("ST_MISC_DESCRIPTION_NAME", "Description");

require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$metaKeys = getMiscText(ST_MISC_KEYWORDS_NAME);
$metaDescr = getMiscText(ST_MISC_DESCRIPTION_NAME);

$title = "Single Track Bikes - Flagstaff Arizona";
$cssHrefs = array(
    "/styles/st-carousel.css");
$jsSrcs = array();
$navBar = new NavBar(GetNavLinks(), "/");
$content = "content/home.php";

$footerLinks = getFooterLinks();

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content, $footerLinks, $metaKeys,
$metaDescr);
?>
