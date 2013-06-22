<?php
require_once("../cms/htmlelement.php");

define("ST_IMAGE_UPLOADS_PATH", "../images/uploads");
define("ST_IMAGE_UPLOADS_URL_PATH", "/images/uploads/");

function getImageUrls() {
    $ST_IMAGE_EXTS = array(".png", ".PNG", ".jpeg", ".JPEG", ".jpg", ".JPG",
        ".gif", ".GIF");

    $imgs = scandir(ST_IMAGE_UPLOADS_PATH);
    $urls = array();

    for ($i = 0; $i < count($imgs); $i++) {
        $img = $imgs[$i];
        $ext = strrchr($img, '.');
        if ($ext !== false && in_array($ext, $ST_IMAGE_EXTS)) {
            $urls[] = ST_IMAGE_UPLOADS_URL_PATH . $img;
        }
    }
    
    return $urls;
}

class ImagesModal extends HtmlElement {

    private function getImageItem($imgUrl) {
        $baseEles = array();

        //image
        $img = new HtmlElement("img", null, "media-object");
        $img->setAttribute("src", $imgUrl);
        $img->setAttribute("alt", "uploaded image");
        $baseEles[] = new HtmlElement("div", null, "pull-left img-container",
            null, array($img));

        //body
        $bodyEles = array();
        $bodyEles[] = new HtmlElement("div", null, "media-heading", $imgUrl);
        $useBut = new HtmlElement("button", null, "btn btn-success", "Use");
        $useBut->setAttribute("type", "button");
        //$useBut->setAttribute("onmouseup", "");
        $bodyEles[] = $useBut;
        $baseEles[] = new HtmlElement("div", null, "media-body", null,
            $bodyEles);

        return new HtmlElement("div", null, "media-object", null, $baseEles);
    }

    public function __construct($cssId, $imageUrls) {
        $baseEles = array();
    
        //header
        $headerEles = array();
        $close = new HtmlElement("button", null, "close", "&times;");
        $close->setAttribute("type", "button");
        $close->setAttribute("onmouseup", "toggleImagesModal('$cssId')");
        $headerEles[] = $close;
        $headerEles[] = new HtmlElement("h3", null, null, "Image Url Selector");
        $baseEles[] = new HtmlElement("div", null, "modal-header", null,
            $headerEles);

        //body
        $bodyEles = array();
        if (empty($imageUrls)) {
            $bodyEles[] = new HtmlElement("p", null, "lead",
                "No Uploaded Images Found");
        }
        else {
            foreach($imageUrls as $url) {
                $bodyEles[] = $this->getImageItem($url);
            }
        }
        $baseEles[] = new HtmlElement("div", null, "modal-body", null,
            $bodyEles);

        //footer
        $fClose = new HtmlElement("button", null, "btn", "Close");
        $fClose->setAttribute("type", "button");
        $fClose->setAttribute("onmouseup", "toggleImagesModal('$cssId')");
        $baseEles[] = new HtmlElement("div", null, "modal-footer", null,
            array($fClose));

        parent::__construct("div", $cssId, "modal hide fade st-images-modal",
            null, $baseEles);
    }
}
?>
