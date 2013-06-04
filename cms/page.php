<?php

/*
 * Echos the HTMl for a web page
 *
 * PARAMS:
 * 
 * title
 * the tile for the html page
 *
 * navLinks
 * An array of associative arrays containing information for the navbar links
 * each associative array should have the following structure
 * "text" => the text to be displayed for the link
 * "hover_title" => the text to be displayed on hover (will be inserted as the
 *                  title attribute). This is optional.
 * "href" => the url for the link (will be inserted as the href attribute).
 * "dropdown_links" => an array of associatiave arrays formatted the same
 *                     as 'navLinks'
 *
 * activeNavLinkIndex
 * The 'navLinks' index of the current page
 *
 * content
 * A string containing the HTML content for the page.
 */
function MakePage($title, $navLinks, $activeNavLinkIndex, $content) {
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
    //This code builds the navbar from the $navLinks array of associative arrays
    //foreach($navLinks as $link) {
    for($i = 0; $i < count($navLinks); $i++) {
        $link = $navLinks[$i];
        $linkString = "<li";
        $isDDL = array_key_exists("dropdown_links", $link);

        $classes = array();
        if ($i == $activeNavLinkIndex) {
            $classes[] = "active";
        }
        if ($isDDL)
        {
            $classes[] = "dropdown";
        }
        if (count($classes) > 0))
        {
            $linkString .= " class=\"";
            for($i = 0; $i < count($classes); $i++)
            {
                $linkString .= $classes[$i];
                if ($i < count($classes) - 1) {
                    $linkString .= " ";
                }
            }
            $linkString .= "\"";
        }

        $href = $link["href"];
        $linkString .= "><a href=\"$href\"";

        if (array_key_exists("hover_title", $link)) {
            $title = $link["hover_title"];
            $linkString .= " title=\"$title\"";
        }

        $text = $link["text"];
        $linkString .= ">$text";

        if ($isDDL)
        {
            $linkString .= "<b class\"caret\"></b>";
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            // Working HERE - need to add UL of dropdown options
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }

        echo $linkString;
    }
?>
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
                                <!-- Like Us on Facebook -->
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
