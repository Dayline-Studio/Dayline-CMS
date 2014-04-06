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
    $meta['title'] = "[s_".$show."]";
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
            $qry_in = getInbox($_SESSION['userid']);
            if (mysqli_num_rows($qry_in) == 0){
                $case['inbox'] = show('ucp/msg_inbox_empty');
            } else {
                while ($data = _assoc($qry_in)) {
                    $msg_case['title'] = $data['title'];
                    if (isset($data['name'])) {
                        $msg_case['sender'] = $data['name'];
                    } else {
                        $msg_case['sender'] = $data['memail'];
                    }
                    $msg_case['gravatar'] = get_gravatar($data['email']);
                    $msg_case['date'] = date("m.d.y H:i:s",$data['date']);
                    $msg_case['id'] = $data['id'];

                    if ($data['opened']) {
                        $case['inbox'] .= show('ucp/msg_inbox_read', $msg_case);
                    } else {
                        $case['inbox'] .= show('ucp/msg_inbox_unread', $msg_case);
                    } 
                }
            }
            $qry_out = getOutbox($_SESSION['userid']);
            if (mysqli_num_rows($qry_out) == 0){
                $case['outbox'] = show('ucp/msg_outbox_empty');
            } else {
                while ($data = _assoc($qry_out)) {
                    $msg_case['title'] = $data['title'];
                    $msg_case['receiver'] = $data['name'];
                    $msg_case['gravatar'] = get_gravatar($data['email'],32);
                    $msg_case['date'] = date("m.d.y H:i:s",$data['date']);
                    $msg_case['id'] = $data['id'];
                    
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
            $disp = show('ucp/msg_new', $case);
            break;
        case 'msg_viewer':
            if($data = getMessage($_SESSION['userid'],$_GET['id'])) {
                $msg_case['sender'] = $data->name;
                $msg_case['date'] = date("m.d.y H:i:s",$data->date);
                $msg_case['subject'] = $data->title;
                $msg_case['content'] = $data->content;

                $meta['title'] = "Msg: ".$data->title;
                if ($data->receiver_id != $_SESSION['userid']) {
                    $disp = show('ucp/msg_viewer', $msg_case);
                    up('UPDATE messages SET opened = 1 WHERE id = '.sqlInt($_GET['id']));
                } else {
                    $disp = show('ucp/msg_viewer_outbox', $msg_case);
                }
            } else {
                $disp = msg(_msg_not_found);
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
        
        if ($_POST['passwd'] != $_POST['cpasswd'] && !empty($_POST['cpasswd'])) {
            $disp = msg(_pass_dont_match);
        } else if ($passwd != $user->pass) {
            $disp = msg(_pass_wrong);
        } else {
            if (strlen($_POST['cpasswd']) > 6) {
                db ('Update users Set
                    pass = '.sqlString(customHasher($_POST['cpasswd'],$user->rounds)).',
                    email = '.sqlString($_POST['email']).',
                    firstname = '.sqlString($_POST['firstname']).',
                    lastname = '.sqlString($_POST['lastname']).',
                    country = '.sqlString($_POST['country']).',
                    street = '.sqlString($_POST['street']).'
                    Where id ='.$_SESSION['userid']);
                $disp = msg(_change_sucessful);
            } $disp = msg(_password_syntax_failed);
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
            if (deleteMessage($_GET['id'],$_SESSION['userid'])) {
                $disp = msg(_msg_delete_sucessful);
            } else {
                $disp = msg(_msg_delete_failed);
            }
        } else {
            $disp = msg(_msg_delete_failed);
        }
        break;
}
init($disp,$meta);

function getMessage($userid, $msgid) {
    return db("SELECT m.email memail,m.receiver_id,m.date,m.title,m.content,u.name,u.email as email "
            . "FROM messages as m "
            . "LEFT JOIN users as u ON m.sender_id = u.id "
            . "WHERE m.receiver_id || m.sender_id = " . sqlInt($userid).' '
            . 'AND m.inbox = 1 '
            . 'AND m.id = ' . sqlInt($msgid)
            ,'object');
}

function getInbox($userid) {
    return db("SELECT m.id,m.opened,m.email memail,m.date,m.title,m.content,u.name,u.email as email "
            . "FROM messages m "
            . "LEFT JOIN users u ON m.sender_id = u.id "
            . "WHERE m.receiver_id = ".sqlInt($userid).' '
            . 'AND m.inbox = 1 '
            . 'ORDER BY date DESC');
}

function getOutbox($userid) {
    return db("SELECT m.id,m.opened,m.email as memail,m.date,m.title,m.content,u.name,u.email as email "
            . "FROM messages as m "
            . "LEFT JOIN users as u ON m.receiver_id = u.id "
            . "WHERE m.sender_id = ".sqlInt($userid).' '
            . 'AND m.outbox = 1 '
            . 'ORDER BY date DESC');
}

function deleteMessage($id, $userid)
{
    $qry = db('SELECT sender_id s, receiver_id r FROM messages WHERE id = '.sqlInt($id),'object');
    if ($qry->r == $userid) {
        if (up('UPDATE messages SET inbox = 0 WHERE id = '.sqlInt($id))) {
            return true;
        } 
    } else if ($qry->s == $userid) {
        if (up('UPDATE messages SET outbox = 0 WHERE id = '.sqlInt($id))) {
            return true;
        } 
    }
    return false;
}