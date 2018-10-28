<?php
class CSSIMAGESPRITERS {

    var $inputImageDir, $classOrId = '#';
    protected $imageObj, $width, $height = 64;
    var $cssSaveDir = 'css/';
    var $cssSaveName = 'images.css';
    var $imgSaveDir = 'img/';
    var $imgSaveName = 'spriters.png';

   use MINIFYCSS;    

    public function process() {
        //check input image directory
        if(!$this->inputImageDir) die ('->inputImageDir not set');
        if(!file_exists($this->inputImageDir)) die('->inputImageDir not exsist');

        //get images array
        $this->imageObj = array_slice(scandir($this->inputImageDir), 2);        

        if(!$this->imageObj) die('image not found');

        //create output CSS, export output image

        $this->generateCssImg($this->imageObj);
    }

    private function generateCssImg($imageObj) {        

        foreach ($imageObj as $key => $image) {
            list($width, $height, $type, $attr) = getimagesize($this->inputImageDir . $image);
            $this->width += $width;
            $dataObj[$key]['image'] = $image;
            $dataObj[$key]['width'] = $width;
            $dataObj[$key]['height'] = $height;            
        }
       
        $this->genpng($dataObj);        

    }

    public function genPng($dataObj) {        
 
        $dest_image = imagecreatetruecolor($this->width, $this->height);
 
        //make sure the transparency information is saved
        imagealphablending($dest_image, false);
        imagesavealpha($dest_image, true);
 
        //create a fully transparent background (127 means fully transparent)
        $trans_background = imagecolorallocatealpha($dest_image, 0, 0, 0, 127);
 
        //fill the image with a transparent background
        imagefill($dest_image, 0, 0, $trans_background);
 
        //take create image resources out of the 3 pngs we want to merge into destination image
        $test = 0;
        $css = null;
        foreach ($dataObj as $key => $obj) {
            $img = imagecreatefrompng($this->inputImageDir . $obj['image']);
            imagecopy($dest_image, $img, $test, 0, 0, 0, $this->width, $this->height);
            $css .= $this->genCss($obj, $test);
            $test += $obj['width'];
        }
       
        file_put_contents($this->cssSaveDir . $this->cssSaveName, $this->minifyCss($css));        
    
        imagepng($dest_image, $this->imgSaveDir  . $this->imgSaveName);
        //imagegif($dest_image, 'test.gif');
        //destroy all the image resources to free up memory
        imagedestroy($img);
     
        imagedestroy($dest_image);
    }

    private function genCss($obj, $pozition) {
        $cssName = explode('.', $obj['image']);

        $return = $this->classOrId . $cssName[0] . ' { ';
       //$return .= 'width: ' . $obj['width'] . 'px; ';
       // $return .= 'height: ' . $obj['height'] . 'px; ';
        $return .= 'background: url("test.png") -'.$pozition.'px 0; }';
        return $return;

    }
}
?>  