<?php
function GetNavLinks() {
    $navLinks = array(
        array("text" => "Home",
            "hover_title" => "home page",
            "href" => "/"),
        array("text" => "Bikes",
            "hover_title" => "Bikes we carry",
            "href" => "/bikes.php" /*,
            "dropdown_links" => array(
                array(
                    "text" => "All Companies",
                    "href" => "/bikes.php"),
                array(
                    "text" => "Test 2",
                    "hover_title" => "Test Link 2",
                    "href" => "/")
            )
        ),
        array("text" => "Parts",
            "hover_title" => "Parts we carry",
            "href" => "/"),
        array("text" => "Gear",
            "hover_title" => "Other stuff we carry",
            "href" => "/")
        */)
    );
    return $navLinks;
}
?>
