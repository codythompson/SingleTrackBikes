<?php
define("ST_PRODUCT_STYLE_LARGE_BG_SCROLL", 0);
define("ST_PRODUCT_STYLE_CAROUSEL", 1);
define("ST_PRODUCT_STYLE_TILES", 2);

define("ST_PRODUCT_MISSING_NAME_TEXT", "Products sold at Single Track");
define("ST_PRODUCT_MISSING_OFFSITE_URL_TEXT", "Visit the dealer's website");

define("ST_PRODUCT_URL", "/product.php");

class Product extends HtmlElement {
    /*
     * Functions for building the Larger background scroll style product page
     */
    private function getProductItem_LargeBGScroll($title, $descr, $imageUrl,
        $offsiteLinkUrl, $offsiteLinkText, $onsiteLinkUrl) {
        $itemEleChildren = array();

        //make the product image element(s)
        if (!empty($imageUrl)) {
            $imageEle;
            if (!empty($onsiteLinkUrl)) {
                $imageEle = new HtmlElement("a", null,
                    "pull-left st-product-imagelink"); 
                $imageEle->setAttribute("href", $onsiteLinkUrl);
                $imageEle->childElements[] = new HtmlElement("img", null,
                    "media-object");
                $imageEle->childElements[0]->setAttribute("src", $imageUrl);
            }
            else {
                $imageEle = new HtmlElement("img", null); //,
                    //"pull-left media-object");
                $imageEle->setAttribute("src", $imageUrl);
            }
            $itemEleChildren[] = new HtmlElement("div", null,
                "product-imagecontainer pull-left media-object", null, array($imageEle));
        }

        // make the product item body
        $bodyChildren = array();

        if (!empty($title)) {
            $titleChilds = array();
            $titleText = $title;
            if (!empty($onsiteLinkUrl)) {
                $titleChilds[] = new HtmlElement("a", null, null, $title);
                $titleChilds[0]->setAttribute("href", $onsiteLinkUrl);
                $titleText = null;
            }
            $bodyChildren[] = new HtmlElement("h3", null, "media-heading",
                $titleText, $titleChilds);
        }
        if (!empty($descr)) {
            $bodyChildren[] = new HtmlElement("div", null, null, $descr);
        }
        if (!empty($offsiteLinkUrl)) {
            if (empty($offsiteLinkText)) {
                $offsiteLinkText = ST_PRODUCT_MISSING_OFFSITE_URL_TEXT;
            }
            $offLinkEle = new HtmlElement("a", null, null, $offsiteLinkText);
            $offLinkEle->setAttribute("href", $offsiteLinkUrl);
            $offLinkEle->setAttribute("target", "_blank");
            $bodyChildren[] = new HtmlElement("div", null, null, null,
                array($offLinkEle));
        }

        $bodyEle = new HtmlElement("div", null, "media-body", null,
            $bodyChildren);
        $itemEleChildren[] = $bodyEle;

        return new HtmlElement("div", null, "media", null, $itemEleChildren);
    }

