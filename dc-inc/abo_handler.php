<?php
// Include CMS System
include "base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "Newsletter";
//------------------------------------------------

$empfaenger = $_POST['mail'];
$absendername = $settings->website_title;
$absendermail = "newsletter@" . $_SERVER['HTTP_HOST'];
$betreff = "Sie haben das Newssystem von " . $_SERVER['HTTP_HOST'] . " aboniert";
$text = "Sie haben das Newssystem von " . $_SERVER['HTTP_HOST'] . " aboniert";

if (check_email_address($_POST['mail'])) {
    if (db("SELECT id FROM subscribe WHERE email LIKE " . sqlString($_POST['mail']), 'rows') == 0) {
        if (up("INSERT INTO subscribe (id, email) VALUES (NULL," . sqlString($_POST['mail']) . ")")) {
            if (mail($empfaenger, $betreff, $text, "From: $absendername <$absendermail>")) {
                $disp = msg("Mail wurde erfolgreich gesendet");
            } else {
                $disp = msg("Mail wurde NICHT erfolgreich gesendet");
            }
        }
    }
} else {
    $disp = msg(_wrong_email);
}
init($disp, $meta);
		