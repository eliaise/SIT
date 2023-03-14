<?php
class Helper
{
    private $defaultPath;

    public function __construct()
    {
        //$this->defaultPath = ($_SERVER['SERVER_NAME'] == 'localhost') ? '/' : '/' ;
        $this->defaultPath = '/' ;
    }

    public function subviewPath($file) { // return a path to include subviews
        $file =  __DIR__ . '/../views/sub-views/'. $file;
        return $file;
    }

    function cssPath($file) { // return a css file path to for href attributes
        $hrefPath = $this->defaultPath .'css/'. $file;
        return $hrefPath;
    }

    function jsPath($file) { // return a js file path to for src attributes
        $hrefPath = $this->defaultPath . 'js/'. $file;
        return $hrefPath;
    }


    function processUrl($file) {
        $httpProtocol = !isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http' : 'https';
        $url = $httpProtocol . '://' . $_SERVER['HTTP_HOST'] . $this->defaultPath;
        return $url .= 'process/' . $file;
    }

    function imgPath($file, $type = null) { // return a img file path to for src attributes
        if ($type == "Category") {
            $hrefPath = $this->defaultPath . 'Images/Category/'. $file;
        } else if ($type == "Products") {
            $hrefPath = $this->defaultPath . 'Images/Products/'. $file;
        } else {
            $hrefPath = $this->defaultPath . 'Images/'. $file;
        }
        return $hrefPath;
    }


    function pageUrl($file) { // return a page path for href navigation
        $httpProtocol = !isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http' : 'https';
        $url = $httpProtocol . '://' . $_SERVER['HTTP_HOST'] . $this->defaultPath;
        if ($file !== 'home.php') {
            $url .= 'views/' . $file;
        }
        return $url;
    }

    function pageIndex($file) { // return a page path for href navigation
        $httpProtocol = !isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http' : 'https';
        $url = $httpProtocol . '://' . $_SERVER['HTTP_HOST'] . $this->defaultPath;
        if ($file == 'home.php') {
            $url .= $file;
        }
        return $url;
    }

    function flatten_array($array, $prefix = '' ) {
        $result = array();
        foreach ($array as $key => $value) {
            
        }
    }
    function subPageUrl($file) { // return a page path for href navigation
        $httpProtocol = !isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http' : 'https';
        $url = $httpProtocol . '://' . $_SERVER['HTTP_HOST'] . $this->defaultPath;
        if ($file !== 'home.php') {
            $url .= 'views/sub-views/' . $file;
        }
        return $url;
    }
}
//set timzeone to sg
date_default_timezone_set("Asia/Singapore");
?>
