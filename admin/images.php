<?php
require_once("../cms/htmlelement.php");

define("ST_IMAGE_UPLOADS_PATH", "../images/uploads");
define("ST_IMAGE_UPLOADS_URL_PATH", "/images/uploads/");

/*
 * Upload Form Logic
 */
$formType = "";
if (isset($_POST["form_type"])) {
    $formType = $_POST["form_type"];
}

if ($formType === "image_upload") {
    //TODO
}

function getImageUrls() {
    $ST_IMAGE_EXTS = array(".png", ".PNG", ".jpeg", ".JPEG", ".jpg", ".JPG",
        ".gif", ".GIF");

    $imgs = scandir(ST_IMAGE_UPLOADS_PATH);
    $urls = array();

    for ($i = 0; $i < count($imgs); $i++) {
        $img = $imgs[$i];
        $ext = strrchr($img, '.');
        if ($ext !== false && in_array($ext, $ST_IMAGE_EXTS)) {
            $url = array();
            $url["filename"] = $img;
            $url["path"] = ST_IMAGE_UPLOADS_URL_PATH . $img;
            $urls[] = $url;
        }
    }
    
    return $urls;
}

class UploadForm extends HtmlElement {
    public function __construct($cssId) {
        $formChildren = array();

        $formType = new HtmlElement("input");
        $formType->setAttribute("type", "hidden");
        $formType->setAttribute("name", "form_type");
        $formType->setAttribute("value", "image_upload");
        $formChildren[] = $formType;

        $file = new HtmlElement("input");
        $file->setAttribute("type","file");
        $file->setAttribute("name","upload_file");
        $formChildren[] = $file;

        $submit = new HtmlElement("input", null, "btn btn-success");
        $submit->setAttribute("value", "Upload");
        $formChildren[] = $submit;

        $form = new HtmlElement("form", null, null, null, $formChildren);

        parent::__construct("div", $cssId, "st-image-upload well", null,
            array($form));
    }
}

class ImagesModal extends HtmlElement {

    private function getImageItem($imgUrlInfo) {
        $baseEles = array();

        //image
        $img = new HtmlElement("img", null, "media-object");
        $img->setAttribute("src", $imgUrlInfo["path"]);
        $img->setAttribute("alt", "uploaded image");
        $imgLink = new HtmlElement("a", null, "pull-left img-container", null,
            array($img));
        $imgLink->setAttribute("href", $imgUrlInfo["path"]);
        $imgLink->setAttribute("target", "_blank");
        $baseEles[] = $imgLink;
        //$baseEles[] = new HtmlElement("div", null, "pull-left img-container", null, array($img));

        //body
        $bodyEles = array();
        $bodyEles[] = new HtmlElement("div", null, "media-heading",
            $imgUrlInfo["filename"]);
        $useBut = new HtmlElement("button", null, "btn btn-success", "Use");
        $useBut->setAttribute("type", "button");
        //$useBut->setAttribute("onmouseup", "");
        $bodyEles[] = $useBut;
        $bodyEles[] = new HtmlElement("br");
        $delBut = new HtmlElement("button", null, "btn btn-danger", "Delete");
        $delBut->setAttribute("type", "button");
        //$delBut->setAttribute("onmouseup", "");
        $bodyEles[] = $delBut;
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
            foreach($imageUrls as $urlInfo) {
                $bodyEles[] = $this->getImageItem($urlInfo);
            }
        }
        $baseEles[] = new HtmlElement("div", null, "modal-body", null,
            $bodyEles);

        //footer
        $footerEles = array();
        $upBut = new HtmlElement("button", null,
            "btn btn-success st-image-upload-button", "Upload Image");
        $upBut->setAttribute("type", "button");
        $upBut->setAttribute("onmouseup", "toggleUploadForm('$cssId-upload')");
        $footerEles[] = $upBut;

        $upForm = new UploadForm($cssId . "-upload");
        $footerEles[] = $upForm;

        $fClose = new HtmlElement("button", null,
            "btn btn-info st-images-modal-fclose", "Close");
        $fClose->setAttribute("type", "button");
        $fClose->setAttribute("onmouseup", "toggleImagesModal('$cssId')");
        $footerEles[] = $fClose;

        $baseEles[] = new HtmlElement("div", null, "modal-footer", null,
            $footerEles);

        parent::__construct("div", $cssId, "modal hide fade st-images-modal",
            null, $baseEles);
    }
}
?>
