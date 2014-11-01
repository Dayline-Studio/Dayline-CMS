<?php
// Site Informations
/**--**/  $meta['title'] = "Globale Einstellungen";
//------------------------------------------------
  
if ($do == "")
{
    switch ($action)
    {
        default:
            $te = new TemplateEngine('acp/acp_edit_settings');
            $form = new FormModel(Config::$settings);
            $te->add_vars($form->get_vars_out());
            $disp = $te->render();
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
        if (permTo('edit_settings')) {
            $form = new FormModel($_POST, array('force_https', 'use_site_id', 'force_domain'));
            if(Db::update('settings', 1, $form->get_vars_raw())) {
                goToWithMsg('back', 'Done','success');
            } else {
                goToWithMsg('back', 'Failed','danger');
            }
        } else {
            $disp = _no_permissions;
        }
        break;
}
