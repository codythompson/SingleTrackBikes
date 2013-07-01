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
        <a href="<?php echo "/admin/navbar.php?parent_id=$navId"; ?>" class="btn btn-warning">
            Edit/Add dropdown options
        </a>
<?php
    }
?>
    </div>
</div>
<?php
}

/*
 * Form Logic
 */
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

for($i = 0; $i < count($navLinks); $i++) {
    $row = $navLinks[$i];
    $isTop = $i == 0;
    $isBottom = $i == count($navLinks) - 1;
    displayItem($row, "nav-link-$i", $isTop, $isBottom, null);
}
$emptyNav = array(
    "nav_link_id" => 0,
    "parent_nav_link_id" => null);

displayItem($emptyNav, "nav-link-add-$i", false, false, null);
?>
