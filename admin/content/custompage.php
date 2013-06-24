<?php
require_once("../cms/datalayer.php");
require_once("localvars.php");

$errorMess = array();
$succMess = array();

function addMessage($pageId, $message) {
    global $errorMess;

    if (empty($errorMess)) {
        $errorMess = array();
    }
    if (!isset($errorMess[$pageId])) {
        $errorMess[$pageId] = array();
    }
    $errorMess[$pageId][] = $message;
}

function getMessage($pageId) {
    global $errorMess;

    if (empty($errorMess) || !isset($errorMess[$pageId])) {
        return null;
    }
    else {
        return $errorMess[$pageId];
    }
}

/*
 * Form Logic
 */
$pFormType = "";
if (isset($_POST["form_type"])) {
    $pFormType = $_POST["form_type"];
}
if ($pFormType === "edit_page") {
    $pageId = intval($_POST["page_id"]);

    $pageTitle;
    if (isset($_POST["page_title"]) && !empty($_POST["page_title"])) {
        $pageTitle = $_POST["page_title"];
        if (strlen($pageTitle) > 255) {
            addMessage($pageId, "The page title cannot exceed 255 characters.");
        }
    }
    else {
        addMessage($pageId, "You must provide a page title.");
    }

    $pageHead;
    if (isset($_POST["page_heading"]) && !empty($_POST["page_heading"])) {
        $pageHead = $_POST["page_heading"];
        if (strlen($pageHead) > 255) {
            addMessage($pageId, "The page heading cannot exceed 255 characters.");
        }
    }
    else {
        addMessage($pageId, "You must provide a page heading.");
    }

    $pageContent;
    if (isset($_POST["page_heading"]) && !empty($_POST["page_heading"])) {
        $pageContent = $_POST["page_content"];
    }
    else {
        addMessage($pageId, "You must provide content for the page.");
    }

    $message = getMessage($pageId);
    if (empty($message)) {
        if($pageId == 0) {
            $result = insertPageContent($pageTitle, $pageHead, $pageContent);

            if ($result === true) {
                $succMess[] = "Successfully created the custom page '" .
                    "<strong>$pageHead</strong>'.";
            }
            else {
                addMessage($pageId, "A database error occured while creating " .
                    "the custom page.");
            }
        }
        else {
            $result = updatePageContent($pageId, $pageTitle, $pageHead,
                $pageContent);
            if ($result === true) {
                $succMess[] = "Successfully updated the custom page '" .
                    "<strong>$pageHead</strong>'.";
            }
            else {
                addMessage($pageId, "A database error occured while updating " .
                    "the custom page. ((This error might have occured because" .
                    " you clicked 'Save Changes' without actually changing " .
                    "anything.)");
            }
        }
    }
}
else if ($pFormType === "del_page") {
    $pageId = intval($_POST["page_id"]);

    deletePageContent($pageId);

    $succMess[] = "Successfully deleted the page.";
}
else if ($pFormType === "page_add_nav") {
    $linkUrl = $_POST["link_url"];
    $linkText = $_POST["link_text"];

    addNavLink($linkUrl, $linkText);
}
else if ($pFormType === "page_remove_nav") {
    $pageId = intval($_POST["page_id"]);

    deleteFromNavBar($pageId);
}
else if ($pFormType === "page_add_footer") {
    $linkUrl = $_POST["link_url"];
    $linkText = $_POST["link_text"];

    addFooterLink($linkUrl, $linkText);
}
else if ($pFormType === "page_remove_footer") {
    $pageId = intval($_POST["page_id"]);

    deleteFromFooterLinks($pageId);
}

/*
 * Html gen.
 */
