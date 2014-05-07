<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Login";
/**--**/  $meta['page_id'] = 1;
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
		if (!isset($_POST['agree']) ||  !isset($_SESSION['agree'])) {
                    $content = show("ucp/terms");
                    $_SESSION['agree'] = true;
                } else {
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
        if ($_POST['username'] != "" && $_POST['passwort'] != "")
        {
            if (!check_email_address($_POST['username'])) {
                    $sql = "user";
            } else {
                    $sql = "email";
            }
            if (db("SELECT id FROM users WHERE ".$sql." LIKE ".sqlString(strtolower($_POST['username'])),'rows') == 1)
            {
                $username = $_POST['username'];
                $passwort = $_POST['passwort'];

                $user = db('Select * From users Where '.$sql.' like '.sqlString(strtolower($username)),'object');

                if (customHasher($passwort,$user->rounds) == $user->pass)
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
                    $disp = msg(_wrong_pw);
                }
            } else { 
                $disp = msg(_user_not_found);
            } 
        } else { 
            $disp = msg(_fields_missing);  
        }
        break;
    case 'register':
        if($_POST['username'] == "" || $_POST['firstname'] == "" ||
           $_POST['lastname'] == "" || $_POST['mail'] == "" ||
           $_POST['password'] == "" || $_POST['password2'] == "") {
                $disp = msg(_fields_missing);
        } else {
            $nick 	= $_POST['username'];
            $firstname	= $_POST['firstname'];
            $lastname 	= $_POST['lastname'];
            $email      =  $_POST['mail'];

            if ($_POST['password'] != $_POST['password2']){
                $disp = msg(_pass_dont_match);
            } else 
            if (!check_email_address($_POST['mail'])){
                $disp = msg(_mailcheck_failed);
            } else
            if (db("Select id "
                    . "FROM users "
                    . "where user LIKE ".strtolower(sqlString($nick))." "
                    . "OR email LIKE ".sqlString($email),'rows') > 0) {
                $disp = msg(_already_exists);
            } else {
                //Passwort generieren
                $rounds = rand(5000,10000);
                $pass = customHasher($_POST['password'], $rounds);

                //sql insert
                if (up("INSERT INTO users (id, name, pass, email, rounds, user, street, firstname, lastname, country, main_group) "
                        . "VALUES (NULL, ".sqlString($nick).", ".sqlString($pass).", ".sqlString($email).", ".sqlInt($rounds).", ".strtolower(sqlString($nick)).", '', ".sqlString($firstname).", ".sqlString($lastname).", '', '0')"))
                {
                    $disp = msg(_regist_sucess);
                }
                else {
                    $disp = msg(_regist_failed);
                }
           }
        }
        break;
    case 'logout':
        session_destroy();

        header('Location: ../');
        break;
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();
