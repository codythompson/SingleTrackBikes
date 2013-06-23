<?php
define("ST_SLIDER_IMAGE_PIKER_ID", "slider-img-picker");

require_once("../cms/datalayer.php");

require_once("images.php");

/*
 * Form Logic
 */
$editErrors = array();
$editSuccs = array();

function addMessage($itemId, $message, $isError = true) {
    global $editErrors;

    if (empty($editErrors) || !array_key_exists($itemId, $editErrors)) {
        $editErrors[$itemId] = array();
    }
    $editErrors[$itemId][] = $message;
}

function errorOccurred($itemId) {
    global $editErrors;
    if (empty($editErrors)) {
        return false;
    }
    return array_key_exists($itemId, $editErrors);
}
function getErrorMessages($itemId) {
    global $editErrors;
    if (errorOccurred($itemId)) {
        return $editErrors[$itemId];
    }
    else {
        return null;
    }
}

$blankRow = null;
$sliderFormType = "";
if (isset($_POST["form_type"])) {
    $sliderFormType = $_POST["form_type"];
}
if ($sliderFormType === "content_edit") {
    $itemId = intval($_POST["content_item_id"]);

    // get the title
    $itemTitle = "";
    if (!isset($_POST["content_title"]) || empty($_POST["content_title"])) {
        addMessage($itemId, "You must provide a title.");
    }
    else {
        $itemTitle = $_POST["content_title"];
        if (strlen($itemTitle) > 255) {
            addMessage($itemId, "The title must be less than 255 characters.");
            $itemTitle = "";
        }
    }

    //get the body content
    $itemContent = "";
    if (!isset($_POST["item_content"]) || empty($_POST["item_content"])) {
        addMessage($itemId, "You must provide a content body.");
    }
    else {
        $itemContent = $_POST["item_content"];
    }

    //get the bg image url
    $itemBgUrl = "";
    if (!isset($_POST["content_bg_img_url"]) || empty($_POST["content_bg_img_url"])) {
        addMessage($itemId, "You must provide a background image URL.");
        $itemBgUrl = "";
    }
    else {
        $itemBgUrl = $_POST["content_bg_img_url"];
    }

    //get the bg image alt
    $itemBgAlt = "";
    if (!isset($_POST["content_bg_img_alt"]) || empty($_POST["content_bg_img_alt"])) {
        addMessage($itemId,
            "You must provide a description for the background image.");
    }
    else {
        $itemBgAlt = $_POST["content_bg_img_alt"];
        if (count($itemBgAlt) > 255) {
            addMessage($itemId, "The background image description must be " .
                "less than 255 characters.");
            $itemBgAlt = "";
        }
    }

    // get the location id
    $itemLoc = intval($_POST["content_item_loc"]);

    if ($itemId == 0) {
        if (errorOccurred($itemId)) {
            $blankRow = array();
            $blankRow["content_item_id"] = null;
            $blankRow["content_item_location_id"] = $itemLoc;
            $blankRow["title"] = $itemTitle;
            $blankRow["content"] = $itemContent;
            $blankRow["bg_image_url"] = $itemBgUrl;
            $blankRow["bg_image_alt"] = $itemBgAlt;
        }
        else {
            $result = addContentItem($itemLoc, $itemTitle, $itemContent,
                $itemBgUrl, $itemBgAlt);
            if ($result === true) {
                $editSuccs[] = "Successfully added <strong>$itemTitle</strong>";
            }
            else {
                $blankRow = array();
                $blankRow["content_item_id"] = null;
                $blankRow["content_item_location_id"] = $itemLoc;
                $blankRow["title"] = $itemTitle;
                $blankRow["content"] = $itemContent;
                $blankRow["bg_image_url"] = $itemBgUrl;
                $blankRow["bg_image_alt"] = $itemBgAlt;
                $eMess = "A database error occured while adding the slider " .
                    "item!";
                addMessage($itemId, $eMess);
            }
        }
    }
    else {
        if (!errorOccurred($itemId)) {
            $result = editContentItem($itemId, $itemLoc, $itemTitle, $itemContent,
                $itemBgUrl, $itemBgAlt);
            if ($result === true) {
                $editSuccs[] = "Successfully updated <strong>$itemTitle</strong>";
            }
            else {
                $eMess = "A database error occured while updating the slider " .
                    "item.<br/>(This error might have occured because you " .
                    "clicked 'Save Changes' without actually changing " .
                    "anything.)";
                addMessage($itemId, $eMess);
            }
        }
    }
}
else if ($sliderFormType === "move_up") {
    $itemId = intval($_POST["content_item_id"]);
    reorderContentItemUp($itemId);
}
else if ($sliderFormType === "move_down") {
    $itemId = intval($_POST["content_item_id"]);
    reorderContentItemDown($itemId);
}
else if ($sliderFormType === "content_item_del") {
    $itemId = intval($_POST["content_item_id"]);
    deleteContentItem($itemId);
}

