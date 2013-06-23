<?php
define("ST_SLIDER_IMAGE_PIKER_ID", "slider-img-picker");

require_once("../cms/datalayer.php");

require_once("images.php");

$imgPicker = new ImagesModal(ST_SLIDER_IMAGE_PIKER_ID, "slider.php");
$imgPicker->writeElement();

function displayItem($itemRow) {
    $itemId = $itemRow["content_item_id"];

    $textAreaId = "st-content-edit-area-" . $itemId;
    $bgImgTextId = "st-content-edit-bgimg-" . $itemId;

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
?>
<div class="st-content-item">
    <button class="btn btn-danger" onmouseup="bboardDeleteToggle(this)" title="Delete Slider Item">
        <i class="icon-trash icon-white"></i>
    </button>

    <button class="btn btn-warning" onmouseup="bboardEditToggle(this)" title="Edit Slider Item">
        <i class="icon-pencil icon-white"></i>
    </button>

<?php
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

<!-- edit panel -->
    <div class="st-content-edit well">
        <form action="slider.php" method="post">
            <input type="hidden" name="form_type" value="content_edit" />
            <input type="hidden" name="form_type" value="<?php echo $itemId; ?>" />

            <label for="content_title">Slider Item Title:</label>
            <input type="text" name="content_title" value="<?php echo $itemTitle;?>" />

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
        </form>
    </div>
</div>
<?php
}

//Set up
$items = getContentItems();

?>

<h1>Home Page 'Slider' Edit Page'</h1>

<div class="st-container">

<?php
/*
 * display each item
 */
foreach($items as $itemRow) {
    displayItem($itemRow);
}
?>

</div>
<!-- end st-container -->
