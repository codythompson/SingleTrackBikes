<?php
require_once("cms/datalayer.php");
require_once("cms/navbar.php");
require_once("cms/page.php");

$productId = 1;

if (!empty($_GET["product_id"])) {
    $productId = intval($_GET["product_id"]);
}

$title = "Single Track Bikes - Products";
$prodInfo = getProductInfo($productId, true);
$metaKeys = null;
if (!empty($prodInfo)) {
    $title = "Flagstaff " . $prodInfo["name"];
    $metaKeys = $title;
}

$cssHrefs = array(
    "/styles/product.css");
$jsSrcs = array(
    "/scripts/product.js");
$navBar = new NavBar(GetNavLinks(), "/bikes.php");
$content = "content/product.php";

$footerLinks = getFooterLinks();

MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content, $footerLinks, $metaKeys);
?>
