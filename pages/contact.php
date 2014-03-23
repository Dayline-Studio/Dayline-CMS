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
        $out = show('contact/form');
        break;
    }
}

switch ($do)
{
    case send_mail:
        if($_POST['mail'] == "" ||
           $_POST['subject'] == "" ||
           $_POST['message'] == "")
        {
            $out = msg(_fields_missing); 
        }
        else if (!check_email_address($_POST['mail']))
        {
            $out = msg(_mailcheck_failed);
        }
        else {

        up("INSERT INTO messages (id,mail,subject,message,targetgroup,to_user)"
                . "VALUES (NULL, ".sqlString($_POST['mail']).", ".sqlString($_POST['subject']).", ".sqlString($_POST['message']).", 1, 0");

            if(mail(con($_POST['mail']),con($_POST['subject']) , con($_POST['message']))) {
                $out = msg(_mail_send_true);
            } else {
                $out = msg(_mail_send_false);
            }
         }
         break;
}

//Initialisierung
init($out, $meta);