<?php
require_once("error.php");

define("ST_PRODUCT_ID_ROOT", 1);
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

function navLinkExists($linkUrl) {
    global $mysqli;

    $query = "select nl.link_url from single_track.nav_links nl " .
        "where nl.link_url = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("s", $linkUrl);
    $stmt->execute();
    $result = fetchRows($stmt);
    return !empty($result);
}

function footerLinkExists($linkUrl) {
    global $mysqli;

    $query = "select nl.link_url from single_track.footer_links nl " .
        "where nl.link_url = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("s", $linkUrl);
    $stmt->execute();
    $result = fetchRows($stmt);
    return !empty($result);
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

function getContentItems($limit = 0) {
    global $mysqli;

    $query = " select ci.*, cil.css_location_class, cil.name as loc_name " .
        "from single_track.content_item ci " .
        "left join single_track.content_item_location cil " .
        "on ci.content_item_location_id = cil.content_item_location_id " .
        "order by order_num, content_item_id ";
    if ($limit > 0) {
        $query .= "limit ?";
    }
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    if ($limit > 0) {
        $stmt->bind_param("i", $limit);
    }
    $stmt->execute();
    return fetchRows($stmt);
}

function getContentItemLocations() {
    global $mysqli;

    $query = "select * from single_track.content_item_location";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->execute();
    return fetchRows($stmt);
}

function getAnnouncements($limit = 3) {
    global $mysqli;

    $query = "select " .
        "a.announcement_id,  " .
        "a.title,  " .
        "a.text,  " .
        "DATE_FORMAT(a.date, '%c/%e/%Y %l:%i') as date " .
        "from announcements a " .
        "order by a.date desc ";
    if ($limit > 0) {
        $query .= "limit ?";
    }
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    if ($limit > 0) {
        $stmt->bind_param("i", $limit);
    }
    $stmt->execute();

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

    $queryParent = "select " .
        "p.*, " .
        "pp.name as parent_name ". 
        "from single_track.product p " .
        "left join single_track.product pp " .
        "on p.parent_product_id = pp.product_id " .
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

function getProductStyleInfo() {
    global $mysqli;

    $query = "select * from single_track.product_style ";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->execute();
    return fetchRows($stmt);
}

function getMiscText($miscTextName) {
    global $mysqli;

    $query = "select * from single_track.misc_text mt " .
        "where LOWER(mt.misc_text_name) = LOWER(?) ";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->bind_param("s", $miscTextName);
    $stmt->execute();
    $result = fetchRows($stmt);
    if (count($result) == 1) {
        return $result[0]["value"];
    }
    else return null;
}

function getAllMiscText() {
    global $mysqli;

    $query = "select * from single_track.misc_text";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->execute();
    return fetchRows($stmt);
}

function getPageContent($pageContentId) {
    global $mysqli;

    $query = "select * from single_track.page_content pc ".
        "where pc.page_content_id = ? ";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->bind_param("i", $pageContentId);
    $stmt->execute();
    $result = fetchRows($stmt);
    if (count($result) > 0) {
        return $result[0];
    }
    else {
        return null;
    }
}

function getAllPageContent() {
    global $mysqli;

    $query = "select * from single_track.page_content";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return null;
    }
    $stmt->execute();
    return fetchRows($stmt);
}

/*
 * Setters
 */

function insertBulletin($title, $text) {
    global $mysqli;

    if (empty($title) || empty($text)) {
        return false;
    }

    $query = "insert into single_track.announcements " .
        "(title, `text`, `date`) " .
        "values (?, ?, NOW())";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("ss", $title, $text);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function updateBulletin($annId, $title, $text, $resetDate) {
    global $mysqli;

    $query = "update single_track.announcements " .
        "set title = ?, " .
        "text = ? ";
    if ($resetDate) {
        $query .= ", date = NOW() ";
    }
    $query .= "where announcement_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("ssi", $title, $text, $annId);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function deleteBulletin($annId) {
    global $mysqli;

    $query = "delete from single_track.announcements " .
        "where announcement_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("i", $annId);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function addContentItem($locationId, $title, $content, $bgUrl, $bgAlt) {
    global $mysqli;

    $query = "insert into single_track.content_item " .
        "(content_item_location_id, title, content, bg_image_url, bg_image_alt) " .
        "values (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("issss", $locationId, $title, $content, $bgUrl, $bgAlt);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function editContentItem($itemId, $locationId, $title, $content, $bgUrl, $bgAlt) {
    global $mysqli;

    $query = "update single_track.content_item " .
        "set content_item_location_id = ? ," .
        "title = ?, " .
        "content = ?, " .
        "bg_image_url = ?, " .
        "bg_image_alt = ? " .
        "where content_item_id = ? ";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("issssi", $locationId, $title, $content, $bgUrl, $bgAlt,
        $itemId);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function deleteContentItem($itemId) {
    global $mysqli;

    $query = "delete from single_track.content_item " .
        "where content_item_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    return $stmt->affected_rows == 1;
}

function reorderContentItemUp($upItemId) {
    global $mysqli;

    $items = getContentItems();
    
    $foundItem = false;
    for($i = 1; $i < count($items) && !$foundItem; $i++) {
        $item = $items[$i];

        if (intval($item["content_item_id"]) == $upItemId) {
            $foundItem = true;
            $tmp = $items[$i - 1];
            $items[$i - 1] = $item;
            $items[$i] = $tmp;
        }
    }
    if (!$foundItem) {
        return false;
    }

    $query = "update single_track.content_item " .
        "set order_num = ? " .
        "where content_item_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $curId = 0;
    $i = 0;
    $stmt->bind_param("ii", $i, $curId);
    while ($i < count($items)) {
        $item = $items[$i];
        $curId = $item["content_item_id"];
        $stmt->execute();
        $i++;
    }

    return $stmt->affected_rows > 0;
}

function reorderContentItemDown($downItemId) {
    global $mysqli;

    $items = getContentItems();
    
    $foundItem = false;
    for($i = 0; $i < count($items) - 1 && !$foundItem; $i++) {
        $item = $items[$i];

        if (intval($item["content_item_id"]) == $downItemId) {
            $foundItem = true;
            $tmp = $items[$i + 1];
            $items[$i + 1] = $item;
            $items[$i] = $tmp;
        }
    }
    if (!$foundItem) {
        return false;
    }

    $query = "update single_track.content_item " .
        "set order_num = ? " .
        "where content_item_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $curId = 0;
    $i = 0;
    $stmt->bind_param("ii", $i, $curId);
    while ($i < count($items)) {
        $item = $items[$i];
        $curId = $item["content_item_id"];
        $stmt->execute();
        $i++;
    }

    return $stmt->affected_rows > 0;
}

function updateProductInfo($pId, $styleId, $name, $descr, $longDescr,
    $offsiteUrl, $offsiteUrlText, $imageUrl, $bgImageUrl) {

    global $mysqli;

    $query = "update single_track.product " .
        "set product_style_id = ?, " .
        "name = ?, " .
        "descr = ?, " .
        "long_descr = ?, " .
        "offsite_url = ?, " .
        "offsite_url_text = ?, " .
        "image_url = ?, " .
        "background_image_url = ? " .
        "where product_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("isssssssi", $styleId, $name, $descr, $longDescr,
        $offsiteUrl, $offsiteUrlText, $imageUrl, $bgImageUrl, $pId);
    $stmt->execute();
    return $stmt->affected_rows == 1;
}

function insertNewProduct($parentId, $selStyle, $name, $descr, $longDescr,
    $offsiteUrl, $offsiteUrlText, $imageUrl, $bgImageUrl) {

    global $mysqli;

    $query = "insert into single_track.product " .
        "(parent_product_id, product_style_id, name, descr, long_descr, ". 
        "offsite_url, offsite_url_text, image_url, background_image_url) ". 
        "values (?, ?, ?, ? ,?, ?, ?, ?, ?) ";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("iisssssss", $parentId, $selStyle, $name, $descr,
        $longDescr, $offsiteUrl, $offsiteUrlText, $imageUrl, $bgImageUrl);
    $stmt->execute();
    return $stmt->affected_rows == 1;
}

function deleteProduct($productId) {
    global $mysqli;

    //remove references
    $query = "update single_track.product " .
        "set parent_product_id = null " .
        "where parent_product_id = ? ";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $orphanCount = $stmt->affected_rows;

    //delete product
    $query = "delete from single_track.product " .
        "where product_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("i", $productId);
    $stmt->execute();

    return $orphanCount;
}

function getOrphanedProducts() {
    global $mysqli;

    $query = "select p.product_id, p.name from single_track.product p " .
        "where p.parent_product_id is null " .
        "and p.product_id <> 1";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->execute();
    return fetchRows($stmt);
}

function setParent($productId, $parentId) {
    global $mysqli;

    $query = "update single_track.product ".
        "set parent_product_id = ? " .
        "where product_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("ii", $parentId, $productId);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function updateMiscText($miscTextName, $value) {
    global $mysqli;

    $query = "update single_track.misc_text " .
        "set value = ? " .
        "where LOWER(misc_text_name) = LOWER(?)";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("ss", $value, $miscTextName);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function insertPageContent($pageTitle, $pageHead, $pageContent) {
    global $mysqli;

    $query = "insert into single_track.page_content " .
        "(page_title, page_heading, page_content) " .
        "values(?, ?, ?)";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("sss", $pageTitle, $pageHead, $pageContent);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function updatePageContent($pageId, $pageTitle, $pageHead, $pageContent) {
    global $mysqli;

    $query = "update single_track.page_content " .
        "set page_title = ?, " .
        "page_heading = ?, " .
        "page_content = ? " .
        "where page_content_id = ?";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("sssi", $pageTitle, $pageHead, $pageContent, $pageId);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function deletePageContent($pageId) {
    global $mysqli;

    $linkUrl = getNavLinkUrl($pageId);
    if (navLinkExists($linkUrl)) {
        $delResult = deleteFromNavBar($pageId);
        if ($delResult !== true) {
            return false;
        }
    }
    if (footerLinkExists($linkUrl)) {
        $delResult = deleteFromFooterLinks($pageId);
        if ($delResult !== true) {
            return false;
        }
    }

    $query = "delete from single_track.page_content " .
        "where page_content_id = ?";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("i", $pageId);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function addNavLink($linkUrl, $linkText) {
    global $mysqli;

    $query = "insert into single_track.nav_links " .
        "(link_url, link_text) " .
        "values (?, ?)";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("ss", $linkUrl, $linkText);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function addFooterLink($linkUrl, $linkText) {
    global $mysqli;

    $query = "insert into single_track.footer_links " .
        "(link_url, link_text) " .
        "values (?, ?)";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $stmt->bind_param("ss", $linkUrl, $linkText);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function deleteFromNavBar($pageId) {
    global $mysqli;

    $query = "delete from single_track.nav_links " .
        "where link_url = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $linkUrl = getNavLinkUrl($pageId);
    $stmt->bind_param("s", $linkUrl);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function deleteFromFooterLinks($pageId) {
    global $mysqli;

    $query = "delete from single_track.footer_links " .
        "where link_url = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        handleError($mysqli->error);
        return false;
    }
    $linkUrl = getNavLinkUrl($pageId);
    $stmt->bind_param("s", $linkUrl);
    $stmt->execute();

    return $stmt->affected_rows == 1;
}

function getNavLinkUrl($pageId) {
    return "/page.php?page_content_id=$pageId";
}
?>
