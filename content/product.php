<?php
require_once("cms/datalayer.php");
require_once("cms/product.php");

$parentId = 0;

if (!empty($_GET["parent_id"])) {
    $parentId = intval($_GET["parent_id"]);
}

$prodInfo = getProductInfo($parentId);
//var_dump($prodInfo);
$prodObj = new Product("product_info", $prodInfo);

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