/*
 * Edit form builder funcs
 */

function displayItemLocDDL($locationInfo, $selectedLoc) {
?>
<div class="space-above">
<label for="content_item_loc">Slider Item Content Location</label>
<select name="content_item_loc" id="content_item_loc">
<?php
    foreach($locationInfo as $loc) {
        $selString = "";
        if ($selectedLoc == intval($loc["content_item_location_id"])) {
            $selString = "selected=\"selected\"";
        }
        $name = $loc["name"];
        $value = $loc["content_item_location_id"];
        echo "<option value=\"$value\" $selString>$name</option>";
    }
?>
</select>
</div>
<?php
}

function displayItem($itemRow, $locationInfo, $messages = null, $isTop = false,
    $isBottom = false) {

    $itemId = 0;
    if (!empty($itemRow["content_item_id"])) {
        $itemId = intval($itemRow["content_item_id"]);
    }
    $itemLocId = 1;
    if (!empty($itemRow["content_item_location_id"])) {
        $itemLocId = intval($itemRow["content_item_location_id"]);
    }

    $editContId = "st-content-edit-" . $itemId;
    $delContId = "st-content-delete-" . $itemId;
    $textAreaId = "st-content-edit-area-" . $itemId;
    $bgImgTextId = "st-content-edit-bgimg-" . $itemId;
    $contentId = "st-content-edit-text-" . $itemId;

    $itemTitle = "";
    if (!empty($itemRow["title"])) {
        $itemTitle = $itemRow["title"];
    }

    $itemText = "";
    if (!empty($itemRow["content"])) {
        $itemText = $itemRow["content"];
    }

    $bgImgUrl = "";
    if (!empty($itemRow["bg_image_url"])) {
        $bgImgUrl = $itemRow["bg_image_url"];
    }

    $bgImgAlt = "";
    if (!empty($itemRow["bg_image_alt"])) {
        $bgImgAlt = $itemRow["bg_image_alt"];
    }

?>
<div class="st-content-item">
<?php
    if ($itemId != 0) {
?>
    <button class="btn btn-danger" onmouseup="toggleContainer('<?php echo $delContId; ?>')"
        title="Delete Slider Item">
        <i class="icon-trash icon-white"></i>
    </button>

    <button class="btn btn-warning" onmouseup="toggleContainer('<?php echo $editContId ?>')" title="Edit Slider Item">
        <i class="icon-pencil icon-white"></i>
    </button>

<?php
        if ($isTop) {
?>
        <button type="button" class="btn disabled" disabled="disabled">
            <i class="icon-arrow-up icon-white"></i>
        </button>
<?php
        }
        else {
?>
    <form class="st-content-up" action="slider.php" method="POST">
        <input type="hidden" name="form_type" value="move_up" />
        <input type="hidden" name="content_item_id" value="<?php echo $itemId; ?>" />
        <button type="submit" class="btn btn-info" title="move item up one place">
            <i class="icon-arrow-up icon-white"></i>
        </button>
    </form>
<?php
        }

        if ($isBottom) {
?>
        <button type="button" class="btn disabled" disabled="disabled">
            <i class="icon-arrow-down icon-white"></i>
        </button>
<?php
        }
        else {
?>
    <form class="st-content-up" action="slider.php" method="POST">
        <input type="hidden" name="form_type" value="move_down" />
        <input type="hidden" name="content_item_id" value="<?php echo $itemId; ?>" />
        <button type="submit" class="btn btn-info" title="move item down one place">
            <i class="icon-arrow-down icon-white"></i>
        </button>
    </form>
<?php
        }

        if (empty($itemTitle)) {
?>
    <span class="alert alert-info">(Untitled)</span>
<?php
        }
        else {
?>
    <span class="alert alert-info"><?php echo $itemTitle; ?></span>
<?php
        }
?>

<?php
    }
    else {
?>
    <button class="btn btn-success" onmouseup="toggleContainer('<?php echo $editContId ?>')"
        title="Add Slider Item">+</button>
<?php
    }
?>
<!-- edit panel -->
<?php
    if (!empty($messages)) {
?>
    <div class="st-content-edit st-content-open well" id="<?php echo $editContId; ?>">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
<?php
        foreach($messages as $mess) {
            echo "<div>$mess</div>";
        }
        echo "</div>";
    }
    else {
?>
    <div class="st-content-edit well" id="<?php echo $editContId; ?>">
<?php
    }
?>

        <form action="slider.php" method="post"
                onsubmit="postEditorContent('<?php echo $textAreaId; ?>', '<?php echo $contentId; ?>')">
            <input type="hidden" name="form_type" value="content_edit" />
            <input type="hidden" name="content_item_id" value="<?php echo $itemId; ?>" />

            <label for="content_title">Slider Item Title:</label>
            <input type="text" name="content_title" value="<?php echo $itemTitle;?>" />

            <input type="hidden" name="item_content" id="<?php echo $contentId; ?>"/>
            <label for="<?php echo $textAreaId; ?>">Slider Item Body</label>
            <div id="<?php echo $textAreaId; ?>" class="st-content-edit-area"><?php echo $itemText; ?></div>
            <script type="text/javascript">
                (new nicEditor({fullPanel: true})).panelInstance('<?php echo $textAreaId ?>');
            </script>

            <div class="input-append space-above">
            <label for="<?php echo $bgImgTextId; ?>">Slider Item Background Image Url</label>
            <input type="text" name="content_bg_img_url" id="<?php echo $bgImgTextId; ?>"
                value="<?php echo $bgImgUrl; ?>" />
            <button type="button" class="btn btn-success"
                onmouseup="showImagesModal('<?php echo ST_SLIDER_IMAGE_PIKER_ID; ?>', '<?php echo $bgImgTextId; ?>')">
                Pick Background Image Url
            </button>
            </div>

            <div class="space-above">
            <label for="content_bg_img_alt">Slider Item Background Image Description</label>
            <input type="text" name="content_bg_img_alt" id="content_bg_img_alt"
                value="<?php echo $bgImgAlt; ?>" />
            </div>

<?php
    displayItemLocDDL($locationInfo, $itemLocId);
?>

            <div class="space-above">
                <input type="submit" value="Save Changes" class="btn btn-success space-right" />
                <button type="button" class="btn btn-info" onmouseup="toggleContainer('<?php echo $editContId; ?>')">
                    Hide
                </button>
            </div>

        </form>
    </div>
    <!-- end edit panel -->
    <!-- del panel -->
    <div id="<?php echo $delContId; ?>" class="st-content-delete well">
        <form action="slider.php" method="POST">
            <input type="hidden" name="form_type" value="content_item_del" />
            <input type="hidden" name="content_item_id" value="<?php echo $itemId; ?>" />
            <p>Are you sure you want to delete this slider item?</p>
            <input type="submit" value="Delete" class="btn btn-danger" />
            <button type="button" class="btn btn-info"
                onmouseup="toggleContainer('<?php echo $delContId; ?>')">
                Hide
            </button>
        </form>
    </div>
</div>
<?php
}