    private function build_LargeBGScroll($cssId, $parentProduct) {
        $name = ST_PRODUCT_STYLE_TILES;
        if (!empty($parentProduct["name"])) {
            $name = $parentProduct["name"];
        }

        $baseChildren = array();

        if (!empty($parentProduct["background_image_url"])) {
            $bgImage = new HtmlElement("img", null,
                "product-bgimage st-rounded");
            $bgImage->setAttribute("src",
                $parentProduct["background_image_url"]);
            $bgImage->setAttribute("alt", $name);
            $baseChildren[] = $bgImage;
        }

        $innerContEles = array();

        $innerContEles[] = new HtmlElement("h2", null, null, $name);
        $innerContEles[] = new HtmlElement("hr");

        if (empty($parentProduct["child_product"])) {
            $descr = null;
            if (!empty($parentProduct["long_descr"])) {
                $descr = $parentProduct["long_descr"];
            }
            else if (!empty($parentProduct["descr"])) {
                $descr = $parentProduct["descr"];
            }
            $imageUrl = null;
            if (!empty($parentProduct["image_url"]))
                $imageUrl = $parentProduct["image_url"];
            $offsiteUrl = null;
            if (!empty($parentProduct["offsite_url"]))
                $offsiteUrl = $parentProduct["offsite_url"];
            $offsiteText = null;
            if (!empty($parentProduct["offsite_url_text"]))
                $offsiteText = $parentProduct["offsite_url_text"];

            $innerContEles[] = $this->getProductItem_LargeBGScroll(null, $descr,
                $imageUrl, $offsiteUrl, $offsiteText, null);
        }
        else {
            if (!empty($parentProduct["long_descr"])) {
                $innerContEles[] = new HtmlElement("div", null, "product-longdescr",
                    $parentProduct["long_descr"]);
                $innerContEles[] = new HtmlElement("hr");
            }
            for($i = 0; $i < count($parentProduct["child_product"]); $i++) {
                $childInfo = $parentProduct["child_product"][$i];
                $name = ST_PRODUCT_MISSING_NAME_TEXT;
                if (!empty($childInfo["name"]))
                    $name = $childInfo["name"];
                $descr = null;
                if (!empty($childInfo["descr"]))
                    $descr = $childInfo["descr"];
                $imageUrl = null;
                if (!empty($childInfo["image_url"]))
                    $imageUrl = $childInfo["image_url"];
                $offsiteUrl = null;
                if (!empty($childInfo["offsite_url"]))
                    $offsiteUrl = $childInfo["offsite_url"];
                $offsiteText = ST_PRODUCT_MISSING_OFFSITE_URL_TEXT;
                if (!empty($childInfo["offsite_url_text"]))
                    $offsiteText = $childInfo["offsite_url_text"];
                $productId = 0;
                if (!empty($childInfo["product_id"]))
                    $productId = intval($childInfo["product_id"]);

                $innerContEles[] = $this->getProductItem_LargeBGScroll($name,
                    $descr, $imageUrl, $offsiteUrl, $offsiteText,
                    ST_PRODUCT_URL . "?product_id=$productId");

                if ($i < count($parentProduct["child_product"]) - 1) {
                    $innerContEles[] = new HtmlElement("hr");
                }
            }
        }

        $baseChildren[] = new HtmlElement("div", null,
            "product-innercontainer st-rounded", null, $innerContEles);

        parent::__construct("div", $cssId,
            "st-product-lgbgscroll st-product st-rounded",
            null, $baseChildren);
    }

    /*
     * Carousel page style builder functions
     */
    private function getItem_Carousel($title, $descr, $imageUrl, $bgImageUrl,
        $offsiteLinkUrl, $offsiteLinkText, $onsiteLinkUrl,
        $isActive = false) {

        $itemEles = array();

        if (!empty($bgImageUrl)) {
            $bgImage = new HtmlElement("img", null,
                "product-bgimage st-rounded");
            $bgImage->setAttribute("src", $bgImageUrl);
            $bgImage->setAttribute("alt", $title);
            $itemEles[] = $bgImage;
        }

        $contentEles = array();

        if (!empty($imageUrl)) {
            $imgEle = new HtmlElement("img", null, "product-image");
            $imgEle->setAttribute("src", $imageUrl);
            if (!empty($title)) {
                $imgEle->setAttribute("alt", $title);
            }

            if (empty($onsiteLinkUrl)) {
                $contentEles[] = new HtmlElement("span", null,
                    "product-imagecont pull-left", null, array($imgEle));
            }
            else {
                $imgLink = new HtmlElement("a", null,
                    "product-imagecont pull-left", null, array($imgEle));
                $imgLink->setAttribute("href", $onsiteLinkUrl);
                $contentEles[] = $imgLink;
            }
        }

        if (!empty($title)) {
            if (empty($onsiteLinkUrl)) {
                $contentEles[] = new HtmlElement("h3", null, null, $title);
            }
            else {
                $onLink = new HtmlElement("a", null, null, $title);
                $onLink->setAttribute("href", $onsiteLinkUrl);
                $contentEles[] = new HtmlElement("h3", null, null, null,
                    array($onLink));
            }
        }
        if (!empty($descr)) {
            $contentEles[] = new HtmlElement("p", null, null, $descr);
        }
        if (!empty($offsiteLinkUrl)) {
            $offLink = new HtmlElement("a");
            $offLink->setAttribute("href", $offsiteLinkUrl);
            $offLink->setAttribute("target", "_blank");
            if (empty($offsiteLinkText)) {
                $offLink->text = ST_PRODUCT_MISSING_OFFSITE_URL_TEXT;
            }
            else {
                $offLink->text = $offsiteLinkText;
            }
            $contentEles[] = new HtmlElement("p", null, null, null,
                array($offLink));
        }

        $itemEles[] = new HtmlElement("div", null,
            "carousel-caption st-rounded", null, $contentEles); 

        $cssClass = "item";
        if ($isActive) {
            $cssClass .= " active";
        }

        return new HtmlElement("div", null, $cssClass, null, $itemEles);
    }

