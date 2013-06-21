<?php
require_once("../cms/datalayer.php");

$errorMess = array();
$succMess = array();

$form_type = null;
if (isset($_POST["form_type"])) {
    $form_type = $_POST["form_type"];
}

$newB_title = null;
$newB_text = null;

if ($form_type === "ann_create") {
    if (isset($_POST["ann-title"]) && !empty($_POST["ann-title"])) {
        $newB_title = $_POST["ann-title"];
        $newB_title = htmlspecialchars($newB_title);
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
    }
    else {
        $errorMess[] = "You must supply a bulletin body";
    }


    if (!empty($newB_title) && !empty($newB_text)) {
        $result = insertBulletin($newB_title, $newB_text);
        if ($result === false) {
            $errorMess[] = "A <strong>database error</strong> occured while inserting the new bulletin!";
        }
        else {
            $succMess[] = "<strong>Success!</strong> The new bulletin was created";
        }
    }
}

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

<?php
if (!empty($bboardContent)) {
    foreach($bboardContent as $bul) {
?>
<div>
    <form action="bboard.php" method="post">
        <input type="hidden" name="form_type" value="ann_delete" />
        <input type="hidden" name="ann_id" value="<?php echo $bul["announcement_id"]; ?>">
        <input type="button" value="&times;" title="Delete this bulletin" class="btn btn-danger" />
        <a class="btn" href="#">
<?php
        if (empty($bul["title"])) {
            echo "(Untitled) ";
            if (!empty($bul["date"])) {
                echo "(created on ". $bul["date"] . ")";
            }
        }
        else {
            echo $bul["title"];
        }
?>
        </a>

    </form>
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
    <button class="btn btn-success" onmouseup="bboardCreateToggle(this)">
        + Add a Bulletin
    </button>
    <div class="st-bulletin-create well">
        <form action="bboard.php" method="post">
            <input type="hidden" name="form_type" value="ann_create" />

            <div class="st-bulletin-create-title">
                <input type="text" name="ann-title" placeholder="Bulletin Title"<?php
if (!empty($newB_title)) {
    echo " value=\"$newB_title\" ";
}
?> />
            </div>

            <div class="st-bulletin-create-text">
<?php
if (empty($newB_text)) {
?>
                <textarea name="ann-text" rows="5" placeholder="Bulletin Body"></textarea>
<?php
}
else {
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
