<?php
define("ST_PRODUCT_IMAGE_PICKER_ID", "product-img-picker");

require_once("../cms/datalayer.php");
require_once("images.php");

/*
 * Form Logic
 */
$errMess = array();
$addErrMess = array();
$succMess = array();

$pFormType = "";
if (isset($_POST["form_type"])) {
    $pFormType = $_POST["form_type"];
}
if ($pFormType === "product_edit") {
    $pId = intval($_POST["item_id"]);
    $selStyle = intval($_POST["item_style"]);

    if (!isset($_POST["item_title"]) || empty($_POST["item_title"])) {
        if ($pId == 0) {
            $addErrMess[] = "You must provide a title for the Product/Category.";
        }
        else {
            $errMess[] = "You must provide a title for the Product/Category.";
        }
    }
    else {
        $pName = $_POST["item_title"];
    }

    if (isset($_POST["item_descr"])) {
        $pDescr = $_POST["item_descr"];
    }
    else {
        $pDescr = "";
    }

    if (isset($_POST["item_long_descr"])) {
        $pLDescr = $_POST["item_long_descr"];
    }
    else {
        $pLDescr = "";
    }

    if (isset($_POST["item_offsite_url"])) {
        $pOffsite = $_POST["item_offsite_url"];
    }
    else {
        $pOffsite = "";
    }

    if (isset($_POST["item_offsite_text"])) {
        $pOffsiteText = $_POST["item_offsite_text"];
    }
    else {
        $pOffsiteText = "";
    }

    if (isset($_POST["item_img_url"])) {
        $imgUrl = $_POST["item_img_url"];
    }
    else {
        $imgUrl = "";
    }

    if (isset($_POST["item_bgimg_url"])) {
        $bgUrl = $_POST["item_bgimg_url"];
    }
    else {
        $bgUrl = "";
    }

    if ($pId == 0) {
        if (count($addErrMess) == 0) {
            $parentId = intval($_POST["item_parent_id"]);
            $result = insertNewProduct($parentId, $selStyle, $pName,
                $pDescr, $pLDescr, $pOffsite, $pOffsiteText, $imgUrl, $bgUrl);
            if ($result === true) {
                $succMess[] = "Successfully created <strong>$pName</strong>";
            }
            else {
                $addErrMess[] = "A database error occurred while updating the " .
                    "product/category. (This error might have occured because" .
                    " you clicked 'Save Changes' without actually changing " .
                    "anything.)";
            }
        }
    }
    else {
        if (count($errMess) == 0) {
            $result = updateProductInfo($pId, $selStyle, $pName, $pDescr,
                $pLDescr, $pOffsite, $pOffsiteText, $imgUrl, $bgUrl);
            if ($result === true) {
                $succMess[] = "Successfully updated <strong>$pName</strong>";
            }
            else {
                $errMess[] = "A database error occurred while updating the " .
                    "product/category. (This error might have occured because" .
                    " you clicked 'Save Changes' without actually changing " .
                    "anything.)";
            }
        }
    }
}
else if ($pFormType === "product_delete") {
    $pId = $_POST["item_id"];

    $oCount = deleteProduct($pId);
    $succMess[] = "The product/category was successfully deleted. <strong>" .
        "$oCount</strong> sub-categories were orphaned but can be added as a " .
        "sub-category to an existing category via the dropdown at the bottom " .
        "of this page.";
}
else if ($pFormType == "product_set_parent") {
    $pId = intval($_POST["product_id"]);
    $parentId = intval($_POST["parent_id"]);
    if (isset($_POST["add_parent"])) {
        $result = setParent($pId, $parentId);
        if ($result === true) {
            $succMess[] = "Successfully set parent";
        }
    }
    else if (isset($_POST["delete_orphan"])) {
        $oCount = deleteProduct($pId);
        $succMess[] = "The product/category was successfully deleted. <strong>" .
            "$oCount</strong> sub-categories were orphaned but can be added as a " .
            "sub-category to an existing category via the dropdown at the bottom " .
            "of this page.";
    }
}

