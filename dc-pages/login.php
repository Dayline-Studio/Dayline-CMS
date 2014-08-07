<?php
// Include CMS System
include "../dc-inc/base.php";
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
        case 'lost_password':
            $disp = show('ucp/lost_password');
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
                    if (sizeof(Db::query(
                                "Select id FROM users where user LIKE :nick OR email LIKE :email",
                                array(
                                    'nick' => strtolower($nick),
                                    'email' => $email)
                            )
                        ) > 0
                    ) {
                        $disp = msg(_already_exists);
                    } else {
                        //Passwort generieren
                        $group_id = $_SESSION['admin'] ? 1 : 3;
                        //sql insert
                        $up = array(
                            'name' => $nick,
                            'pass' => customHasher($_POST['password']),
                            'email' => $email,
                            'user' => strtolower($nick),
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'main_group' => $group_id
                        );
                        if (Db::insert('users', $up)) {
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
    case 'reset_password':
        $user = Db::query('SELECT id FROM users WHERE email LIKE :email LIMIT 1', array('email' => $_POST['email']), PDO::FETCH_OBJ);
        if (isset($user->id)) {
            $user = new User($user->id);
            $activation_code = rand(10000000000000, 99999999999999);
            __c("files")->set('activation_' . $user->id, $activation_code, 600);
            sendMessage(0, $user->id, show(_mail_password_reset_code, array('id' => $user->id, 'code' => $activation_code, 'user' => $user->name, 'domain' => Auth::get_clear_url())), 'Passwort reset');
            goToWithMsg('back', _password_resetcode_send_successful, 'success');
        }
        goToWithMsg('back', _password_resetcode_send_failed, 'danger');
        break;
    case 'activate_new_password':

        $activation_code = __c("files")->get('activation_' . $_GET['id']);
        if ($activation_code != NULL) {
            $new_password = randomstring(8);
            $user = new User($_GET['id']);
            $user->set_new_password($new_password);
            $user->update_changes();
            sendMessage(0, $user->id, show(_mail_new_password, array('password' => $new_password, 'domain' => Auth::get_clear_url())), 'New Password');
            __c("files")->delete('activation_' . $user->id);
            $disp = _password_reset_successful;
        } else $disp = _password_reset_failed;
        break;
}


Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();
