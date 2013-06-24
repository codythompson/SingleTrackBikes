<?php
require_once("navbar.php");
require_once("announcements.php");
require_once("datalayer.php");

define("ST_MISC_TEXT_FOOTER_NAME", "Footer Text");
/*
 * Echos the HTMl for a web page
 *
 * PARAMS:
 * 
 * title
 * the tile for the html page
 *
 * cssHrefs
 * an array of filepaths of css files to be added to the head section
 *
 * jsSrcs
 * an array of filepaths of javascript files to be added to the head section
 *
 * navBar
 * The NavBar object that will be used to write the navigation bar html.
 *
 * content
 * A string filepath of the html/php content file to be included
 */
function MakePage($title, $cssHrefs, $jsSrcs, $navBar, $content,
    $footerLinksInfo) {

    $ann = getAnnouncements(3);
    $ann = new Announcements("st-anns", $ann);

    $footerText = getMiscText(ST_MISC_TEXT_FOOTER_NAME);
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <title><?php echo $title; ?></title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="styles/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="styles/bootstrap-responsive.css" type="text/css" />
        <link rel="stylesheet" href="styles/singletrack.css" type="text/css" />
        <!--[if lte IE 8]>
        <style type="text/css">
            .st-carousel-caption {
                background-color:#444;
            }

            .st-floating {
                background-color:#444;
            }
        </style>
        <![endif]-->
<?php
    foreach($cssHrefs as $href) {
        echo "<link rel=\"stylesheet\" href=\"$href\" type=\"text/css\" />";
    }
?>

        <script src="scripts/jquery-1.10.0.min.js" type="text/javascript"></script>
        <script src="scripts/bootstrap.js" type="text/javascript"></script>
        <script src="scripts/bootstrap-carousel-withfix.js" type="text/javascript"></script>
        <script src="scripts/singletrack.js" type="text/javascript"></script>
<?php
    foreach($jsSrcs as $src) {
        echo "<script src=\"$src\" type=\"text/javascript\"></script>";
    }
?>
    </head>
    <body>
        <div class="container">
            <div class="row">

                <div id="st-left-column" class="span3">
                    <h1 id="st-logo">
                        <a href="/">
                            <img src="images/st_logo_no_fill.png"
                                alt="Single Track Bicycle Shop. On the Right Track."
                                class="st-rounded" />
                        </a>
                    </h1>

<?php
    $ann->writeElement();
?>
<!--
                    <div class="hidden-750 well">
                        <h3 class="st-red">Announcements</h3>
                        <hr>
                        <div>
                            <a>Something happened on twitter</a>
                        </div>
                        Something Something and Something Else. This is Filler Text.
                        <hr>
                        <div>
                            <a>Something happened on twitter</a>
                        </div>
                        Something Something and Something Else. This is Filler Text.
                        <hr>
                        <div>
                            <a>Something happened on twitter</a>
                        </div>
                        Something Something and Something Else. This is Filler Text.
                    </div>
-->
                </div>

                <div class="span9">
<?php
    $navBar->writeElement();

    if (!empty($content)) {
        require($content);
    }
?>
                </div>
                <!-- end span10 -->

            </div>
            <!-- end main row -->

            <div class="well st-footer">
<?php
    echo $footerText;
?>
                <hr />
<?php
    foreach($footerLinksInfo as $fLink) {
        echo "<a href=\"" . $fLink["link_url"] . "\">" . $fLink["link_text"] . 
            "</a>";
    }
?>
            </div>

        </div>
        <!-- end container -->

    </body>
</html>
<?php
}
?>
