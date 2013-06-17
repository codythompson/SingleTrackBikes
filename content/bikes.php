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

if ($prodInfo["product_style_id"] == ST_PRODUCT_STYLE_CAROUSEL) {
?>
<script type="text/javascript">
$(document).ready( function () {
    $('#bikes-info').carousel();
});
</script>
<?php
}

$prodObj = new Product("bikes-info", $prodInfo);

$prodObj->writeElement();
?>
