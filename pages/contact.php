<?php
// Include CMS System
include "../inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "Kontakt";
//------------------------------------------------
if ($do == "") {
    switch ($show) {
        default:
            $disp = show('contact/contact');
            break;
    }
}

switch ($do) {
    case 'send_mail':
        if ($_POST['email'] == "" ||
            $_POST['subject'] == "" ||
            $_POST['message'] == ""
        ) {
            goToWithMsg('back',_fields_missing,'danger');
        } else if (!check_email_address($_POST['email'])) {
            $disp = msg(_mailcheck_failed);
        } else {
            if (sendMessage(0, 1, $_POST['message'], 'Kontaktformular: ' . $_POST['subject'], $_POST['email'], true)) {
                goToWithMsg('../pages/news',_msg_sent_successful,'success');
            } else {
                goToWithMsg('back',_msg_sent_failed,'danger');
            }
        }
        break;
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();