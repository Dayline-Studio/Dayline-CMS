<?
	// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Newsletter";
//------------------------------------------------

		$empfaenger = $_POST['mail'];
		$absendername = $settings->website_title;
		$absendermail = "newsletter@".$_SERVER['HTTP_HOST'] ;
		$betreff = "Sie haben das Newssystem von D4ho.de aboniert";
		$text = "Sie haben das Newssystem von D4ho.de aboniert";
		if(check_email_adress($_POST['mail']))
		{
			if(up("INSERT INTO subscribe SET (id, email) VALUES (NULL,".sqlSting($_POST['mail']).")"))
			{
				if(mail($empfaenger, $betreff, $text, "From: $absendername <$absendermail>"))
				{
					$disp = msg("Mail wurde erfolgreich gesendet");
				}
				else
				{
					$disp = msg("Mail wurde NICHT erfolgreich gesendet");
				}
			}
		}
		else
		{
			$disp = msg(_wrong_email);
		}
		init($disp,$meta);
		
