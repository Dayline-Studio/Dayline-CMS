<?php
// Include CMS System
include "../dc-inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "UCP";
$meta['page_id'] = 4;
//------------------------------------------------

if (!$_SESSION['loggedin']) {
    header('Location: ../');
}
if ($do == "") {
    $meta['title'] = "{s_" . $show . "}";
    switch ($show) {
        case  "profile_edit":
            $user = new UserModel($_SESSION['userid']);
            $disp = show("ucp/edit_profile", array("firstname" => $user->firstname,
                "email" => $user->email,
                "lastname" => $user->lastname,
                "country" => $user->country,
                "street" => $user->street));
            break;
        case 'inbox':
            $msgbox = new MsgBox($_SESSION['userid']);
            $te = new TemplateEngine();

            $case['inbox'] = "";
            if (sizeof($msgbox->inbox) == 0) {
                $case['inbox'] = show('ucp/msg_inbox_empty');
            } else {
                foreach ($msgbox->inbox as $msg) {
                    $msg_case['title'] = $msg->title;
                    if (isset($msg->sender_name)) {
                        $msg_case['sender'] = $msg->sender_name;
                    } else {
                        $msg_case['sender'] = $msg->sender_email;
                    }
                    $msg_case['gravatar'] = get_gravatar($msg->sender_email);
                    $msg_case['date'] = date("m.d.y H:i:s", $msg->date);
                    $msg_case['id'] = $msg->id;

                    if ($msg->opened) {
                        $case['inbox'] .= show('ucp/msg_inbox_read', $msg_case);
                    } else {
                        $case['inbox'] .= show('ucp/msg_inbox_unread', $msg_case);
                    }
                }
            }
            $case['outbox'] = "";
            if (sizeof($msgbox->outbox) == 0) {
                $case['outbox'] = show('ucp/msg_inbox_empty');
            } else {
                foreach ($msgbox->outbox as $msg) {
                    $msg_case['title'] = $msg->title;
                    $msg_case['receiver'] = $msg->receiver_name;
                    $msg_case['gravatar'] = get_gravatar($msg->receiver_email, 32);
                    $msg_case['date'] = date("m.d.y H:i:s", $msg->date);
                    $msg_case['id'] = $msg->id;
                    $case['outbox'] .= show('ucp/msg_outbox_read', $msg_case);
                }
            }
            $disp = show('ucp/inbox', $case);
            break;
        case 'new_message':
            Auth::backSideFix();
            $case['options'] = '';
            $case['input'] = show('allround/input_editor');
            $qry = Db::npquery("SELECT name,id FROM users WHERE id != " . $_SESSION['userid']);
            foreach ($qry as $user) {
                $case['options'] .= show("allround/select_option", array('option' => $user['name'], 'value' => $user['id']));
            }
            $case['content'] = '';
            $disp = show('ucp/msg_new', $case);
            break;
        case 'msg_viewer':
            $msgbox = new MsgBox($_SESSION['userid']);
            if (isset($msgbox->inbox[$_GET['id']])) {
                $msg = $msgbox->inbox[$_GET['id']];
                $msg_case['user_info'] = _to . ': ' . $msg->sender_name;
                $msg->set_message_read();
            } else if (isset($msgbox->outbox[$_GET['id']])) {
                $msg = $msgbox->outbox[$_GET['id']];
                $msg_case['user_info'] = _from . ': ' . $msg->receiver_name;
            }
            if (isset($msg)) {
                $msg_case['date'] = date("m.d.y H:i:s", $msg->date);
                $msg_case['subject'] = $msg->title;
                $msg_case['content'] = $msg->content;
                $disp = show('ucp/msg_viewer', $msg_case);
                $meta['title'] = $msg->title;
            } else {
                $disp = msg(_site_not_found);
            }
            break;
        default:
            $disp = '';
            break;
    }
} else {
    $meta['title'] = "[s_" . $do . "]";
}
switch ($do) {
    case 'update_profile':
        $user = new UserModel($_SESSION['userid']);
        $update = true;
        if (custom_verify($_POST['pass'],$user->pass)) {
            if (!empty($_POST['cpasswd'])) {
                if ($_POST['cpasswd'] == $_POST['cpasswd']) {
                    if (strlen($_POST['cpasswd']) > 6) {
                        $passwd = customHasher($_POST['cpasswd']);
                    } else {
                        $disp = msg(_password_syntax_failed);
                        $update = false;
                    }
                } else {
                    $disp = msg(_pass_dont_match);
                    $update = false;
                }
            }
        } else {
            $disp = msg(_pass_wrong);
            $update = false;
        }
        if ($update) {
            $up['email'] = $_POST['email'];
            $up['firstname'] = $_POST['firstname'];
            $up['lastname'] = $_POST['lastname'];
            $up['country'] = $_POST['country'];
            $up['street'] = $_POST['street'];
            $up['pass'] = customHasher($_POST['cpasswd']);

            if (Db::update('users', $_SESSION['userid'], $up)) {
                goToWithMsg('back', _change_sucessful, 'success');
            } else {
                $disp = msg(_change_failed);
            }
        }
        break;
    case 'msg_send':
        if (permTo('msg_send')) {

            if (sendMessage($_SESSION['userid'], $_POST['receiver'], $_POST['title'], $_POST['content'])) {
                goToWithMsg('back', _msg_sent_successful, 'success');
            } else {
                $disp = msg(_msg_sent_failed);
            }
        } else {
            $disp = msg(_no_permissions);
        }
        break;
    case 'delete_msg':
        if (isset($_GET['id'])) {
            $msgbox = new MsgBox($_SESSION['userid']);
            $msg = $msgbox->get_message_by_id($_GET['id']);
            $msg->delete();
            goToWithMsg('back', _msg_delete_sucessful, 'success');
        } else {
            $disp = msg(_msg_delete_failed);
        }
        break;
}

//Seite Rendern
$myDisplay = new Display($meta);
$myDisplay->setContent($disp);
$myDisplay->render();