<?php
require_once("../cms/datalayer.php");

$bboardContent = getAnnouncements();
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
    <a href="#" class="btn btn-info">+ Add a Bulletin</a>
</div>
</div>