    private function build_Carousel($cssId, $parentProduct) {
        $baseChildren = array();

        $name = ST_PRODUCT_MISSING_NAME_TEXT;
        if (!empty($parentProduct["name"])) {
            $name = $parentProduct["name"];
        }

        $titleChildren = array();
        $titleChildren[] = new HtmlElement("h2", null, null, $name);
        
        $carouselItems = array();
        if (empty($parentProduct["child_product"])) {
            $baseChildren[] = new HtmlElement("div", null,
                "product-title st-rounded", null, $titleChildren);

            $descr = null;
            if (!empty($parentProduct["long_descr"])) {
                $descr = $parentProduct["long_descr"];
            }
            else if (!empty($parentProduct["descr"])) {
                $descr = $parentProduct["descr"];
            }
            $imageUrl = null;
            if (!empty($parentProduct["image_url"]))
                $imageUrl = $parentProduct["image_url"];
            $bgImageUrl = null;
            if (!empty($parentProduct["background_image_url"])) {
                $bgImageUrl = $parentProduct["background_image_url"];
            }
            $offsiteUrl = null;
            if (!empty($parentProduct["offsite_url"]))
                $offsiteUrl = $parentProduct["offsite_url"];
            $offsiteText = null;
            if (!empty($parentProduct["offsite_url_text"]))
                $offsiteText = $parentProduct["offsite_url_text"];

            $carouselItems[] = $this->getItem_Carousel(null, $descr, $imageUrl,
                $bgImageUrl, $offsiteUrl, $offsiteText, null, true);
        }
        else {
            if (!empty($parentProduct["long_descr"])) {
                $titleChildren[] = new HtmlElement("div", null, null,
                    $parentProduct["long_descr"]);
            }
            $baseChildren[] = new HtmlElement("div", null,
                "product-title st-rounded", null, $titleChildren);

            for($i = 0; $i < count($parentProduct["child_product"]); $i++) {
                $childInfo = $parentProduct["child_product"][$i];
                $name = null;
                if (!empty($childInfo["name"]))
                    $name = $childInfo["name"];
                $descr = null;
                if (!empty($childInfo["descr"]))
                    $descr = $childInfo["descr"];
                $imageUrl = null;
                if (!empty($childInfo["image_url"]))
                    $imageUrl = $childInfo["image_url"];
                $bgImageUrl = null;
                if (!empty($childInfo["background_image_url"])) {
                    $bgImageUrl = $childInfo["background_image_url"];
                }
                $offsiteUrl = null;
                if (!empty($childInfo["offsite_url"]))
                    $offsiteUrl = $childInfo["offsite_url"];
                $offsiteText = ST_PRODUCT_MISSING_OFFSITE_URL_TEXT;
                if (!empty($childInfo["offsite_url_text"]))
                    $offsiteText = $childInfo["offsite_url_text"];
                $productId = 0;
                if (!empty($childInfo["product_id"]))
                    $productId = intval($childInfo["product_id"]);

                $isActive = false;
                if ($i == 0) {
                    $isActive = true;
                }
                $carouselItems[] = $this->getItem_Carousel($name, $descr, $imageUrl,
                    $bgImageUrl, $offsiteUrl, $offsiteText,
                    ST_PRODUCT_URL . "?product_id=$productId", $isActive);
            }
        }

        $baseChildren[] = new HtmlElement("div", null,
            "carousel-inner st-rounded", null, $carouselItems);

        if (count($carouselItems) > 1) {
            $navEles = array();
            $leftArrow = new HtmlElement("button", null, "pull-left");
            $leftArrow->setAttribute("onmouseup",
                "carouselSlidePrev('$cssId')");
            $leftArrow->setAttribute("title", "scroll left");
            $lAImg = new HtmlElement("img");
            $lAImg->setAttribute("src", "/images/left-arrow-hover.png");
            $lAImg->setAttribute("alt", "slide left");
            $leftArrow->childElements[] = $lAImg;
            $navEles[] = $leftArrow;

            $pauseEle = new HtmlElement("button", null, "product-pause");
            $pauseEle->setAttribute("title", "stop scrolling");
            $pauseEle->setAttribute("onmouseup",
                "carouselToggle(this, '$cssId')");
            $pauseImg = new HtmlElement("img");
            $pauseImg->setAttribute("src", "/images/pause-icon-hover.png");
            $pauseImg->setAttribute("alt", "stop scrolling");
            $pauseEle->childElements[] = $pauseImg;
            $navEles[] = $pauseEle;

            $rightArrow = new HtmlElement("button", null, "pull-right");
            $rightArrow->setAttribute("onmouseup",
                "carouselSlideNext('$cssId')");
            $rightArrow->setAttribute("title", "scroll right");
            $rAImg = new HtmlElement("img");
            $rAImg->setAttribute("src", "/images/right-arrow-hover.png");
            $rAImg->setAttribute("alt", "slide right");
            $rightArrow->childElements[] = $rAImg;
            $navEles[] = $rightArrow;

            $clearEle = new HtmlElement("div");
            $clearEle->setAttribute("style", "clear: both;");
            $navEles[] = $clearEle;

            $baseChildren[] = new HtmlElement("div", null,
                "product-navs st-rounded", null, $navEles);
        }

        parent::__construct("div", $cssId,
            "carousel slide st-product st-product-carousel st-rounded", null,
            $baseChildren);
    }

