<?php
require_once("htmlelement.php");
/*
 * navLinks
 * An array of associative arrays containing information for the navbar links
 * each associative array should have the following structure
 * "text" => the text to be displayed for the link
 * "hover_title" => the text to be displayed on hover (will be inserted as the
 *                  title attribute). This is optional.
 * "href" => the url for the link (will be inserted as the href attribute).
 * "dropdown_links" => an array of associatiave arrays formatted the same
 *                     as 'navLinks'
 *
 * activeNavLinkIndex
 * The 'navLinks' index of the current page
 */
class NavBar extends HtmlElement {
    public function __construct($navLinks, $activeIndex) {
        parent::__construct("ul", null, "nav nav-pills");

        for($i = 0; $i < count($navLinks); $i++) {
            $link = $navLinks[$i];
            $isDDL = array_key_exists("dropdown_links", $link);

            $childItem = new HtmlElement("li");
            $classes = array();
            if ($i == $activeIndex) {
                $classes[] = "active";
            }
            if ($isDDL) {
                $classes[] = "dropdown";
            }
            if (count($classes) > 0) {
                $childItem->cssClass = "";
                for($j = 0; $j < count($classes); $j++) {
                    $childItem->cssClass .= $classes[$j];
                    if ($j < count($classes) - 1) {
                        $childItem->cssClass .= " ";
                    }
                }
            }

            $childLink = new HtmlElement("a");
            $childLink->text = $link["text"];
            $childLink->setAttribute("href", $link["href"]);
            if (array_key_exists("hover_title", $link)) {
                $childLink->setAttribute("title", $link["hover_title"]);
            }

            if ($isDDL) {
                $childLink->cssClass = "dropdown-toggle";
                $childLink->setAttribute("data-toggle", "dropdown");
                $childLink->childElements[] = new HtmlElement("b", null,
                    "caret");

                $childUl = new HtmlElement("ul", null, "dropdown-menu");
                $childUl->setAttribute("role", "menu");
                $subLinks = $link["dropdown_links"];
                foreach($subLinks as $subLink) {
                    $childUlItem = new HtmlElement("li");
                    $childUlItemLink = new HtmlElement("a");
                    $childUlItemLink->text = $subLink["text"];
                    $childUlItemLink->setAttribute("href", $subLink["href"]);
                    if (array_key_exists("hover_title", $subLink)) {
                        $childUlItemLink->setAttribute("title",
                            $subLink["hover_title"]);
                    }
                    $childUlItem->childElements[] = $childUlItemLink;
                    $childUl->childElements[] = $childUlItem;
                }
                $childItem->childElements[] = $childUl;
            }

            $childItem->childElements[] = $childLink;
            $this->childElements[] = $childItem;
        }
    }
}
?>
