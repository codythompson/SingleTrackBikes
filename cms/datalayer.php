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

// adapted from http://php.net/manual/en/mysqli-stmt.bind-result.php#92505
function fetchRows($stmt) {
    $meta = $stmt->result_metadata(); 
    while ($field = $meta->fetch_field()) 
    { 
        $params[] = &$row[$field->name]; 
    } 

    call_user_func_array(array($stmt, 'bind_result'), $params); 

    while ($stmt->fetch()) { 
        foreach($row as $key => $val) 
        { 
            $c[$key] = $val; 
        } 
        $result[] = $c; 
    } 
    if (!isset($result)) {
        return null;
    }

    return $result;
}

function GetNavLinks() {
    global $mysqli;

    $query = "select * from nav_links nl " .
        "where parent_nav_link_id is null";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->execute();
    $result = fetchRows($stmt);
    /* Apparently get_result() isn't supported on the server
    $result = $stmt->get_result();
    $result = resultToArrayOfAssoc($result);
     */
    if (empty($result)) {
        return null;
    }
    $stmt->close();

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
        $children = fetchRows($stmt);
        //$children = resultToArrayOfAssoc($stmt->get_result());
        if (count($children) > 0) {
            $row["children"] = $children;
        }
        $result[$i] = $row;
    }

    return $result;
}

function getFooterLinks() {
    global $mysqli;

    $query = " select * from single_track.footer_links fl " .
        "order by fl.`order`, fl.footer_link_id";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->execute();
    /*
    $result = resultToArrayOfAssoc($stmt->get_result());
     */
    $result = fetchRows($stmt);
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
    /*
    return resultToArrayOfAssoc($stmt->get_result());
     */
    return fetchRows($stmt);
}

/*
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
 */

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
    $result = fetchRows($stmt);
    /*
    $result = resultToArrayOfAssoc($stmt->get_result());
     */
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
        //$result["child_product"] = resultToArrayOfAssoc($stmt->get_result());
        $result["child_product"] = fetchRows($stmt);
    }

    return $result;
}
?>