    /*
     * Rows of 3 page style builder functions
     */
    private function getItem_RowsOf3($title, $descr, $imageUrl, $offsiteLinkUrl,
        $offsiteLinkText, $onsiteLinkUrl) {

        $itemEles = array();

        if (!empty($title)) {
            if (empty($onsiteLinkUrl)) {
                $itemEles[] = new HtmlElement("h3", null, null, $title);
            }
            else {
                $titleLink = new HtmlElement("a", null, null, $title);
                $titleLink->setAttribute("href", $onsiteLinkUrl);
                $itemEles[] = new HtmlElement("h3", null, null, null,
                    array($titleLink));
            }
        }

        $imgSrc;
        if (empty($imageUrl)) {
            $imgSrc = "/images/no-image.png";
        }
        else {
            $imgSrc = $imageUrl;
        }
        $imgEle = new HtmlElement("img");
        $imgEle->setAttribute("src", $imageUrl);
        if (!empty($title)) {
            $imgEle->setAttribute("alt", $title);
        }
        if (!empty($onsiteLinkUrl)) {
            $imgLink = new HtmlElement("a");
            $imgLink->setAttribute("href", $onsiteLinkUrl);
            $imgLink->childElements[] = $imgEle;
            $imgEle = $imgLink;
        }
        $itemEles[] = new HtmlElement("div", null, "product-image", null,
            array($imgEle));

        $itemEles[] = new HtmlElement("hr");

        if (!empty($descr)) {
            $itemEles[] = new HtmlElement("div", null, null, $descr);
        }

        $itemEles[] = new HtmlElement("hr");

        if (!empty($offsiteLinkUrl)) {
            $offText;
            if (empty($offsiteLinkText)) {
                $offText = ST_PRODUCT_MISSING_OFFSITE_URL_TEXT;
            }
            else {
                $offText = $offsiteLinkText;
            }
            $offLink = new HtmlElement("a", null, null, $offText);
            $offLink->setAttribute("href", $offsiteLinkUrl);
            $offLink->setAttribute("target", "_blank");
            $itemEles[] = new HtmlElement("div", null, null, null,
                array($offLink));
        }

        $innerCont = new HtmlElement("div", null,
            "product-continner st-rounded", null, $itemEles);
        $outerCont = new HtmlElement("div", null, "span4 product-cont well",
            null, array($innerCont));
        return $outerCont;
    }

