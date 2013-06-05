<?php
class HtmlElement
{
    public $tagName;
    public $cssId;
    public $cssClass;
    public $text;
    public $childElements;

    private $attributes;

    //FLAGS
    public $textAfterChildren;
    public $closeInOpenTag;

    public function __construct($tagName, $cssId = null, $cssClass = null,
        $text = null, $childElements = array())
    {
        $this->tagName = $tagName;
        $this->cssId = $cssId;
        $this->cssClass = $cssClass;
        $this->text = $text;
        $this->childElements = $childElements;

        $this->attributes = array();

        $this->associatedHeadElements = array();

        $this->textAfterChildren = false;
        $this->closeInOpenTag = false;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function writeOpenTag()
    {
        $openString = "<$this->tagName";
        if (!empty($this->cssId))
        {
            $openString .= " id=\"$this->cssId\"";
        }
        if (!empty($this->cssClass))
        {
            $openString .= " class=\"$this->cssClass\"";
        }
        foreach ($this->attributes as $key => $value)
        {
            $openString .= " $key=\"$value\"";
        }
        if (empty($this->text) && empty($this->childElements) &&
            $this->closeInOpenTag)
        {
            $openString .= " />";
        }
        else
        {
            $openString .= ">";
        }

        echo $openString;
    }

    public function writeText()
    {
        if (!empty($this->text))
        {
            echo $this->text;
        }
    }

    public function writeChildElements()
    {
        if (!empty($this->childElements))
        {
            foreach($this->childElements as $child)
            {
                $child->writeElement();
            }
        }
    }

    public function writeCloseTag()
    {
        $closeString = "</$this->tagName>";
        echo $closeString;
    }

    public function writeElement()
    {
        $this->writeOpenTag();
        if (empty($this->text) && empty($this->childElements))
        {
            if (!$this->closeInOpenTag)
            {
                $this->writeCloseTag();
            }
        }
        else
        {
            if ($this->textAfterChildren)
            {
                $this->writeChildElements();
                $this->writeText();
            }
            else
            {
                $this->writeText();
                $this->writeChildElements();
            }
            $this->writeCloseTag();
        }
    }
}
?>
