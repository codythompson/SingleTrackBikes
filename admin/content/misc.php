<?php
require_once("../cms/datalayer.php");

$errorMess = array();
$succMess = array();

function addMessage($miscName, $message) {
    global $errorMess;

    if (empty($errorMess)) {
        $errorMess = array();
    }
    if (!isset($errorMess[$miscName])) {
        $errorMess[$miscName] = array();
    }
    $errorMess[$miscName][] = $message;
}

function getMessage($miscName) {
    global $errorMess;

    if (empty($errorMess) || !isset($errorMess[$miscName])) {
        return null;
    }
    else {
        return $errorMess[$miscName];
    }
}
/*
 * Form Logic
 */
$mFormType = "";
if (isset($_POST["form_type"])) {
    $mFormType = $_POST["form_type"];
}
if ($mFormType === "edit_misc") {
    $miscTextName = $_POST["misc_text_name"];
    if (isset($_POST["misc_content"]) && !empty($_POST["misc_content"])) {
        $value = $_POST["misc_content"];

        $result = updateMiscText($miscTextName, $value);
        if ($result === true) {
            $succMess[] = "Your changes to '$miscTextName' have been saved.";
        }
        else {
            addMessage($miscTextName, "No values in the database changed. " .
                "(this could be an issue in the code or you may have just " .
                "clicked 'Save Changes' without actually changeing anything.");
        }
    }
    else {
        addMessage($miscTextName, "You must provide at least some text for this.");
    }
}

$miscRows = getAllMiscText();

/*
 * Html gen.
 */
function displayItem($miscRow, $cssId, $errorMessages) {
    $cssEditId = $cssId . "-edit";
    $cssValueId = $cssId . "-value";
    $cssTextAreaId = $cssId . "-text";

    $extraClass = "";
    if (!empty($errorMessages)) {
        $extraClass = " st-content-open";
    }

?>
<div id="<?php echo $cssId; ?>" class="st-content-area space-below">

    <button type="button" class="btn btn-warning"
        onmouseup="toggleContainer('<?php echo $cssEditId; ?>')">
        <i class="icon-pencil icon-white"></i>
    </button>
    <span class="alert alert-info"><?php echo $miscRow["misc_text_name"]; ?></span>

<div id="<?php echo $cssEditId; ?>" class="st-content-edit well<?php echo $extraClass; ?>">
<?php
    if (!empty($errorMessages)) {
        foreach($errorMessages as $mess) {
            echo "<div class=\"alert alert-danger\">$mess</div>";
        }
    }
?>

<form action="misc.php" method="POST"
        onsubmit="postEditorContent('<?php echo $cssTextAreaId?>', '<?php echo $cssValueId; ?>')">
    <input type="hidden" name="form_type" value="edit_misc" />
    <input type="hidden" name="misc_text_name" value="<?php echo $miscRow["misc_text_name"]; ?>" />

    <input type="hidden" name="misc_content" id="<?php echo $cssValueId; ?>" />
    <label for="<?php echo $cssTextAreaId; ?>"><?php echo $miscRow["descr"]; ?></label>
    <div id="<?php echo $cssTextAreaId; ?>" class="st-misc-edit-area"><?php echo $miscRow["value"]; ?></div>
    <script type="text/javascript">
        (new nicEditor({fullPanel: true})).panelInstance('<?php echo $cssTextAreaId ?>');
    </script>

    <div class="space-above">
        <input type="submit" value="Save Changes" class="btn btn-success" />
        <button type="button" class="btn btn-info" onmouseup="toggleContainer('<?php echo $cssEditId; ?>')">
            Hide
        </button>
    </div>

</form>
</div>
<!-- end edit area -->

</div>
<?php
}
?>
<h1>Edit Miscellaneous Areas</h1>
<p class="lead">Here you can edit text from various locations around the website</p>

<?php
if (!empty($succMess)) {
    foreach($succMess as $mess) {
        echo "<div class=\"alert alert-success\">$mess</div>";
    }
}

for($i = 0; $i < count($miscRows); $i++) {
    $row = $miscRows[$i];
    displayItem($row, "misc-edit-" . $i, getMessage($row["misc_text_name"]));
}
?>
