<?php
require_once("../cms/datalayer.php");

$pId = 1;
if (isset($_GET["product_id"]) && !empty($_GET["product_id"])) {
    $pId = intval($_GET["product_id"]);
}

$pInfo = getProductInfo($pId, true);

$pName = $pInfo["name"];

$pDescr = "";
if (!empty($pInfo["descr"])) {
    $pDescr = $pInfo["descr"];
}

$pLDescr = "";
if (!empty($pInfo["long_descr"])) {
    $pLDescr = $pInfo["long_descr"];
}

$pOffsite = "";
if (!empty($pInfo["offsite_url"])) {
    $pOffsite = $pInfo["offsite_url"];
}

$pOffsiteText = "";
if (!empty($pInfo["offsite_url_text"])) {
    $pOffsiteText = $pInfo["offsite_url_text"];
}

$imgUrl = "";
if (!empty($pInfo["image_url"])) {
    $imgUrl = $pInfo["image_url"];
}

$bgUrl = "";
if (!empty($pInfo["background_image_url"])) {
    $bgUrl = $pInfo["background_image_url"];
}

?>

<h1>Edit Product Pages</h1>

<div class="st-product">
<?php
if (isset($pInfo["parent_product_id"]) && !empty($pInfo["parent_product_id"])) {
?>
    <button type="button" class="btn btn-danger">
        <i class="icon-trash icon-white"></i>
    </button>
<?php
}
else {
?>
    <button type="button" class="btn disabled">
        <i class="icon-trash icon-white"></i>
    </button>
<?php
}
?>

    <button type="button" class="btn btn-warning"
        onmouseup="toggleContainer('st-product-edit')">
        <i class="icon-pencil icon-white"></i>
    </button>

    <span class="alert alert-info">
        Category Name: <strong><?php echo $pName; ?></strong>
    </span>

<!-- edit area -->
    <div id="st-product-edit" class="st-content-edit well">
    <form action="product.php" method="POST">
        <input type="hidden" name="form_type" value="product_edit" />
        <input type="hidden" name="item_id" value="<?php echo $pId; ?>" />

        <label for="st-product-title">Product/Category Title</label>
        <input type="text" name="item_title" value="<?php echo $pName; ?>"
            id="st-product-title" />

        <label for="st-product-descr">Product/Category Short Description</label>
        <textarea name="item_descr" id="st-product-descr" rows="5"><?php echo $pLDescr; ?></textarea>

        <label for="st-product-long-descr">
            Product/Category Long Description (If you don't have one you can
            copy and paste the Short Description).
        </label>
        <textarea name="item_long_descr" rows="5" id="st-product-long-descr"><?php echo $pLDescr; ?></textarea>

        <label for="st-product-offurl" title="for example http://trekbikes.com">
            Product/Category Offsite Link Url
        </label>
        <input type="text" name="item_offsite_url" value="<?php echo $pOffsite; ?>"
            id="st-product-offurl" />

        <label for="st-product-offtext" title="The text the will be displayed for the link">
            Prodcut/Category Offsite Link Text
        <label>
        <input type="text" name="item_offsite_text" value="<?php echo $pOffsiteText; ?>"
            id="st-product-offtext" />
    </form>
    </div>
</div>

<hr />
<div class="st-product-parent">
    <span class="lead">Parent Category:</div>
<?php
if (isset($pInfo["parent_product_id"]) && !empty($pInfo["parent_product_id"])) {
?>
    <a href="product.php?product_id=<?php echo $pInfo["parent_product_id"]; ?>"
        class="btn btn-info">
        <?php echo $pInfo["parent_name"]; ?>
    </a>
<?php
}
else {
?>
    <div class="alert alert-warning">
        This category is the root category and has no parent category.
    </div>
<?php
}
?>
</div>

<hr />
<div class="st-product-subs">
    <span class="lead">Sub Categories:</span>
<?php
if (isset($pInfo["child_product"]) && !empty($pInfo["child_product"])) {
    $subCats = $pInfo["child_product"];
    foreach($subCats as $sub) {
?>
    <a href="product.php?product_id=<?php echo $sub["product_id"]; ?>"
        class="btn btn-info">
        <?php echo $sub["name"]; ?>
        </a>
<?php
    }
}
else {
?>
    <div class="alert alert-warning">
        This category currently has no sub categories.
    </div>
<?php
}
?>
</div>
