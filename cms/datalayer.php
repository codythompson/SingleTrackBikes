<?php
define("ST_PRODUCT_TYPE_ID_BIKES", 1);

$dbconn_loc = "dbconn.php";
require($dbconn_loc);

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

function getTopLevelProductInfo($intTypeId, $intChildrenDepth) {
    global $mysqli;

    $query = "select * from single_track.product p where p.product_type_id = ?";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $intTypeId);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    return null;
}

function getProductInfo($intParentId, $intChildrenDepth = 1) {

}
?>
