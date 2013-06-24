<?php
$pcId = 0;
if (isset($_GET["page_content_id"])) {
    $pcId = $_GET["page_content_id"];
}
$pageInfo = getPageContent($pcId);
?>

<div class="well">
<?php
echo "<h1>" . $pageInfo["page_heading"] . "</h1>";
?>
<hr/>
<?php
echo $pageInfo["page_content"];
?>
</div>