    private function build_RowsOf3($cssId, $parentProduct) {
        $baseChildren = array();

        $name = ST_PRODUCT_MISSING_NAME_TEXT;
        if (!empty($parentProduct["name"])) {
            $name = $parentProduct["name"];
        }

        $titleEles = array();
        $titleEles[] = new HtmlElement("h2", null, null, $name);

        if (empty($parentProduct["child_product"])) {
            $baseChildren[] = new HtmlElement("div", null,
                "product-title st-rounded", null, $titleEles);

            $descr = null;
            if (!empty($parentProduct["long_descr"])) {
                $descr = $parentProduct["long_descr"];
            }
            else if (!empty($parentProduct["descr"])) {
                $descr = $parentProduct["descr"];
            }
            $imageUrl = null;
            if (!empty($parentProduct["image_url"]))
                $imageUrl = $parentProduct["image_url"];
            $offsiteUrl = null;
            if (!empty($parentProduct["offsite_url"]))
                $offsiteUrl = $parentProduct["offsite_url"];
            $offsiteText = null;
            if (!empty($parentProduct["offsite_url_text"]))
                $offsiteText = $parentProduct["offsite_url_text"];

            $item = $this->getItem_RowsOf3(null, $descr, $imageUrl, $offsiteUrl,
                $offsiteText, null);
            $baseChildren[] = new HtmlElement("div", null, "row-fluid", null,
                array($item));
        }
        else {
            if (!empty($parentProduct["long_descr"])) {
                $titleEles[] = new HtmlElement("div", null, null,
                    $parentProduct["long_descr"]);
            }
            $baseChildren[] = new HtmlElement("div", null,
                "product-title st-rounded", null, $titleEles);

            $rowChildren = array();
            for($i = 0; $i < count($parentProduct["child_product"]); $i++) {
                $childInfo = $parentProduct["child_product"][$i];
                $name = ST_PRODUCT_MISSING_NAME_TEXT;
                if (!empty($childInfo["name"]))
                    $name = $childInfo["name"];
                $descr = null;
                if (!empty($childInfo["descr"]))
                    $descr = $childInfo["descr"];
                $imageUrl = null;
                if (!empty($childInfo["image_url"]))
                    $imageUrl = $childInfo["image_url"];
                $offsiteUrl = null;
                if (!empty($childInfo["offsite_url"]))
                    $offsiteUrl = $childInfo["offsite_url"];
                $offsiteText = ST_PRODUCT_MISSING_OFFSITE_URL_TEXT;
                if (!empty($childInfo["offsite_url_text"]))
                    $offsiteText = $childInfo["offsite_url_text"];
                $productId = 0;
                if (!empty($childInfo["product_id"]))
                    $productId = intval($childInfo["product_id"]);

                $item = $this->getItem_RowsOf3($name, $descr, $imageUrl,
                    $offsiteUrl, $offsiteText,
                    ST_PRODUCT_URL . "?product_id=$productId");

                if ($i % 3 == 0 && $i > 0) {
                    $baseChildren[] = new HtmlElement("div", null, "row-fluid",
                        null, $rowChildren);
                    $rowChildren = array();
                }

                $rowChildren[] = $item;
            }

            if (count($rowChildren) > 0) {
                $baseChildren[] = new HtmlElement("div", null, "row-fluid",
                    null, $rowChildren);
            }
        }

        parent::__construct("div", $cssId,
            "st-product st-product-rows container-fluid", null, $baseChildren);
    }

    /*
     * Constructor
     */
    public function __construct($cssId, $parentProduct) {
        $styleId = 0;
        if (!empty($parentProduct["product_style_id"])) {
            $styleId = intval($parentProduct["product_style_id"]);
        }
        switch ($styleId) {
        case ST_PRODUCT_STYLE_LARGE_BG_SCROLL:
            $this->build_LargeBGScroll($cssId, $parentProduct);
            break;
        case ST_PRODUCT_STYLE_CAROUSEL:
            $this->build_Carousel($cssId, $parentProduct);
            break;
        case ST_PRODUCT_STYLE_TILES:
            $this->build_RowsOf3($cssId, $parentProduct);
            break;
        default:
            $this->build_LargeBGScroll($cssId, $parentProduct);
            break;
        }
    }
}
?>
