<?php
require_once("../cms/datalayer.php");

// adapted from
// http://stackoverflow.com/questions/6004343/converting-br-into-a-new-line-for-use-in-a-text-area
function br2nl($text) {
    $breaks = array("<br />","<br>","<br/>");
    return str_ireplace($breaks, "\n", $text);
}

$errorMess = array();
$succMess = array();

$form_type = null;
if (isset($_POST["form_type"])) {
    $form_type = $_POST["form_type"];
}

$update_annId = 0;
$newB_title = null;
$newB_text = null;

/*
 * POST processing code
 */
if ($form_type === "ann_create" || $form_type === "ann_edit") {
    if ($form_type === "ann_edit" && isset($_POST["ann_id"]) && !empty($_POST["ann_id"])) {
        $update_annId = intval($_POST["ann_id"]);
    }

    if (isset($_POST["ann-title"]) && !empty($_POST["ann-title"])) {
        $newB_title = $_POST["ann-title"];
        $newB_title = htmlspecialchars($newB_title);
        $newB_title = nl2br($newB_title, true);
        if (strlen($newB_title) > 255) {
            $newB_title = null;
            $errorMess[] = "The bulletin title must be less than 250 characters";
        }
    }
    else {
        $errorMess[] = "You must supply a bulletin title";
    }

    if (isset($_POST["ann-text"]) && !empty($_POST["ann-text"])) {
        $newB_text = $_POST["ann-text"];
        $newB_text = htmlspecialchars($newB_text);
        $newB_text = nl2br($newB_text, true);
    }
    else {
        $errorMess[] = "You must supply a bulletin body";
    }


    if (!empty($newB_title) && !empty($newB_text)) {

        if ($form_type === "ann_create") {
            $result = insertBulletin($newB_title, $newB_text);
            if ($result === false) {
                $errorMess[] = "A <strong>database error</strong> occured while inserting the new bulletin!";
            }
            else {
                $update_annId = 0;
                $newB_title = null;
                $newB_text = null;
                $succMess[] = "<strong>Success</strong> The new bulletin was created";
            }
        }
        else {
            if ($update_annId > 0) {
                //$resetDate = isset($_POST["ann_reset_date"]) && $_POST["ann_reset_date"] === "Yes";
                $resetDate = isset($_POST["ann_reset_date"]);

                $result = updateBulletin($update_annId, $newB_title, $newB_text, $resetDate);

                if ($result === false) {
                    $errorMess[] = "A <strong>database error</strong> occured while updating the bulletin!" .
                    "<br/>(This error might have occured because you clicked " .
                    "'Save Changes' without actually changing anything.)";
                }
                else {
                    $update_annId = 0;
                    $newB_title = null;
                    $newB_text = null;
                    $succMess[] = "<strong>Success</strong> The bulletin was updated";
                }
            }
            else {
                $errorMess[] = "A <strong>code error</strong> occured while updating the bulletin!";
            }
        }
    }
}
else if ($form_type === "ann_delete") {
    if (isset($_POST["ann_id"]) && !empty($_POST["ann_id"]) && intval($_POST["ann_id"] > 0)) {
        $annId = intval($_POST["ann_id"]);

        $result = deleteBulletin($annId);
        if ($result === true) {
            $succMess[] = "<strong>Success</strong> The bulletin was deleted";
        }
        else {
            $errorMess[] = "A <strong>database error</strong> occured while deleting the bulletin!";
        }
    }
    else {
        $errorMess[] = "A <strong>code error</strong> occured while deleting the bulletin!";
    }
}
/*
 */

$bboardContent = getAnnouncements(0);

if (!empty($errorMess)) {
?>
<div class="alert alert-danger">
<strong>Error(s)</strong>
<ul>
<?php
    foreach($errorMess as $mess) {
        echo "<li>$mess</li>";
    }
?>
</ul>
</div>
<?php
}

if (!empty($succMess)) {
?>
<div class="alert alert-success">
<ul>
<?php
    foreach($succMess as $mess) {
        echo "<li>$mess</li>";
    }
?>
</ul>
</div>
<?php
}

?>
<div>
<h1>Bulletin Board Edit Page</h1>

