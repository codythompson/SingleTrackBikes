<?php
require_once("../cms/datalayer.php");

/*
 * Functions
 */
function displayItem($navRow, $cssId, $isTop, $isBottom, $errorMessages) {
    $cssEditId = $cssId . "-edit";
    $cssDelId = $cssId . "-del";
    $navId = intval($navRow["nav_link_id"]);
    $parentId = intval($navRow["parent_nav_link_id"]);

    $url = "";
    if (!empty($navRow["link_url"])) {
        $url = $navRow["link_url"];
    }
    $text = "";
    if (!empty($navRow["link_text"])) {
        $text = $navRow["link_text"];
    }
    $hover = "";
    if (!empty($navRow["link_hover_text"])) {
        $hover = $navRow["link_hover_text"];
    }

    $extraClass = "";
    if (!empty($errorMessages)) {
        $extraClass = " st-content-open";
    }
?>
<div id="<?php echo $cssId; ?>" class="st-content-area space-below">
<?php
    if ($navId == 0) {
?>
    <button type="button" class="btn btn-success" title="create new nav link"
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
        if ($isTop) {
?>
    <button type="button" class="btn disabled" disabled="disabled">
        <i class="icon-arrow-up icon-white"></i>
    </button>
<?php
        }
        else {
?>
    <form class="st-content-up" action="navbar.php" method="POST">
        <input type="hidden" name="form_type" value="move_up" />
        <input type="hidden" name="nav_link_id" value="<?php echo $navId; ?>" />
        <input type="hidden" name="parent_nav_link_id" value="<?php echo $parentId; ?>" />
        <button type="submit" class="btn btn-info" title="move link up one place">
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
    <form class="st-content-up" action="navbar.php" method="POST">
        <input type="hidden" name="form_type" value="move_down" />
        <input type="hidden" name="nav_link_id" value="<?php echo $navId; ?>" />
        <input type="hidden" name="parent_nav_link_id" value="<?php echo $parentId; ?>" />
        <button type="submit" class="btn btn-info" title="move link down one place">
            <i class="icon-arrow-down icon-white"></i>
        </button>
    </form>

<?php
        }
?>
    <span class="alert alert-info"><?php echo $navRow["link_text"]; ?></span>
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

    if (empty($navRow["parent_nav_link_id"]) && $navId != 0) {
?>
        <a href="<?php echo "/admin/navbar.php?parent_id=$navId"; ?>" class="btn btn-warning space-below">
            Edit/Add dropdown options
        </a>
<?php
    }
?>
    <form action="navbar.php" method="POST">
        <input type="hidden" name="form_type" value="edit_nav" />
        <input type="hidden" name="nav_link_id" value="<?php echo $navId; ?>" />
        <input type="hidden" name="parent_nav_link_id" value="<?php echo $parentId; ?>" />

        <label for="nav_url">Link Url</label>
        <div class="alert alert-danger">
            <strong>IMPORTANT</strong>: If you using a link on this (the single track) website,
            You should remove the domain (http://singletrackbikes.com) from the url. <br/>
            For example 'http://singletrackbikes.com/location.php' should be
            changed to '/location.php' <br/>
            The home page, 'http://singletrackbikes.com/' should just be '/' <br/><br/>
            However, if you are linking to a page NOT on this website you can/should leave
            the link alone <br/>
            For example 'http://www.trekbikes.com/us/en/bikes/road' should remain as 'http://www.trekbikes.com/us/en/bikes/road'
        </div>
        <input type="text" name="nav_url" value="<?php echo $url; ?>" id="nav_url" />

        <label for="nav_text" title="This will be the text displayed for the link in the navbar">
            Link Text
        </label>
        <input type="text" name="nav_text" value="<?php echo $text; ?>" id="nav_text" />

        <label for="nav_hover" title="This will be the text displayed when the user hovers over the link. (It will look just like this hover message">
            Link Hover Text
        </label>
        <input type="text" name="nav_hover" value="<?php echo $hover; ?>" id="nav_hover" />

        <div class="space-above">
            <input type="submit" value="Save Changes" class="btn btn-success" />
            <button type="button" onmouseup="toggleContainer('<?php echo $cssEditId; ?>')"
                class="btn btn-info">Hide</button>
        </div>

    </form>

    </div>

    <!-- delete area -->
    <div id="<?php echo $cssDelId; ?>" class="st-content-delete well">
    <form action="navbar.php" method="POST">
        <input type="hidden" name="form_type" value="del_nav" />
        <input type="hidden" name="nav_link_id" value="<?php echo $navId; ?>" />
        <input type="hidden" name="parent_nav_link_id" value="<?php echo $parentId; ?>" />
    
        <p class="lead">Are you sure you want to permanently delete this nav link?</p>
    
        <input type="submit" value="Delete" class="btn btn-danger" />
        <button type="button" class="btn btn-info" onmouseup="toggleContainer('<?php echo $cssDelId; ?>')">
            Hide
        </button>
    </form>
    </div>
    
</div>
<?php
}

/*
 * Form Logic
 */
$errorMess = array();
$succMess = array();

function addMessage($navId, $message) {
    global $errorMess;

    if (empty($errorMess)) {
        $errorMess = array();
    }
    if (!isset($errorMess[$navId])) {
        $errorMess[$navId] = array();
    }
    $errorMess[$navId][] = $message;
}

function getMessage($navId) {
    global $errorMess;

    if (empty($errorMess) || !isset($errorMess[$navId])) {
        return null;
    }
    else {
        return $errorMess[$navId];
    }
}


$nFormType = "";
if (isset($_POST["form_type"])) {
    $nFormType = $_POST["form_type"];
}
if ($nFormType === "move_up") {
    $navId = intval($_POST["nav_link_id"]);
    $parentId = intval($_POST["parent_nav_link_id"]);
    reOrderNavLinkUp($navId, $parentId);
    $_GET["parent_id"] = $parentId;
}
else if ($nFormType === "move_down") {
    $navId = intval($_POST["nav_link_id"]);
    $parentId = intval($_POST["parent_nav_link_id"]);
    reOrderNavLinkDown($navId, $parentId);
    $_GET["parent_id"] = $parentId;
}
else if ($nFormType === "edit_nav") {
    $navId = intval($_POST["nav_link_id"]);
    $parentId = intval($_POST["parent_nav_link_id"]);

    $url = null;
    if (isset($_POST["nav_url"]) && !empty($_POST["nav_url"])) {
        $url = $_POST["nav_url"];
    }

    if (empty($url)) {
        addMessage($navId, "You must provide a url.");
    }

    $text = null;
    if (isset($_POST["nav_text"]) && !empty($_POST["nav_text"])) {
        $text = $_POST["nav_text"];
    }

    if (empty($text)) {
        addMessage($navId, "You must provide a text name for the link.");
        if (strlen($text) > 255) {
            addMessage($navId, "The link text cannot be longer than 255 characters.");
        }
    }

    $hover = null;
    if (isset($_POST["nav_hover"]) && !empty($_POST["nav_hover"])) {
        $hover = $_POST["nav_hover"];
    }

    if (strlen($hover) > 255) {
        addMessage($navId, "The link text cannot be longer than 255 characters.");
    }

    if ($navId == 0) {
        $result;
        if ($parentId > 0) {
            $result = addNavLinkWParent($parentId, $url, $text, $hover);
        }
        else {
            $result = addNavLink($url, $text, $hover);
        }
        if ($result === true) {
            $succMess[] = "Successfully added the link <strong>'$text'</strong>";
        }
        else {
            addMessage($navId, "A database error occurred. This may have " .
                "happened if you clicked 'save changes' without actually" .
                "changing anything.");
        }
    }
    else {
        $result = updateNavLink($navId, $url, $text, $hover);
        if ($result === true) {
            $succMess[] = "Successfully edited the link <strong>'$text'</strong>";
        }
        else {
            addMessage($navId, "A database error occurred. This may have " .
                "happened if you clicked 'save changes' without actually" .
                "changing anything.");
        }
    }

    $_GET["parent_id"] = $parentId;
}
else if ($nFormType === "del_nav") {
    $navId = intval($_POST["nav_link_id"]);
    $parentId = intval($_POST["parent_nav_link_id"]);
    deleteNavLink($navId);

    $_GET["parent_id"] = $parentId;
}

/*
 * Setup 
 */
$parentId = 0;
if (isset($_GET["parent_id"])) {
    $parentId = intval($_GET["parent_id"]);
}

$navLinks = GetNavLinksByParent($parentId);
$parentText = GetNavLinkText($parentId);

?>
<h1>Edit the Nav-Bar</h1>
<p class="lead">Here you can edit the navigation links at the top of the page</p>
<?php
if (!empty($parentText)) {
?>
<p class="alert alert-info">
You are currently editing the dropdown options under
'<strong><?php echo $parentText; ?></strong>'
<a href="/admin/navbar.php" class="btn btn-info">Back</a>
</p>
<?php

    if (empty($navLinks)) {
?>
<p class="alert alert-warning">There are currently no dropdown options under
'<strong><?php echo $parentText; ?></strong>'
</p>
<?php
    }
}
else if (empty($navLinks)) {
?>
<p class="alert alert-warning">There are currently no nav-links</p>
<?php
}
foreach($succMess as $mess) {
?>
<div class="alert alert-success">
<?php echo $mess; ?>
</div>
<?php
}

for($i = 0; $i < count($navLinks); $i++) {
    $row = $navLinks[$i];
    $isTop = $i == 0;
    $isBottom = $i == count($navLinks) - 1;
    displayItem($row, "nav-link-$i", $isTop, $isBottom, getMessage(intval($row["nav_link_id"])));
}
$emptyNav = array(
    "nav_link_id" => 0,
    "parent_nav_link_id" => $parentId);

displayItem($emptyNav, "nav-link-add-$i", false, false, getMessage(0));
?>
