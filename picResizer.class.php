<?php
//error_reporting(8);

class resizer
{
    //图片类型
    var $type;
    //实际宽度
    var $width;
    //实际高度
    var $height;
    //改变后的宽度
    var $resize_width;
    //改变后的高度
    var $resize_height;

    //图片质量
    var $quality = 100;
    //源图象
    var $srcimg;
    //目标图象地址
    var $dstimg;
    //临时创建的图象
    var $im;
    
    var $error = '';

    function resize($img, $wid, $hei=0, $quality=100)
    {
        $hei == 0 && $hei = $wid;
        $this->srcimg = $img;
        $this->resize_width = $wid;
        $this->resize_height = $hei;
        
        //图片的类型
        $this->type = strtolower(substr(strrchr($this->srcimg,"."),1));
        
        $this->quality = $quality;

        //初始化图象
        if(!$this->initi_img()) {
            return false;
        }
        if(!$this->im) {
            $this->error = '生成资源出错';
            return false;
        }
        //目标图象地址
        //$this -> dst_img($dstpath);
        $this->dstimg = $this->srcimg.'.thumb.jpg';

        $this->width = imagesx($this->im);
        $this->height = imagesy($this->im);
        //生成图象
        
        $flag = $this->newimg();
        ImageDestroy ($this->im);
        return $flag;
    }
    private function newimg()
    {        
        $newimg = imagecreatetruecolor($this->resize_width, $this->resize_height);        
        $bg = imagecolorallocate($newimg, 255, 255, 255);// 补加的代码处 开始 
        imagefill($newimg, 0, 0, $bg);

        $tarData = array('x'=>0, 'y'=>0, 'w'=>$this->resize_width, 'h'=>$this->resize_height);
        
        if($this->width > $this->height) {
            if($this->width > $this->resize_width) {
                $tarData['x'] = 0;
                $tarData['y'] = (int)(($this->resize_height - $this->height*($this->resize_width/$this->width))/2);
                $tarData['h'] = (int)($this->height*($this->resize_width/$this->width));
            } elseif($this->width < $this->resize_width) {
                $tarData['x'] = (int)(($this->resize_width - $this->width)/2);
                $tarData['y'] = (int)(($this->resize_height - $this->height)/2);
                $tarData['h'] = $this->height;//*($this->resize_width/$this->width);
                $tarData['w'] = $this->width;//*($this->resize_width/$this->width);
            }  
        }  elseif($this->width < $this->height) {
            if($this->height > $this->resize_height) {
                $tarData['y'] = 0;
                $tarData['x'] = (int)(($this->resize_width - $this->width*($this->resize_height/$this->height))/2);
                $tarData['w'] = (int)($this->width*($this->resize_height/$this->height));
            } elseif($this->height < $this->resize_height) {
                $tarData['x'] = (int)(($this->resize_width - $this->width)/2);
                $tarData['y'] = (int)(($this->resize_height - $this->height)/2);
                $tarData['h'] = $this->height;//*($this->resize_width/$this->width);
                $tarData['w'] = $this->width;//*($this->resize_width/$this->width);
            }  
        }  elseif($this->width = $this->height) {
            if($this->width < $this->resize_width) {
                $tarData['x'] = (int)(($this->resize_width - $this->width)/2);
                $tarData['y'] = (int)(($this->resize_height - $this->height)/2);
                $tarData['h'] = $this->height;//*($this->resize_width/$this->width);
                $tarData['w'] = $this->width;
            } 
        }
    

        imagecopyresampled($newimg, $this->im, $tarData['x'], $tarData['y'], 0, 0, $tarData['w'], $tarData['h'], $this->width, $this->height);
        if(ImageJpeg ($newimg, $this->dstimg, $this->quality)) {
           return true;
        } else {
           return false;
        }
            
    }
    //初始化图象
    private function initi_img()
    {
        if(!file_exists($this->srcimg)) {
            $this->error = $this->srcimg.' 图片不存在';
            return false;
        }
        if($this->type=="jpg")
        {
            $this->im = imagecreatefromjpeg($this->srcimg);
        }
        if($this->type=="gif")
        {
            $this->im = imagecreatefromgif($this->srcimg);
        }
        if($this->type=="png")
        {
            $this->im = imagecreatefrompng($this->srcimg);
        }
        return true;
    }
    //图象目标地址
    private function dst_img($dstpath)
    {
        $full_length  = strlen($this->srcimg);
        $type_length  = strlen($this->type);
        $name_length  = $full_length-$type_length;
        $name         = substr($this->srcimg,0,$name_length-1);
        $this->dstimg = $dstpath;
    }
}

?>