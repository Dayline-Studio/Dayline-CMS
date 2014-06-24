<?php
// Include CMS System
include "../inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "Login";
$meta['page_id'] = 1;
//------------------------------------------------

if ($_SESSION['loggedin']) {
    header('Location: ucp.php');
}

$disp = "";
if ($do == "") {
    switch ($show) {
        case 'register':
            if (!isset($_POST['agree']) || !isset($_SESSION['agree'])) {
                $disp = show("ucp/terms");
                $_SESSION['agree'] = true;
            } else {
                $disp = show("ucp/register");
            }
            break;
        default:
            $disp = show("ucp/login");
    }
}
switch ($do) {
    case 'login':
        if (Auth::login($_POST['username'], $_POST['passwort'])) {
            header('Location: ' . Config::$path['pages'] . 'ucp.php');
        } else {
            $disp = msg(_login_failed);
        }
        break;
    case 'register':
        if ($_POST['username'] == "" || $_POST['firstname'] == "" ||
            $_POST['lastname'] == "" || $_POST['mail'] == "" ||
            $_POST['password'] == "" || $_POST['password2'] == ""
        ) {
            $disp = msg(_fields_missing);
        } else {
            $nick = $_POST['username'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['mail'];

            if ($_POST['password'] != $_POST['password2']) {
                $disp = msg(_pass_dont_match);
            } else
                if (!check_email_address($_POST['mail'])) {
                    $disp = msg(_mailcheck_failed);
                } else
                    if (db("Select id "
                            . "FROM users "
                            . "where user LIKE " . strtolower(sqlString($nick)) . " "
                            . "OR email LIKE " . sqlString($email), 'rows') > 0
                    ) {
                        $disp = msg(_already_exists);
                    } else {
                        //Passwort generieren
                        $rounds = rand(5000, 10000);
                        $pass = customHasher($_POST['password'], $rounds);

                        //sql insert
                        if (up("INSERT INTO users (id, name, pass, email, rounds, user, street, firstname, lastname, country, main_group) "
                            . "VALUES (NULL, " . sqlString($nick) . ", " . sqlString($pass) . ", " . sqlString($email) . ", " . sqlInt($rounds) . ", " . strtolower(sqlString($nick)) . ", '', " . sqlString($firstname) . ", " . sqlString($lastname) . ", '', '0')")
                        ) {
                            $disp = msg(_regist_sucess);
                        } else {
                            $disp = msg(_regist_failed);
                        }
                    }
        }
        break;
    case 'logout':
        Auth::logout();
        header('Location: ../');
        break;
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();
