<?php
// Site Informations
/**--**/  $meta['title'] = "Slider-Bild Hochladen";
//------------------------------------------------
// Site Permissions
/**--**/ //if (!permTo("site_create")) $error = msg(_no_permissions);
//------------------------------------------------

if ($do == "")
{
	switch ($action)
	{
		default:
                    $disp = show('acp/acp_slider');    
                    break;
	}
}
switch ($do)
{
    case 'upload_image':
            //require_once '../inc/image_resize.php';
            /*$image = new SimpleImage(); 
            echo $_FILES['image'];
            $image->load($_FILES['image']); 
            $image->resize(1200,300); 
            $image->save('../inc/upload/slider/test.png'); */
            break;
}