$pId = 1;
if (isset($_GET["product_id"]) && !empty($_GET["product_id"])) {
    $pId = intval($_GET["product_id"]);
}

$pInfo = getProductInfo($pId, true);
$selStyle = intval($pInfo["product_style_id"]);

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

$styleInfo = getProductStyleInfo();
$orphanInfo = getOrphanedProducts();

/*
 * Html gen code
 */
?>
<?php
function displayStyleSelector($styleInfo, $selStyle) {
?>
<div class="space-above">
<label for="product-style-options">Product Style/Layout Options</label>
<select name="item_style" id="product-style-options">
<?php
    foreach($styleInfo as $style) {
        $selString = "";
        if ($selStyle == intval($style["product_style_id"])) {
            $selString = "selected=\"selected\"";
        }
        $name = $style["name"];
        $value = $style["product_style_id"];
        $title = $style["descr"];
        echo "<option value=\"$value\" title=\"$title\" $selString>$name</option>";
    }
?>
</select>
</div>
<?php
}

function displayEditArea($cssId, $pId, $pName, $pDescr, $pLDescr, $pOffsite,
    $pOffsiteText, $imgUrl, $bgUrl, $selStyle, $styleInfo, $errMessage = null,
    $isNew = false) {

    $extraClass = "";
    if (!empty($errMessage)) {
        $extraClass = " st-content-open";
    }
?>
<!-- edit area -->
    <div id="<?php echo $cssId; ?>" class="st-content-edit well<?php echo $extraClass; ?>">

<?php
    if (!empty($errMessage)) {
        foreach($errMessage as $eMess) {
            echo "<div class=\"alert alert-danger\">$eMess</div>";
        }
    }
?>

    <form action="product.php?product_id=<?php echo $pId; ?>" method="POST">
        <input type="hidden" name="form_type" value="product_edit" />
<?php
    if ($isNew) {
?>
        <input type="hidden" name="item_id" value="0" />
        <input type="hidden" name="item_parent_id" value="<?php echo $pId; ?>" />
<?php
    }
    else {
?>
        <input type="hidden" name="item_id" value="<?php echo $pId; ?>" />
<?php
    }
?>

        <label for="st-product-title">Product/Category Title</label>
        <input type="text" name="item_title" value="<?php echo $pName; ?>"
            id="st-product-title" />

        <label for="st-product-descr">Product/Category Short Description</label>
        <textarea name="item_descr" id="st-product-descr" rows="5"><?php echo $pDescr; ?></textarea>

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
        </label>
        <input type="text" name="item_offsite_text" value="<?php echo $pOffsiteText; ?>"
            id="st-product-offtext" />

<?php
    $imgInId = $cssId . "-imgurl";
?>
        <div class="input-append space-above">
        <label for="<?php echo $imgInId; ?>">Product/Category Image Url</label>
        <input type="text" name="item_img_url" value="<?php echo $imgUrl; ?>"
            id="<?php echo $imgInId; ?>" />
        <button type="button" class="btn btn-success" onmouseup="showImagesModal('<?php
            echo ST_PRODUCT_IMAGE_PICKER_ID; ?>', '<?php echo $imgInId; ?>')">
            Pick Image Url
        </button>
        </div>

<?php
    $bgInId = $cssId . "-bgurl";
?>
        <div class="input-append space-above">
        <label for="<?php echo $bgInId; ?>">Product/Category Background Image Url</label>
        <input type="text" name="item_bgimg_url" value="<?php echo $bgUrl; ?>"
            id="<?php echo $bgInId; ?>" />
        <button type="button" class="btn btn-success" onmouseup="showImagesModal('<?php
            echo ST_PRODUCT_IMAGE_PICKER_ID; ?>', '<?php echo $bgInId; ?>')">
            Pick Image Url
        </button>
        </div>

<?php
        displayStyleSelector($styleInfo, $selStyle);
?>

        <div class="space-above">
            <input type="submit" value="Save Changes" class="btn btn-success" />
            <button type="button" class="btn btn-info" onmouseup="toggleContainer('<?php echo $cssId; ?>')">
                Hide
            </button>
        </div>
    </form>
    </div>
</div>
<?php
}

