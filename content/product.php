<?php
require_once("cms/datalayer.php");
require_once("cms/product.php");

$productId = 1;

if (!empty($_GET["product_id"])) {
    $productId = intval($_GET["product_id"]);
}

$prodInfo = getProductInfo($productId, true);
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
    $('#product-info').carousel();
});
</script>
<?php
}

$prodObj = new Product("product-info", $prodInfo);

$prodObj->writeElement();
?>
<!--
<div class="container-fluid">

<div class="row-fluid">
    <div class="span4 product-container">
        <div class="product-container-inner">
            <h3>Trek Road Bikes</h3>
            <div class="product-image">
                <img src="/images/trek-road-bikes-square.png" alt="Trek Road Bikes" />
            </div>
            <p>Trek Road Bikes description goes here. blah blah blah blah</p>
        </div>
    </div>

    <div class="span4 product-container">
        <div class="product-container-inner">
            <h3>Trek Road Bikes</h3>
            <div class="product-image">
                <img src="/images/trek-road-bikes-square.png" alt="Trek Road Bikes" />
            </div>
            <p>Trek Road Bikes description goes here. blah blah blah blah</p>
        </div>
    </div>

    <div class="span4 product-container">
        <div class="product-container-inner">
            <h3>Trek Road Bikes</h3>
            <div class="product-image">
                <img src="/images/trek-road-bikes-square.png" alt="Trek Road Bikes" />
            </div>
            <p>Trek Road Bikes description goes here. blah blah blah blah</p>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span4 product-container">
        <div class="product-container-inner">
            <h3>Trek Road Bikes</h3>
            <div class="product-image">
                <img src="/images/trek-road-bikes-square.png" alt="Trek Road Bikes" />
            </div>
            <p>Trek Road Bikes description goes here. blah blah blah blah</p>
        </div>
    </div>

    <div class="span4 product-container">
        <div class="product-container-inner">
            <h3>Trek Road Bikes</h3>
            <div class="product-image">
                <img src="/images/trek-road-bikes-square.png" alt="Trek Road Bikes" />
            </div>
            <p>Trek Road Bikes description goes here. blah blah blah blah</p>
        </div>
    </div>

    <div class="span4 product-container">
        <div class="product-container-inner">
            <h3>Trek Road Bikes</h3>
            <div class="product-image">
                <img src="/images/trek-road-bikes-square.png" alt="Trek Road Bikes" />
            </div>
            <p>Trek Road Bikes description goes here. blah blah blah blah</p>
        </div>
    </div>
</div>
</div>
-->