function displayItem($pageRow, $cssId, $errorMessages, $inNavBar = false, 
    $inFooter = false) {

    $cssEditId = $cssId . "-edit";
    $cssDelId = $cssId . "-del";
    $cssValueId = $cssId . "-value";
    $cssTextAreaId = $cssId . "-text";

    $extraClass = "";
    if (!empty($errorMessages)) {
        $extraClass = " st-content-open";
    }

?>
<div id="<?php echo $cssId; ?>" class="st-content-area space-below">

<?php
    if (intval($pageRow["page_content_id"] == 0)) {
?>
        <button type="button" class="btn btn-success" title="create new custom page"
            onmouseup="toggleContainer('<?php echo $cssEditId; ?>')">
            <i class="icon-plus icon-white"></i>
        </button>
<?php
    }
    else {
?>
    <button type="button" class="btn btn-danger"
        onmouseup="toggleContainer('<?php echo $cssDelId; ?>')">
        <i class="icon-trash icon-white"></i>
    </button>

    <button type="button" class="btn btn-warning"
        onmouseup="toggleContainer('<?php echo $cssEditId; ?>')">
        <i class="icon-pencil icon-white"></i>
    </button>

<?php
if ($inNavBar) {
?>
    <form action="custompage.php" method="POST" class="st-content-up">
        <input type="hidden" name="form_type" value="page_remove_nav" />
        <input type="hidden" name="page_id"
            value="<?php echo $pageRow["page_content_id"]; ?>" />

        <input type="submit" value="Remove from Nav Bar" class="btn btn-danger"
            title="Remove the link to this page from the navigation bar at the top of the website." />
    </form>
<?php
}
else {
?>
    <form action="custompage.php" method="POST" class="st-content-up">
        <input type="hidden" name="form_type" value="page_add_nav" />
        <input type="hidden" name="link_url"
            value="/page.php?page_content_id=<?php echo $pageRow["page_content_id"]; ?>" />
        <input type="hidden" name="link_text"
            value="<?php echo $pageRow["page_heading"]; ?>" />

        <input type="submit" value="Add to Nav Bar" class="btn btn-info"
            title="Add a link to this page to the navigation bar at the top of the website." />
    </form>
<?php
}
?>

<?php
if ($inFooter) {
?>
    <form action="custompage.php" method="POST" class="st-content-up">
        <input type="hidden" name="form_type" value="page_remove_footer" />
        <input type="hidden" name="page_id"
            value="<?php echo $pageRow["page_content_id"]; ?>" />

        <input type="submit" value="Remove from Footer" class="btn btn-danger"
            title="Remove the link to this page from the footer links at the bottom of the website." />
    </form>
<?php
}
else {
?>
    <form action="custompage.php" method="POST" class="st-content-up">
        <input type="hidden" name="form_type" value="page_add_footer" />
        <input type="hidden" name="link_url"
            value="/page.php?page_content_id=<?php echo $pageRow["page_content_id"]; ?>" />
        <input type="hidden" name="link_text"
            value="<?php echo $pageRow["page_heading"]; ?>" />

        <input type="submit" value="Add to Footer" class="btn btn-info"
            title="Add a link to this page to the footer links at the bottom of the website." />
    </form>
<?php
}
?>
    <span class="alert alert-info"><?php echo $pageRow["page_heading"]; ?></span>
<?php
        $absUrl = "http://" . ST_LOCATION . getNavLinkUrl($pageRow["page_content_id"]);
?>
    <span class="alert alert-info">
        <a href="<?php echo $absUrl; ?>" target="_blank">visit</a> | 
        URL: <?php echo $absUrl; ?>
    </span>
<?php
    }
?>

<!-- edit area -->
<div id="<?php echo $cssEditId; ?>" class="st-content-edit well<?php echo $extraClass; ?>">
<?php
    if (!empty($errorMessages)) {
        foreach($errorMessages as $mess) {
            echo "<div class=\"alert alert-danger\">$mess</div>";
        }
    }
?>

<form action="custompage.php" method="POST"
        onsubmit="postEditorContent('<?php echo $cssTextAreaId?>', '<?php echo $cssValueId; ?>')">
    <input type="hidden" name="form_type" value="edit_page" />
    <input type="hidden" name="page_id" value="<?php echo $pageRow["page_content_id"]; ?>" />

    <label for="page-title-edit">Page Title (will be displayed on the browser tab)</label>
    <input type="text" name="page_title"
        value="<?php echo $pageRow["page_title"]; ?>" id="page-title-edit" />

    <label for="page-heading-edit">Page Heading (will be displayed at the top of the page)</label>
    <input type="text" name="page_heading"
        value="<?php echo $pageRow["page_heading"]; ?>" id="page-heading-edit" />

    <input type="hidden" name="page_content" id="<?php echo $cssValueId; ?>" />
    <label for="<?php echo $cssTextAreaId; ?>">Page Content</label>
    <div id="<?php echo $cssTextAreaId; ?>" class="st-misc-edit-area"><?php echo $pageRow["page_content"]; ?></div>
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

<!-- delete area -->
<div id="<?php echo $cssDelId; ?>" class="st-content-delete well">
<form action="custompage.php" method="POST">
    <input type="hidden" name="form_type" value="del_page" />
    <input type="hidden" name="page_id" value="<?php echo $pageRow["page_content_id"]; ?>" />

    <p class="lead">Are you sure you want to permanently delete this page?</p>

    <input type="submit" value="Delete" class="btn btn-danger" />
    <button type="button" class="btn btn-info" onmouseup="toggleContainer('<?php echo $cssDelId; ?>')">
        Hide
    </button>
</form>
</div>

</div>
<?php
}


$pageRows = getAllPageContent();

?>

<h1>Custom Pages Edit Page</h1>

<p class="lead">Here you can edit existing custom pages, or create new ones.</p>
<hr/>
<?php
if (empty($pageRows)) {
?>
<div class="alert alert-warning">There are currently no custom pages.</div>
<?php
}
else {
    foreach($pageRows as $row) {
        $inNavBar = navLinkExists(getNavLinkUrl($row["page_content_id"]));
        $inFooter = footerLinkExists(getNavLinkUrl($row["page_content_id"]));
        displayItem($row, "page-edit",
            getMessage(intval($row["page_content_id"])), $inNavBar, $inFooter);
    }
}
?>
<hr/>
<?php
displayItem(array("page_content_id" => 0, "page_title" => "",
    "page_heading" => "", "page_content" => ""), "new-page-edit",
    getMessage(0));
?>
