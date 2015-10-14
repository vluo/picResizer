##实现功能：
    * 把一张图片按比例缩放到一个正方形的图片内，非正方形的图片留白色背景<br>
    * 新图片以“原图名称+'.thumb.jpg'”命名，原图保存在同一路径下<br>
##使用方法： 
```
	$resizer = new resizer();
	$r = $resizer->resize($filePath, $this->_imgWidth); 
```	
