<?php
define("ST_PRODUCT_TYPE_ID_BIKES", 1);

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

function getProductInfo_helper($stmt, &$intParentId, $intChildrenDepth) {
    global $mysqli;

    if ($intChildrenDepth <= 0) {
        return null;
    }

    $stmt->execute();
    $rows = resultToArrayOfAssoc($stmt->get_result());

    for ($i = 0; $i < count($rows); $i++) {
        $row = $rows[$i];
        $intParentId = $row["product_id"];
        $row["child_product"] = getProductInfo_helper($stmt, $intParentId,
            $intChildrenDepth - 1);
        $rows[$i] = $row;
    }

    return $rows;
}

function getProductInfo($intParentId, $intChildrenDepth = 1) {
    global $mysqli;

    if ($intChildrenDepth <= 0) {
        return null;
    }

    $query = "select * from single_track.product p ";
    if (empty($intParentId)) {
        $query .= "where p.product_parent_id is null";
    }
    else { 
        $query .= "where p.product_parent_id = ?";
    }
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $intParentId);
    if ($stmt) {
        return getProductInfo_helper($stmt, $intParentId, $intChildrenDepth);
    }
    else {
        return null;
    }
}

/* Returns an array of HtmlElement objects formatted for use on the
 * bikes.php content page.
 */
function getBikeCOsHtmlObjects() {
    $coInfoArray = getTopLevelProductInfo(ST_PRODUCT_TYPE_ID_BIKES, 1);
    $htmlEles = array();
    foreach($coInfoArray as $coInfo) {
        $rootEle = new HtmlElement("div", null, "media");
    
        $childEle = new HtmlElement("span", null, "pull-left st-imagelink");
        if (!empty($coInfo["offsite_url"])) {
            $childEle->tagName = "a";
            $childEle->setAttribute("href", $coInfo["offsite_url"]);
            $childEle->setAttribute("target", "_blank");
        }
    
        if (!empty($coInfo["logo_url"])) {
            $childEle->childElements[] = new HtmlElement("img", null,
                "media-object");
            $childEle->childElements[0]->setAttribute("src",
                $coInfo["logo_url"]);
        }
        $rootEle->childElements[] = $childEle;

        $childEle = new HtmlElement("div", null, "media-body");
        $childEle->childElements[] = new HtmlElement("h3", null,
            "media-heading", $coInfo["name"]);
        if (!empty($coInfo["short_descr"])) {
            $childEle->childElements[] = new HtmlElement("p", null, null, $coInfo["short_descr"]);
        }
        if (!empty($coInfo["offsite_url"])) {
            $childChildEle = new HtmlElement("p");
            $childChildEle->childElements[] = new HtmlElement("a", null, null,
                "Visit the " . $coInfo["name"] . " home page");
            $childChildEle->childElements[0]->setAttribute("href",
                $coInfo["offsite_url"]);
            $childChildEle->childElements[0]->setAttribute("target", "_blank");
            $childEle->childElements[] = $childChildEle;
        }
        $rootEle->childElements[] = $childEle;

        $htmlEles[] = $rootEle;
    }

    return $htmlEles;
}
?>
