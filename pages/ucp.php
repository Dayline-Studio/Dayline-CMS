<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "UCP";
/**--**/  $meta['page_id'] = 4;
//------------------------------------------------
            
if (!$_SESSION['loggedin'])
{
	header('Location: ../');
}
$disp = "";
if ($do == "")
{
    switch ($show)
    {
            case  "profile_edit":

            $user = mysqli_fetch_object(db("Select email,street,firstname,lastname,country from users where id = ".$_SESSION['userid']));
            $disp = show("ucp/edit_profile", array(	"firstname" => $user->firstname,
                                                                                                    "email" => $user->email,
                                                                                                    "lastname" => $user->lastname,
                                                                                                    "country" => $user->country,
                                                                                                    "street" => $user->street));
            break;
            default:
                $disp = getNews($_SESSION['group_main_id']);
            break;
    }
}
switch ($do)
{
	case 'update_profile':
		
		$user = mysqli_fetch_object(db("Select email,street,firstname,lastname,country,salt,pass,rounds from users where id = ".$_SESSION['userid']));
		$passwd = customHasher($_POST['pass'],$user->salt,$user->rounds);
		if ($_POST['passwd'] != $_POST['cpasswd'] & !empty($_POST['passwd'])) $disp = msg(_pass_dont_match);
		else if ($passwd != $user->pass) $disp = msg(_pass_wrong);
		else 
		{
			db ('Update users Set 
				pass = '.sqlString(customHasher($_POST['cpasswd'],$user->salt,$user->rounds)).',
			    email = '.sqlString($_POST['email']).',
				firstname = '.sqlString($_POST['firstname']).',
				lastname = '.sqlString($_POST['lastname']).',
				country = '.sqlString($_POST['country']).',
				street = '.sqlString($_POST['street']).' 				
				Where id ='.$_SESSION['userid']);
			$disp = msg(_change_sucessful);
		}
	break;
}
init($disp);