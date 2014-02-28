<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "News";
//------------------------------------------------

if ($_SESSION['loggedin'])
{
	header('Location: ucp.php');
}

$content = "";
switch ($show)
{
	default: 

	break;
	case 'register':

	break;
}
switch ($do)
{
    case 'login': 
        $username = $_POST['username'];
        $passwort = $_POST['passwort'];

        $user = mysqli_fetch_object(db('Select * From users Where user like '.sqlString(strtolower($username))));
        if ($user->id != "")
        {
                if (customHasher($passwort,$user->salt,$user->rounds) == $user->pass) 
                {
                         $_SESSION['loggedin'] = true;
                         $_SESSION['name'] = $user->name;
                         $_SESSION['user'] = $user->user;
                         $_SESSION['userid'] = $user->id;
                         $_SESSION['email'] = $user->email;
                         $_SESSION['login_time'] = time();
                         $_SESSION['group_main_id'] = $user->main_group;
                         header('Location: ../pages/ucp.php');
                         exit;
                 } else msg('login_failed');
        }
        else msg('login_failed');
        break;
    case 'register':

        break;
    case 'logout':
        session_destroy();

        header('Location: ../');
        break;
}
init($content,$meta);