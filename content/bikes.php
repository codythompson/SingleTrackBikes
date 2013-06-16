<?php
require_once("cms/datalayer.php");
require_once("cms/product.php");

$prodInfo = getProductInfo(ST_PRODUCT_ID_BIKES, true);

if (empty($prodInfo)) {
    $prodInfo = getProductInfo(1, true);
    if (empty($prodInfo)) {
?>
<div class="alert alert-error">
<strong>Woops</strong>
This page seems to be broken.
</div>
<?php
    }
}

$prodObj = new Product("bikes-info", $prodInfo);

$prodObj->writeElement();
?>
