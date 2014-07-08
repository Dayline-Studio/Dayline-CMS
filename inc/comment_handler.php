<?php
// Include CMS System
/**--**/
include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/
$meta['title'] = "Kommentar";
//------------------------------------------------

if ($_SESSION['loggedin']) {
    if (isset($_POST['comment'])) {
        if (isset($_GET['site']) && isset($_GET['subsite'])) {
            $qry = Db::query("SELECT id FROM news WHERE id = :id LIMIT 1", array('id' => $_GET['site']), PDO::FETCH_OBJ);
            print_r($qry);
            if ($qry->id > 0) {
                if (permTo("comment")) {
                    $sql = "INSERT INTO comments (id, userid, date, content, site, subsite, active) "
                        . "VALUES (NULL, " . sqlString($_SESSION['userid']) . ", '" . time() . "', " . sqlStringCon($_POST['comment']) . ", '" . $qry->id . "', " . sqlInt($_GET['subsite']) . ", 1)";
                    if (up($sql)) {
                        goToWithMsg('back',_comment_successful,'success');
                    } else {
                        $disp = msg(_comment_failed);
                    }
                } else {
                    $disp = msg(_comment_no_permissions);
                }
            } else {
                $disp = msg(_comment_site_not_found);
            }
        } else {
            $disp = msg(_comment_content_not_found);
        }
    } else {
        $disp = msg(_comment_failed);
    }
} else {
    $disp = msg(_comment_no_permissions);
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();