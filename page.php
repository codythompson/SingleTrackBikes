<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$pcId = 0;
if (isset($_GET["page_content_id"])) {
    $pcId = $_GET["page_content_id"];
}
$pageInfo = getPageContent($pcId);

$title = $pageInfo["page_title"];
$cssHrefs = array(
    "/styles/st-carousel.css");
$jsSrcs = array();
$navBar = new NavBar(GetNavLinks(), "/");
$content = "content/page.php";

$footerLinks = getFooterLinks();

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content, $footerLinks);
?>
