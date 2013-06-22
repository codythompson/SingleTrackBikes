<?php
require_once("../cms/htmlelement.php");

define("ST_IMAGE_UPLOADS_PATH", "../images/uploads/");
define("ST_IMAGE_UPLOADS_URL_PATH", "/images/uploads/");

function hasImageExt($filename) {
    $ST_IMAGE_EXTS = array(".png", ".PNG", ".jpeg", ".JPEG", ".jpg", ".JPG",
        ".gif", ".GIF");

    $ext = strrchr($filename, '.');
    return ($ext !== false && in_array($ext, $ST_IMAGE_EXTS));
}

function getImageUrls() {
    $ST_IMAGE_EXTS = array(".png", ".PNG", ".jpeg", ".JPEG", ".jpg", ".JPG",
        ".gif", ".GIF");

    $imgs = scandir(ST_IMAGE_UPLOADS_PATH);
    $urls = array();

    for ($i = 0; $i < count($imgs); $i++) {
        $img = $imgs[$i];
        if (hasImageExt($img)) {
            $url = array();
            $url["filename"] = $img;
            $url["path"] = ST_IMAGE_UPLOADS_URL_PATH . $img;
            $urls[] = $url;
        }
    }
    
    return $urls;
}

class UploadForm extends HtmlElement {
    public $uploadErrMess;
    public $uploadSMess;
    public $ST_IMAGE_TYPES = array("image/gif", "image/jpeg", "image/jpg",
        "image/pjpeg", "image/x-png", "image/png");

    private function handlePost() {
        /*
         * Upload Form Logic
         */
        $formType = "";
        if (isset($_POST["form_type"])) {
            $formType = $_POST["form_type"];
        }
        
        $uploadErrMess = null;
        $uploadSMess = null;
        if ($formType === "image_upload") {
            if ($_FILES["upload_file"]["error"] > 0) {
                $this->uploadErrMess =
                    "There was an errror uploading the file.";
            }
            else if (!in_array($_FILES["upload_file"]["type"],
                $this->ST_IMAGE_TYPES)) {

                    $this->uploadErrMess = "The file <strong>" .
                        $_FILES["upload_file"]["name"] . "</strong> " .
                        "is not a valid image file and was not uploaded";
            }
            else if (!hasImageExt($_FILES["upload_file"]["name"])) {
                $this->uploadErrMess = "The file <strong>" .
                    $_FILES["upload_file"]["name"] . "</strong> " .
                    "has the incorrect file extension and was not uploaded.";
            }
            else {
                $name = str_replace(' ', '_', $_FILES["upload_file"]["name"]);

                $path = ST_IMAGE_UPLOADS_PATH . $name;

                $newName = $name;
                $i = 1;
                while (file_exists($path)) {
                    $parts = pathinfo($name);
                    $newName = $parts["filename"] . "_$i." .
                        $parts["extension"];
                    $path = ST_IMAGE_UPLOADS_PATH . $newName;
                    $i++;
                }

                move_uploaded_file($_FILES["upload_file"]["tmp_name"], $path);

                $this->uploadSMess = "Successfully uploaded " .
                    "<strong>$name</strong> as <strong>$newName</strong>";
            }
        }
    }

    public function __construct($cssId, $actionUrl) {
        $this->handlePost();

        $formChildren = array();

        if (!empty($this->uploadErrMess)) {
            $errMessClose = new HtmlElement("button", null, "close", "&times;");
            $errMessClose->setAttribute("data-dismiss", "alert");
            $errMess = new HtmlElement("div", null, "alert alert-danger",
                $this->uploadErrMess, array($errMessClose));
            $formChildren[] = $errMess;
        }
        if (!empty($this->uploadSMess)) {
            $errMessClose = new HtmlElement("button", null, "close", "&times;");
            $errMessClose->setAttribute("data-dismiss", "alert");
            $errMess = new HtmlElement("div", null, "alert alert-success",
                $this->uploadSMess, array($errMessClose));
            $formChildren[] = $errMess;
        }

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
        $submit->setAttribute("type", "Submit");
        $submit->setAttribute("value", "Upload");
        $formChildren[] = $submit;

        $form = new HtmlElement("form", null, null, null, $formChildren);
        $form->setAttribute("method", "POST");
        $form->setAttribute("action", $actionUrl);
        $form->setAttribute("enctype", "multipart/form-data");

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

    public function __construct($cssId, $actionUrl) {
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
        //need to let let the upload form upload images first
        $upForm = new UploadForm($cssId . "-upload", $actionUrl);
        $imageUrls = getImageUrls();
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

        //initialized above
        $footerEles[] = $upForm;

        $fClose = new HtmlElement("button", null,
            "btn btn-info st-images-modal-fclose", "Close");
        $fClose->setAttribute("type", "button");
        $fClose->setAttribute("onmouseup", "toggleImagesModal('$cssId')");
        $footerEles[] = $fClose;

        $baseEles[] = new HtmlElement("div", null, "modal-footer", null,
            $footerEles);

        if (!empty($upForm->uploadErrMess) || !empty($upForm->uploadSMess)) {
            $scriptText =
                "$(document).ready( function () { " .
                "toggleImagesModal('$cssId');" .
                "}); ";
            $scriptEle = new HtmlElement("script", null, null, $scriptText);
            $scriptEle->setAttribute("type", "text/javascript");
            $baseEles[] = $scriptEle;
        }
        else {
            $scriptText =
                "$(document).ready( function () { " .
                "toggleUploadForm('$cssId-upload');" .
                "}); ";
            $scriptEle = new HtmlElement("script", null, null, $scriptText);
            $scriptEle->setAttribute("type", "text/javascript");
            $baseEles[] = $scriptEle;
        }

        parent::__construct("div", $cssId, "modal hide fade st-images-modal",
            null, $baseEles);
    }
}
?>
