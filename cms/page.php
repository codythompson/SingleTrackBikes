<?php
require_once("navbar.php");

/*
 * Echos the HTMl for a web page
 *
 * PARAMS:
 * 
 * title
 * the tile for the html page
 *
 * navBar
 * The NavBar object that will be used to write the navigation bar html.
 *
 * content
 * A string containing the HTML content for the page.
 */
function MakePage($title, $navBar, $content) {
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <title><?php echo $title; ?></title>

        <link rel="stylesheet" href="styles/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="styles/bootstrap-responsive.css" type="text/css" />
        <link rel="stylesheet" href="styles/singletrack.css" type="text/css" />
    </head>
    <body>
        <div class="container">
            <div class="row">

                <div id="st-left-column" class="span2">
                    <a href="">
                        <h1>
                            <img src="images/single_track_logo_small.jpg"
                            alt="Single Track Bicycle Shop. On the Right Track." />
                        </h1>
                    </a>

                    <div class="hidden-750 st-rounded st-grey">
                        <h3 class="st-red">Twitter Feed goes here</h3>
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
                </div>

                <div class="span10">
                    <ul class="nav nav-pills">
<?php
    $navBar->writeElement();
?>
<!--
                        <li class="dropdown pull-right">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="images/facebook-small.png" alt="facebook" />
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                            <a href="https://www.facebook.com/pages/Single-Track-Bikes/285426214809646"
                                    target="_blank">
                                Facebook Page
                            </a>
                            </li>
                            <li>
                            <a class="fb-like" data-send="false" data-width="450" data-show-faces="false">
<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FSingle-Track-Bikes%2F285426214809646&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>
                            </a>
                            </li>
                        </ul>
                        </li>

                        <li class="dropdown pull-right">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="images/twitter-small.png" alt="twitter" />
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                            <a href="">
                                Twitter Page
                            </a>
                            </li>
                            <li>
                            <a href="">
                                Twitter Feed
                            </a>
                            </li>
                        </ul>
                        </li>
                    </ul>
-->
                </div>
                <!-- end span10 -->

            </div>
            <!-- end main row -->

        </div>
        <!-- end container -->

        <!-- These are here so the rest of the page can be loaded first -->
        <script src="scripts/jquery-1.10.0.min.js" type="text/javascript"></script>
        <script src="scripts/bootstrap.js" type="text/javascript"></script>
        <script src="scripts/singletrack.js" type="text/javascript"></script>

    </body>
</html>
<?php
}
?>