//Set up
$items = getContentItems();
$locOptions = getContentItemLocations();

$imgPicker = new ImagesModal(ST_SLIDER_IMAGE_PIKER_ID, "slider.php");
$imgPicker->writeElement();
?>

<h1>Home Page 'Slider' Edit Page'</h1>

<?php
if (count($editSuccs) > 0) {
?>
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
<?php
    foreach($editSuccs as $mess) {
        echo "<div>$mess</div>";
    }
?>
</div>
<?php
}
?>

<div class="st-container">

<?php
/*
 * display each item
 */
//foreach($items as $itemRow) {
for ($i = 0; $i < count($items); $i++) {
    $itemRow = $items[$i];
    $isTop = $i == 0;
    $isBot = $i == count($items) - 1;
    displayItem($itemRow, $locOptions,
        getErrorMessages(intval($itemRow["content_item_id"])), $isTop, $isBot);
}

if (empty($blankRow)) {
    $blankRow = array();
    $blankRow["content_item_id"] = null;
    $blankRow["content_item_location_id"] = null;
    $blankRow["title"] = null;
    $blankRow["content"] = null;
    $blankRow["bg_image_url"] = null;
    $blankRow["bg_image_alt"] = null;
}
displayItem($blankRow, $locOptions, getErrorMessages(0));
?>

</div>
<!-- end st-container -->
