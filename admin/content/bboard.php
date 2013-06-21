<?php
require_once("../../cms/datalayer.php");

$bboardContent = getAnnouncements();
?>
<div class="container">
<h1>Bulletin Board Edit Page</h1>

<?php
if (!empty($bboardContent)) {
    foreach($bboardContent as $bul) {
?>
<div class="row">
    <div class="span6">
        <a href="#">
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
    </div>
</div>
<?php
    }
}
else {
?>
<div class="alert">
    <button type="button" class="close pull-left" data-dismiss="alert">
        &times;
    </button>
    There are currently no bulletins.
</div>
<?php
}
?>
</div>
<!-- end container -->
