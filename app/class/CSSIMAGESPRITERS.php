<?php
class CSSIMAGESPRITERS {
    var $inputImageDir;
    protected $imageObj;

    public function process() {
        //check input image directory
        if(!file_exists($this->dir)) die('not set direcotry, or not exsist');

        //get images array
        $this->imageObj = scandir($this->inputImageDir);

        //create output CSS, export output image
    }
}
?>