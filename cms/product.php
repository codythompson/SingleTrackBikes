<?php
define("ST_PRODUCT_STYLE_LARGE_BG_SCROLL", 0);
define("ST_PRODUCT_STYLE_CAROUSEL", 1);
define("ST_PRODUCT_STYLE_TILES", 2);

define("ST_PRODUCT_MISSING_NAME_TEXT", "Products sold at Single Track");
define("ST_PRODUCT_MISSING_OFFSITE_URL_TEXT", "Visit the dealer's website");

define("ST_PRODUCT_URL", "/product.php");

class Product extends HtmlElement {
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
            $bodyChildren[] = new HtmlElement("p", null, null, $descr);
        }
        if (!empty($offsiteLinkUrl)) {
            if (empty($offsiteLinkText)) {
                $offsiteLinkText = ST_PRODUCT_MISSING_OFFSITE_URL_TEXT;
            }
            $offLinkEle = new HtmlElement("a", null, null, $offsiteLinkText);
            $offLinkEle->setAttribute("href", $offsiteLinkUrl);
            $offLinkEle->setAttribute("target", "_blank");
            $bodyChildren[] = new HtmlElement("p", null, null, null,
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
                "st-product-bgimage st-rounded");
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
            if (!empty($parentProduct["descr"]))
                $descr = $parentProduct["descr"];
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

            $baseChildren[] = new HtmlElement("div", null,
                "product-innercontainer st-rounded", null, $innerContEles);
        }

        parent::__construct("div", $cssId, "st-product-lgbgscroll st-rounded",
            null, $baseChildren);
    }

    public function __construct($cssId, $parentProduct) {
        $styleId = 0;
        if (!empty($parentProduct["product_style_id"])) {
            $styleId = intval($parentProduct["product_style_id"]);
        }
        switch ($styleId) {
        case ST_PRODUCT_STYLE_LARGE_BG_SCROLL:
            $this->build_LargeBGScroll($cssId, $parentProduct);
            break;
        }
    }
}
?>
