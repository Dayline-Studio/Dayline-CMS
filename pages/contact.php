<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Kontakt";
//------------------------------------------------
if ($do == "")
{
    switch ($show)
    {
        default:
        $disp = show('contact/form');
        break;
    }
}

switch ($do)
{
    case send_mail:
        if($_POST['email'] == "" ||
           $_POST['subject'] == "" ||
           $_POST['message'] == "")
        {
            $disp = msg(_fields_missing); 
        }
        else if (!check_email_address($_POST['mail']))
        {
            $disp = msg(_mailcheck_failed);
        }
        else {
            if(sendMessage(0, 1, $_POST['message'] , "Von: ".$_POST['mail']."<br />".$_POST['subject'], $_POST['email'], true)) {
                $disp = msg(_mail_send_true);
            } else {
                $disp = msg(_mail_send_false);
            }
         }
         break;
}

//Initialisierung
init($disp, $meta);