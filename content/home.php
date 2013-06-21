<?php

require_once("cms/datalayer.php");
require_once("cms/carousel.php");

$contentItems = getContentItems();
$carousel = new Carousel("big-carousel", $contentItems);

$carousel->writeElement();
?>
