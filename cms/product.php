<?php
class Product extends HtmlElement {
    public function __construct($cssId, $productItems) {
        parent::__construct("div", $cssId, "container-fluid");

        $baseChildren = array();

        $rowChildren = array();
        $i = 0;
        foreach($productItems as $prodInfo) {
            $itemChildren = array();
            $itemChildren[] = new HtmlElement("h3", null, null,
                $prodInfo["name"]);
            $imageSrc = $prodInfo["large_logo_url"];
            if (empty($imageSrc)) {
                $imageSrc = "/images/no-image.png";
            }
            $imageTag = "<img src=\"$imageSrc\" alt=\"" . $prodInfo["name"] .
                "\" />";
            $itemChildren[] = new HtmlElement("div", null, "product-image",
                $imageTag);
            $itemChildren[] = new HtmlElement("p", null, null,
                $prodInfo["short_descr"]);

            if ($i % 3 == 0 && $i > 0) {
                $baseChildren[] = new HtmlElement("div", null, "row-fluid",
                    null, $rowChildren);
                $rowChildren = array();
            }

            $rowItemInner = new HtmlElement("div", null,
                "product-container-inner", null, $itemChildren);
            $rowChildren[] = new HtmlElement("div", null,
                "span4 product-container", null, array($rowItemInner));


            $i++;
        }

        if (count($rowChildren > 0)) {
            $baseChildren[] = new HtmlElement("div", null, "row-fluid", null,
                $rowChildren);
        }

        $this->childElements = $baseChildren;
    }
}
?>
