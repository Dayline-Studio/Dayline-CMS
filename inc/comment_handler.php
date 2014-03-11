<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Kommentar";
//------------------------------------------------

if ($_SESSION['loggedin']) 
{
    if (isset($_POST['comment']))
    {
        if (isset($_GET['site']) && isset($_GET['subsite']))
        {
            $qry = db("SELECT id FROM pages WHERE id = ".sqlInt($_GET['site']), 'object');

            if ($qry->id>0)
            {
                if (permTo("comment"))
                {
                    $sql = "INSERT INTO comments (id, name, userid, date, content, site, subsite) "
                            . "VALUES (NULL, ".sqlString($_SESSION['name']).", ".sqlString($_SESSION['userid']).", '".time()."', ".  sqlString($_POST['comment']).", '".$qry->id."', ".  sqlInt($_GET['subsite']).")";
                    if (up($sql))
                    {
                        $content = msg(_comment_successful);
                    } else {
                        $content = msg(_comment_failed); 
                    }
                } else {
                    $content = msg(_comment_no_permissions);
                }
            } else {
                $content = msg(_comment_site_not_found);
            }
        } else {
            $content = msg(_comment_content_not_found);
        }
    } else {
        $content = msg(_comment_failed);
    }
} else {
    $content = msg(_comment_no_permissions);
}

init($content,$meta);