<?php
define("ST_MISC_TEXT_RACING_NAME", "Racing Text");
define("ST_MISC_TEXT_RACING_TITLE_NAME", "Racing Title");

$racingTitle = getMiscText(ST_MISC_TEXT_RACING_TITLE_NAME);
$racingText = getMiscText(ST_MISC_TEXT_RACING_NAME);
?>

<div class="well st-page-container">

<h1><?php echo $racingTitle; ?></h1>

<?php
echo $racingText;
?>

</div>
