<?php
function share()
{
    global $meta;
	

    if ($meta['page_id'] == 2 || $meta['page_id'] == 3) {
        return show("panels/share", array("share_url" => $_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']));
    }
    return ""; 
}
