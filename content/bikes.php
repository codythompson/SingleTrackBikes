<?php
require_once("cms/datalayer.php");

$bikeCOsEles = getBikeCOsHtmlObjects();
?>

<div id="container-bikes" class="st-rounded">
    <img src="/images/SDC10465.JPG" alt="Single Track Dealers" class="st-rounded" />

    <div class="st-floating st-rounded">
        <h2>Bikes We Sell</h2>

<?php
foreach ($bikeCOsEles as $ele) {
    $ele->writeElement();
}
?>
    </div>
</div>
