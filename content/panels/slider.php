<?php
function slider() {
    
    global $path;
    
    $slider_images = __c("files")->get('slider_images');
    if ($slider_images == null)
    {
        $images = opendir($path['style']."images/slider/");
        $slider_images = "";
        while ($image = readdir($images)) 
        {
            if ($image != ".." && $image != ".") 
            {
                  $slider_images .= '<img src="'.$path['style']."images/slider/".$image.'" alt="" />';
            }
        } 
        closedir($images);
        __c("files")->set('slider_images',$slider_images, 600);
    }
    return show("panels/slider", array("images" => $slider_images));    
}