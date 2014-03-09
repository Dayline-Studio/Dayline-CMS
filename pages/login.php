<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Login";
//------------------------------------------------

if ($_SESSION['loggedin'])
{
	header('Location: ucp.php');
}

$content = "";
if ($do == "")
{
	switch ($show)
	{
		case 'register':
		
		if (!isset($_POST['agree']) ||  !isset($_SESSION['agree']))
			{
			$content = show("ucp/terms");
			$_SESSION['agree'] = true;
			
			}
		else {
		$content = show("ucp/register");
		}		
		break;
			default:  
				 $content = show("ucp/login");
	}
}
switch ($do)
{
    case 'login': 
        $username = $_POST['username'];
        $passwort = $_POST['passwort'];

        $user = mysqli_fetch_object(db('Select * From users Where user like '.sqlString(strtolower($username))));
        if ($user->id != "" || customHasher($passwort,$user->salt,$user->rounds) != $user->pass)
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
        } else {
            $content = msg('login_failed');
        }
        break;
    case 'register':
		if($_POST['username'] == "" || $_POST['firstname'] == "" ||
		   $_POST['lastname'] == "" || $_POST['mail'] == "" ||
		   $_POST['password'] == "" || $_POST['password2'] == "") {
			$content = msg("fields_missing");	//felder nicht ausgefüllt
		} else {
                    $nick 	= $_POST['username'];
                    $firstname	= $_POST['firstname'];
                    $lastname 	= $_POST['lastname'];
                    $email      =  $_POST['mail'];
		
                    if ($_POST['password'] != $_POST['password2']){
                        $content = msg("pass_dont_match");
                    } else 
                    if (!check_email_address($_POST['mail'])){
                        $content = msg("mailcheck_failed");
                    } else
                    if (db("Select id "
                            . "FROM users "
                            . "where user LIKE ".strtolower(sqlString($nick))." "
                            . "OR email LIKE ".sqlString($email),'rows') > 0) {
                        $content = msg("already_exists");
                    } else {
                        //Passwort generieren
                        $salt = randomstring(16);
                        $rounds = rand(5000,10000);
                        $pass = customHasher($_POST['password'], $salt, $rounds);
                        
                        //sql insert
                        if (up("INSERT INTO users (id, name, pass, salt, email, rounds, user, street, firstname, lastname, country, main_group) "
                                . "VALUES (NULL, ".sqlString($nick).", ".sqlString($pass).", ".sqlString($salt).", ".sqlString($email).", ".sqlInt($rounds).", ".strtolower(sqlString($nick)).", '', ".sqlString($firstname).", ".sqlString($lastname).", '', '0')"))
                        {
                            $content = msg("regist_sucess");
                        }
                        else {
                            $content = msg("regist_failed");
                        }
                   }
                }
        break;
    case 'logout':
        session_destroy();

        header('Location: ../');
        break;
}
init($content,$meta);