<p class="lead">
All bulletins in the system are listed below.<br/>
Only the most recent <strong>3</strong> will be displayed.
</p>
<?php
if (!empty($bboardContent)) {
    foreach($bboardContent as $bul) {
?>
<div class="st-bulletin-item">
        <button class="btn btn-danger" onmouseup="bboardDeleteToggle(this)" title="Delete Bulletin">
            <i class="icon-trash icon-white"></i>
        </button>

        <button class="btn btn-warning" onmouseup="bboardEditToggle(this)" title="Edit Bulletin">
            <i class="icon-pencil icon-white"></i>
        </button>

        <span class="alert alert-info">
<?php
        $itemTitle = "";
        if (empty($bul["title"])) {
            $itemTitle = "(Untitled)";
        }
        else {
            $itemTitle = $bul["title"];
        }
        if (!empty($bul["date"])) {
            $itemTitle .= "<strong> (created on ". $bul["date"] . ")</strong>";
        }
        echo $itemTitle;
?>
        </span>

<!-- DELETE BULLETIN FORM -->
    <div class="st-bulletin-delete well">
    <div class="alert alert-danger">
        Are you sure you want to delete this bulletin?
    </div>
    <form action="bboard.php" method="post">
        <input type="hidden" name="form_type" value="ann_delete" />
        <input type="hidden" name="ann_id" value="<?php echo $bul["announcement_id"]; ?>">

        <div class="st-bulletin-edit-buttons">
        <input type="submit" value="Delete" class="btn btn-danger" />
        <button type="button" class="btn btn-info" onmouseup="bboardDeleteHide(this)">Cancel</button>
        </div>
    </form>
    </div>

<!-- EDIT BULLETIN FORM -->
<?php
        if ($update_annId == intval($bul["announcement_id"])) {
?>
    <div class="st-bulletin-edit-active well">
<?php
        }
        else {
?>
    <div class="st-bulletin-edit well">
<?php
        }
?>
    <form action="bboard.php" method="post">
        <input type="hidden" name="form_type" value="ann_edit" />
        <input type="hidden" name="ann_id" value="<?php echo $bul["announcement_id"]; ?>">
<?php
        $itemTitle = "";
        if ($update_annId == intval($bul["announcement_id"]) && !empty($newB_title))
        {
            $itemTitle = $newB_title;
        }
        else if (!empty($bul["title"])) {
            $itemTitle = $bul["title"];
        }
        $itemTitle = br2nl($itemTitle);
?>
        <label for="ann_title">Bulletin Title: </label>
        <input type="text" name="ann-title" value="<?php echo $itemTitle; ?>" />

<?php
        $itemText = "";
        if ($update_annId == intval($bul["announcement_id"]) && !empty($newB_text))
        {
            $itemText = $newB_text;
        }
        else if (!empty($bul["text"])) {
            $itemText = $bul["text"];
        }
        $itemText = br2nl($itemText);
?>
        <label for="ann_text">Bulletin Title: </label>
        <textarea name="ann-text" rows="5"><?php echo $itemText; ?></textarea> 
        <br/>

        <input type="checkbox" name="ann_reset_date" />
        <label for="ann_reset_date" class="st-bulletin-edit-resetdate-lbl">Reset to Today's Date</label>

        <div class="st-bulletin-edit-buttons">
        <input type="submit" value="Save Changes" class="btn btn-success" />
        <button type="button" class="btn btn-info" onmouseup="bboardEditHide(this)">Hide</button>
        </div>
    </form>
    </div>
</div>
<?php
    }
}
else {
?>
<div class="alert">
    <button type="button" class="close" data-dismiss="alert">
        &times;
    </button>
    There are currently no bulletins.
</div>
<?php
}

?>
<div>
    <button class="btn btn-success" onmouseup="bboardCreateToggle(this)" title="Add a Bulletin">
        <i class="icon-plus icon-white"></i>
    </button>
<?php
if ($update_annId == 0 && (!empty($newB_title) || !empty($newB_text))) {
?>
    <div class="st-bulletin-create-active well">
<?php
}
else {
?>
    <div class="st-bulletin-create well">
<?php
}
?>
        <form action="bboard.php" method="post">
            <input type="hidden" name="form_type" value="ann_create" />

            <div class="st-bulletin-create-title">
                <input type="text" name="ann-title" placeholder="Bulletin Title"<?php
if (!empty($newB_title) && $update_annId == 0) {
    $newB_title = br2nl($newB_title);
    echo " value=\"$newB_title\" ";
}
?> />
            </div>

            <div class="st-bulletin-create-text">
<?php
if (empty($newB_text) && $update_annId == 0) {
?>
                <textarea name="ann-text" rows="5" placeholder="Bulletin Body"></textarea>
<?php
}
else {
    $newB_text = br2nl($newB_text);
?>
                <textarea name="ann-text" rows="5"><?php echo $newB_text; ?></textarea>
<?php
}
?>
            </div>

            <input type="submit" value="Create" class="btn btn-success" />
        </form>
    </div>
</div>
</div>
