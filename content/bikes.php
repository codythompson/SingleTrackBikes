<?php
require_once("cms/datalayer.php");

$bikes_info = getTopLevelProductInfo(ST_PRODUCT_TYPE_ID_BIKES, 1);
?>

<div id="container-bikes" class="st-rounded">
    <img src="/images/SDC10465.JPG" alt="Single Track Dealers" class="st-rounded" />

    <div class="st-floating st-rounded">
        <h2>Bikes We Sell</h2>

<?php
foreach ($bikes_info as $co_info) {
?>
        <div class="media">
            <a class="pull-left st-imagelink" href="<?php ?>
        </div>
<?php
}
?>

        <div class="media">
            <a class="pull-left st-imagelink" href="http://trekbikes.com" target="_blank">
                <img src="images/trek-logo-large-white-text.png" class="media-object" />
            </a>
            <div class="media-body">
                <h3 class="media-heading">Trek</h3>
                <p>
                    Trek description goes here
                    Cras sit amet nibh libero, in gravida nulla. Nulla vel metus
                    scelerisque ante sollicitudin commodo. Cras purus odio,
                    vestibulum in vulputate at, tempus viverra turpis.
                </p>
                <p>
                    <a href="http://trekbikes.com" target="_blank">
                        Visit the Trek home page
                    </a>
                </p>
            </div>
        </div>

        <div class="media">
            <a class="pull-left st-imagelink" href="http://surlybikes.com" target="_blank">
                <img src="images/surly-logo.gif" class="media-object" />
            </a>
            <div class="media-body">
                <h3 class="media-heading">Surly</h3>
                <p>
                    Surly description goes here
                    Cras sit amet nibh libero, in gravida nulla. Nulla vel metus
                    scelerisque ante sollicitudin commodo. Cras purus odio,
                    vestibulum in vulputate at, tempus viverra turpis.
                </p>
                <p>
                    <a href="http://surlybikes.com/" target="_blank">
                        Visit the Surly home page
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
