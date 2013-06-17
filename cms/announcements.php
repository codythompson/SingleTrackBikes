<?php
define("ANNOUNCEMENTS_NONE_MESSAGE", "No announcements at this time.");
define("ANNOUNCEMENTS_HEADER_TEXT", "Bulletin Board");

require_once("htmlelement.php");

class Announcements extends HtmlElement {
    public function __construct($cssId, $announcements) {
        $baseChildren = array();

        $baseChildren[] = new HtmlElement("h3", null, "st-red",
            ANNOUNCEMENTS_HEADER_TEXT);

        $baseChildren[] = new HtmlElement("hr");

        if (empty($announcements)) {
            $baseChildren[] = new HtmlElement("div", null, "ann-empty",
                ANNOUNCEMENTS_NONE_MESSAGE);
        }
        else {
            for ($i = 0; $i < count($announcements); $i++) {
                $ann = $announcements[$i];
                if (!empty($ann["title"])) {
                    $baseChildren[] = new HtmlElement("h4", null, "st-red",
                        $ann["title"]);
                }
                if (!empty($ann["text"])) {
                    $baseChildren[] = new HtmlElement("div", null, "ann-content",
                        $ann["text"]);
                }
                if (!empty($ann["date"])) {
                    $baseChildren[] = new HtmlElement("div", null, "ann-date",
                        $ann["date"]);
                }

                if ($i < count($announcements) - 1) {
                    $baseChildren[] = new HtmlElement("hr");
                }
            }
        }

        parent::__construct("div", $cssId, "well st-ann", null, $baseChildren);
    }
}
?>
