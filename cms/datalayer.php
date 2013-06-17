<?php
require_once("error.php");

define("ST_PRODUCT_ID_ROOT", 2);
define("ST_PRODUCT_ID_BIKES", 2);

$dbconn_loc = "dbconn.php";
require($dbconn_loc);

require_once("htmlelement.php");

function resultToArrayOfAssoc($result) {
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

function removeQueryString($url) {
    $indexOfQ = strpos($url, "?");
    if ($indexOfQ === false) {
        return $url;
    }
    $result = substr($url, 0, $indexOfQ);
    if ($result === false) {
        return $url;
    }
    return $result;
}

function GetNavLinks() {
    /*
    $navLinks = array(
        array("text" => "Home",
            "hover_title" => "home page",
            "href" => "/"),
        array("text" => "Bikes",
            "hover_title" => "Bikes we carry",
            "href" => "/bikes.php" ,
            "dropdown_links" => array(
                array(
                    "text" => "All Companies",
                    "href" => "/bikes.php"),
                array(
                    "text" => "Trek",
                    "hover_title" => "Trek bikes we carry",
                    "href" => "/product.php")
            )
        ),
        array("text" => "Parts",
            "hover_title" => "Parts we carry",
            "href" => "/"),
        array("text" => "Gear",
            "hover_title" => "Other stuff we carry",
            "href" => "/")
        );
    return $navLinks;
     */

    global $mysqli;

    $query = "select * from nav_links nl " .
        "where parent_nav_link_id is null";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $result = resultToArrayOfAssoc($result);
    if (empty($result)) {
        return null;
    }

    $childQuery = "select * from nav_links nl " .
        "where parent_nav_link_id = ?";
    $parentId;
    $stmt = $mysqli->prepare($childQuery);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->bind_param("i", $parentId);
    for($i = 0; $i < count($result); $i++) {
        $row = $result[$i];
        $parentId = intval($row["nav_link_id"]);
        $stmt->execute();
        $children = resultToArrayOfAssoc($stmt->get_result());
        if (count($children) > 0) {
            $row["children"] = $children;
        }
        $result[$i] = $row;
    }

    return $result;
}

function getContentItems($limit = 10) {
    global $mysqli;

    $query = " select ci.*, cil.css_location_class " .
        "from single_track.content_item ci " .
        "left join single_track.content_item_location cil " .
        "on ci.content_item_location_id = cil.content_item_location_id " .
        "order by order_num, content_item_id " .
        "limit ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return resultToArrayOfAssoc($stmt->get_result());
}

function getTopLevelProductInfo($intTypeId, $intChildrenDepth) {
    global $mysqli;

    $query = "select * from single_track.product p where p.product_type_id = ?";
    $query .= " and p.product_parent_id is null";
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

function getProductTreeInfo_helper($stmt, &$intParentId, $intChildrenDepth) {
    global $mysqli;

    if ($intChildrenDepth <= 0) {
        return null;
    }

    $stmt->execute();
    $rows = resultToArrayOfAssoc($stmt->get_result());

    for ($i = 0; $i < count($rows); $i++) {
        $row = $rows[$i];
        $intParentId = $row["product_id"];
        $row["child_product"] = getProductInfoTree_helper($stmt, $intParentId,
            $intChildrenDepth - 1);
        $rows[$i] = $row;
    }

    return $rows;
}

function getProductInfoTree($intParentId, $intChildrenDepth = 1) {
    global $mysqli;

    if ($intChildrenDepth <= 0) {
        return null;
    }

    if ($intParentId == 0) {
        $intParentId = null;
    }

    $query = "select * from single_track.product p ";
    if (empty($intParentId)) {
        $query .= "where p.parent_product_id is null";
    }
    else { 
        $query .= "where p.parent_product_id = ?";
    }
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $intParentId);
    if ($stmt) {
        return getProductInfoTree_helper($stmt, $intParentId, $intChildrenDepth);
    }
    else {
        return null;
    }
}

function getProductInfo($intProductId, $boolGetChildren) {
    global $mysqli;

    $pid = intval($intProductId);

    $queryParent = "select * from single_track.product p " .
        "where p.product_id = ?";
    $stmt = $mysqli->prepare($queryParent);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = resultToArrayOfAssoc($stmt->get_result());
    if (empty($result)) {
        return null;
    }
    $result = $result[0];

    if ($boolGetChildren) {
        $queryChild = "select * from single_track.product p " .
            "where p.parent_product_id = ?";
        $stmt = $mysqli->prepare($queryChild);
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $result["child_product"] = resultToArrayOfAssoc($stmt->get_result());
    }

    return $result;
}
?>
