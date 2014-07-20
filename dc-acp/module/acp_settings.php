<?php
// Site Informations
/**--**/  $meta['title'] = "Globale Einstellungen";
//------------------------------------------------
  
if ($do == "")
{
    switch ($action)
    {
        default:
             
            break;
        case 'update_database': {
            include 'sql_updates.php';

            foreach ($sql_up as $ver => $update) {
                $disp .= "Checking $ver <br/>";
                if($ver > Config::$settings->version) {
                    foreach ($update as $up) {
                        Db::nrquery($up);
                        Debug::log('SQL Update -> '.$up);
                        $disp .= "Update -> $up <br/>";
                    }
                    Db::update('settings', '1', array('version' => $ver));
                    $disp .= "Update -> $ver complete <br/>";
                }
            }
        }
    }
}

switch ($do)
{
    case 'update_settings':
        
        break;
}
