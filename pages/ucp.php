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
if ($do == "")
{
    $meta['title'] = "{s_".$show."}";
    switch ($show)
    {
        case  "profile_edit":
            $user = getUserInformations($_SESSION['userid'],"email,street,firstname,lastname,country,pass,rounds");
            $disp = show("ucp/edit_profile", array( "firstname" => $user->firstname,
                                                    "email" => $user->email,
                                                    "lastname" => $user->lastname,
                                                    "country" => $user->country,
                                                    "street" => $user->street));
            break;
        case 'inbox':

            $msgbox = new Msgbox($_SESSION['userid']);
            $te = new TemplateEngine();

            $case['inbox'] = "";
            if (sizeof($msgbox->inbox) == 0){
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
                    $msg_case['date'] = date("m.d.y H:i:s",$msg->date);
                    $msg_case['id'] = $msg->id;

                    if ($msg->opened) {
                        $case['inbox'] .= show('ucp/msg_inbox_read', $msg_case);
                    } else {
                        $case['inbox'] .= show('ucp/msg_inbox_unread', $msg_case);
                    }
                }
            }
            $case['outbox'] = "";
            if (sizeof($msgbox->outbox) == 0){
                $case['outbox'] = show('ucp/msg_inbox_empty');
            } else {
                foreach ($msgbox->outbox as $msg) {
                    $msg_case['title'] = $msg->title;
                    $msg_case['receiver'] = $msg->receiver_name;
                    $msg_case['gravatar'] = get_gravatar($msg->receiver_email,32);
                    $msg_case['date'] = date("m.d.y H:i:s",$msg->date);
                    $msg_case['id'] = $msg->id;
                    $case['outbox'] .= show('ucp/msg_outbox_read', $msg_case);
                }
            }
            $disp = show('ucp/inbox', $case);
            break;
        case 'new_message':
            backSideFix();
            $case['input'] = show('allround/input_editor');
            $qry = db("SELECT name,id FROM users WHERE id != ".$_SESSION['userid']);
            while ($data = _assoc($qry)) {
                $case['options'] .= show("allround/select_option", array('option' => $data['name'], 'value' => $data['id']));
            }
            $case['content'] = '';
            $disp = show('ucp/msg_new', $case);
            break;
        case 'msg_viewer':
            $msgbox = new Msgbox($_SESSION['userid']);
            if (isset($msgbox->inbox[$_GET['id']])) {
                $msg = $msgbox->inbox[$_GET['id']];
                $msg_case['user_info'] = _to.': '.$msg->sender_name;
                $msg->set_message_read();
            } else if (isset($msgbox->outbox[$_GET['id']])) {
                $msg = $msgbox->outbox[$_GET['id']];
                $msg_case['user_info'] = _from.': '.$msg->receiver_name;
            }
            if(isset($msg)) {
                $msg_case['date'] = date("m.d.y H:i:s",$msg->date);
                $msg_case['subject'] = $msg->title;
                $msg_case['content'] = $msg->content;
                $disp = show('ucp/msg_viewer', $msg_case);
                $meta['title'] = $msg->title;
            } else {
                $disp =  msg(_site_not_found);
            }
            break;
        default:
            $meta['title'] = _user_lobby;
            $disp = getNews($_SESSION['group_main_id']);
        break;
    }
} else {$meta['title'] = "[s_".$do."]";}
switch ($do)
{
    case 'update_profile':
        $user = getUserInformations($_SESSION['userid'],"email,street,firstname,lastname,country,pass,rounds");
        $passwd = customHasher($_POST['pass'],$user->rounds);
        $update = true;
        if ($passwd == $user->pass) {
            if (!empty($_POST['cpasswd'])) {
                if ($_POST['cpasswd'] == $_POST['cpasswd']) {
                    if (strlen($_POST['cpasswd']) > 6) {
                        $passwd = customHasher($_POST['cpasswd'],$user->rounds);
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
         if (db ('Update users Set
                pass = '.sqlString($passwd).',
                email = '.sqlString($_POST['email']).',
                firstname = '.sqlString($_POST['firstname']).',
                lastname = '.sqlString($_POST['lastname']).',
                country = '.sqlString($_POST['country']).',
                street = '.sqlString($_POST['street']).'
                Where id ='.$_SESSION['userid'])) {
                $disp = msg(_change_sucessful);
            } else {
                $disp = msg(_change_failed);
            }
        }

        break;
    case 'msg_send':
        if (permTo('msg_send')) {

            if (sendMessage($_SESSION['userid'],$_POST['receiver'], $_POST['title'], $_POST['content'])) {
                $disp = msg(_msg_sent_successful);
            } else {
                $disp = msg(_msg_sent_failed);
            }
        } else {
            $disp = msg(_no_permissions);
        }
        break;
    case 'delete_msg':
        if (isset($_GET['id'])) {
            $msgbox = new Msgbox($_SESSION['userid']);
            $msg = $msgbox->get_message_by_id($_GET['id']);
            $msg->delete();
            goBack();
        } else {
            $disp = msg(_msg_delete_failed);
        }
        break;
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();