function displayDeleteArea($pId) {
?>
    <div class="st-content-delete well" id="st-content-product-delete">
        <p class="lead">Are you sure you want to delete this product/category?</p>
        <form action="product.php" method="POST">
            <input type="hidden" name="form_type" value="product_delete" />
            <input type="hidden" name="item_id" value="<?php echo $pId; ?>" />

            <input type="submit" value="Delete" class="btn btn-danger" />
            <button type="button" class="btn btn-info"
                onmouseup="toggleContainer('st-content-product-delete')">
                Hide
            </button>
        </form>
        <div class="alert alert-warning">
            Any sub-categories of this product/category will not be visible to
            the public until they are added as sub-categories to an existing
            product/category
        </div>
    </div>
<?php
}

function displayOrphanDDL($pId, $pName, $orphanRows) {
?>
<p class="lead">Orphaned Products/Categories</p>
<p>
    The following drop down list contains all of the product/categories with no
    parent product/category. The orphaned products/categories will not be
    visible to the public until they are added as a sub category to an existing
    product/category.
</p>
<?php
    if (empty($orphanRows)) {
?>
    <div class="alert alet-warning">
        There a currently not any orphaned products/categores
    </div>
<?php
    }
    else {
?>
    <form action="product.php?product_id=<?php echo $pId; ?>" method="POST">
        <input type="hidden" name="form_type" value="product_set_parent" />
        <input type="hidden" name="parent_id" value="<?php echo $pId; ?>" />
        <select name="product_id">
<?php
        foreach($orphanRows as $row) {
            $val= $row["product_id"];
            $name = $row["name"];
            echo "<option value=\"$val\">$name</option>";
        }
?>
        </select>

        <div class="space-above">
        <input type="submit" name="add_parent"
            value="Set '<?php echo $pName; ?>' as Parent" class="btn btn-success" />
        <input type="submit" name="delete_orphan" value="Delete"
            class="btn btn-danger" />
        </div>
    <form>
<?php
    }
}

//image picker
$imgsMod = new ImagesModal(ST_PRODUCT_IMAGE_PICKER_ID, "product.php");
$imgsMod->writeElement();

?>
<h1>Edit Product/Category Page: <strong>'<?php echo $pName; ?>'</strong></h1>

<div class="st-product">
<?php
if (!empty($succMess)) {
    foreach($succMess as $sMess) {
        echo "<div class=\"alert alert-success\">$sMess</div>";
    }
}

if (isset($pInfo["parent_product_id"]) && !empty($pInfo["parent_product_id"])) {
?>
    <button type="button" class="btn btn-danger"
        onmouseup="toggleContainer('st-content-product-delete')">
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

<?php
displayEditArea("st-product-edit", $pId, $pName, $pDescr, $pLDescr, $pOffsite, $pOffsiteText,
    $imgUrl, $bgUrl, $selStyle, $styleInfo, $errMess);
displayDeleteArea($pId);
?>

<hr />
<div class="st-product-parent">
    <span class="lead">Parent Product/Category:</span>
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
    <span class="lead">Sub Products/Categories:</span>
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
    <div class="space-above">
    <button type="button" class="btn btn-success"
        onmouseup="toggleContainer('st-product-add')">
        Add a Sub-Category
    </button>
    </div>
    
<?php
displayEditArea("st-product-add", $pId, "", "", "", "", "", "", "", 0,
    $styleInfo, $addErrMess, true);

?>
<hr />
<?php

displayOrphanDDL($pId, $pName, $orphanInfo);
?>
</div>
