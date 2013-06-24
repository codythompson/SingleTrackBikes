<?php
define("ST_MISC_TEXT_ABOUT_NAME", "About Us Text");

$aboutText = getMiscText(ST_MISC_TEXT_ABOUT_NAME);
?>

<div class="well st-page-container">
<h1>About Single Track</h1>
<?php echo $aboutText; ?>

<hr />
<div class="st-embedded-container">
<div class="st-embedded st-rounded">
<iframe width="560" height="315" src="http://www.youtube.com/embed/VjZ2R5iRegs" frameborder="0" allowfullscreen></iframe>
</div>
</div>

</div>
