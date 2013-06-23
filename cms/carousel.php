<?php
require_once("htmlelement.php");

class Carousel extends HtmlElement {
    public function __construct($cssId, $contentItems) {
        parent::__construct("div", $cssId, "carousel slide");

        $baseChildrenArray = array();

        // build the carousel page indicators
        if (count($contentItems) > 1) {
            $indChildren = array();
            for ($i = 0; $i < count($contentItems); $i++) {
                $indChildren[$i] = new HtmlElement("li");
                $indChildren[$i]->setAttribute("data-target", "#$cssId");
                $indChildren[$i]->setAttribute("data-slide-to", "$i");
            }
            $indChildren[0]->cssClass = "active";
            $indicators = new HtmlElement("ol", null,
                "carousel-indicators st-rounded hidden-750", null,
                $indChildren);
            $baseChildrenArray[] = $indicators;
        }

        //build the content items
        $itemArray = array();
        foreach($contentItems as $itemInfo) {
            $itemChildren = array();
            
            $itemChildren[] = new HtmlElement("img", null, "st-rounded");
            $itemChildren[0]->setAttribute("src", $itemInfo["bg_image_url"]);
            $itemChildren[0]->setAttribute("alt", $itemInfo["bg_image_alt"]);
            $itemChildren[0]->closeInOpenTag = true;

            $itemCapClass = "st-carousel-caption st-rounded " .
                $itemInfo["css_location_class"];
            $itemTitle = 
            $itemChildren[] = new HtmlElement("div", null, $itemCapClass,
                $itemInfo["content"]);
            $itemChildren[1]->childElements[] = new HtmlElement("h3", null,
                null, $itemInfo["title"]);
            $itemChildren[1]->textAfterChildren = true;

            $itemArray[] = new HtmlElement("div", null, "item", null,
                $itemChildren);
        }
        $itemArray[0]->cssClass .= " active";
        $baseChildrenArray[] = new HtmlElement("div", null,
            "carousel-inner st-rounded", null, $itemArray);

        if (count($contentItems) > 1) {
            $leftArrow = new HtmlElement("a", null,
                "carousel-control-left left hidden-750");
            $leftArrow->setAttribute("href", "#$cssId");
            $leftArrow->setAttribute("data-slide", "prev");
            $leftArrow->text =
                "<img src=\"/images/st-arrow-left.png\" alt=\"scroll left\"/>";
    
            $rightArrow = new HtmlElement("a", null,
                "carousel-control-right right hidden-750");
            $rightArrow->setAttribute("href", "#$cssId");
            $rightArrow->setAttribute("data-slide", "next");
            $rightArrow->text =
                "<img src=\"/images/st-arrow-right.png\" alt=\"scroll right\"/>";
    
            $baseChildrenArray[] = $leftArrow;
            $baseChildrenArray[] = $rightArrow;
        }

        $this->childElements = $baseChildrenArray;
    }
}
?>
