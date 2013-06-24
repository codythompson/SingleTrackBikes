<?php
require_once("htmlelement.php");
/*
 * navLinks
 * An array of associative arrays containing information for the navbar links
 * each associative array should have the following structure
 * "link_text" => the text to be displayed for the link
 * "link_hover_text" => the text to be displayed on hover (will be inserted as the
 *                  title attribute). This is optional.
 * "link_url" => the url for the link (will be inserted as the href attribute).
 * "children" => an array of associatiave arrays formatted the same
 *                     as 'navLinks'
 *
 * activeNavLinkIndex
 * The 'navLinks' index of the current page
 */
class NavBar extends HtmlElement {
    public function __construct($navLinks, $activeUrl) {
        parent::__construct("ul", null, "nav nav-pills");

        for($i = 0; $i < count($navLinks); $i++) {
            $link = $navLinks[$i];
            $isDDL = array_key_exists("children", $link);

            $childItem = new HtmlElement("li");
            $classes = array();
            $linkUrlBase = removeQueryString($link["link_url"]);
            if ($linkUrlBase == $activeUrl) {
                $classes[] = "active";
            }
            if ($isDDL) {
                $classes[] = "dropdown";
            }
            $classes[] = "stack-small";
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
            $childLink->text = $link["link_text"];
            $childLink->setAttribute("href", $link["link_url"]);
            if (array_key_exists("hover_title", $link)) {
                $childLink->setAttribute("title", $link["link_hover_text"]);
            }

            if ($isDDL) {
                $childLink->cssClass = "dropdown-toggle";
                $childLink->setAttribute("data-toggle", "dropdown");
                $childLink->childElements[] = new HtmlElement("b", null,
                    "caret");

                $childUl = new HtmlElement("ul", null, "dropdown-menu");
                $childUl->setAttribute("role", "menu");
                $subLinks = $link["children"];
                foreach($subLinks as $subLink) {
                    $childUlItem = new HtmlElement("li");
                    $childUlItemLink = new HtmlElement("a");
                    $childUlItemLink->text = $subLink["link_text"];
                    $childUlItemLink->setAttribute("href", $subLink["link_url"]);
                    if (array_key_exists("link_hover_text", $subLink)) {
                        $childUlItemLink->setAttribute("title",
                            $subLink["link_hover_text"]);
                    }
                    $childUlItem->childElements[] = $childUlItemLink;
                    $childUl->childElements[] = $childUlItem;
                }
                $childItem->childElements[] = $childUl;
            }

            $childItem->childElements[] = $childLink;
            $this->childElements[] = $childItem;
        }

        //Facebook links
        $fbItem = new HtmlElement("li", null, "dropdown pull-right-big");
        $fbItemLink = new HtmlElement("a", null, "dropdown-toggle");
        $fbItemLink->setAttribute("href", "");
        $fbItemLink->setAttribute("data-toggle", "dropdown");
        $fbItemLink->text = "<img src=\"/images/facebook-small.png\" alt=\"" .
            "facebook\" .>";
        $fbItem->childElements[] = $fbItemLink;
        $fbItemUl = new HtmlElement("ul", null, "dropdown-menu");
        $fbItemUl->setAttribute("role", "menu");
        $fbItemUlLi = new HtmlElement("li");
        $fbItemUlLi->text = "<a href=\"https://www.facebook.com/pages/Single-Track-Bikes/285426214809646\" target=\"_blank\"> Facebook Page </a>";
        $fbItemUl->childElements[] = $fbItemUlLi;
        $fbItemUlLi = new HtmlElement("li");
        $fbItemUlLi->text = "<a><iframe src=\"//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FSingle-Track-Bikes%2F285426214809646&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:450px; height:35px;\" allowTransparency=\"true\"></iframe></a>";
        $fbItemUl->childElements[] = $fbItemUlLi;
        $fbItem->childElements[] = $fbItemUl;
        $this->childElements[] = $fbItem;

        $twtrItem = new HtmlElement("li", null, "dropdown pull-right-big");
        $twtrItemLink = new HtmlElement("a", null, "dropdown-toggle");
        $twtrItemLink->setAttribute("href", "");
        $twtrItemLink->setAttribute("data-toggle", "dropdown");
        $twtrItemLink->text = "<img src=\"/images/twitter-small.png\" alt=\"" .
            "twitter\" .>";
        $twtrItem->childElements[] = $twtrItemLink;
        $twtrItemUl = new HtmlElement("ul", null, "dropdown-menu");
        $twtrItemUl->setAttribute("role", "menu");
        $twtrItemUlLi = new HtmlElement("li");
        $twtrItemUlLi->text = "<a href=\"\" target=\"_blank\">Twitter Page </a>";
        $twtrItemUl->childElements[] = $twtrItemUlLi;
        $twtrItemUlLi = new HtmlElement("li");
        $twtrItemUlLi->text = "<a href=\"\">Twitter Feed</a>";
        $twtrItemUl->childElements[] = $twtrItemUlLi;
        $twtrItem->childElements[] = $twtrItemUl;
        $this->childElements[] = $twtrItem;
    }
}
?>
