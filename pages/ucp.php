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
    switch ($show)
    {
        case  "profile_edit":
            $user = getUserInformations($_SESSION['userid'],"email,street,firstname,lastname,country,salt,pass,rounds");
            $disp = show("ucp/edit_profile", array( "firstname" => $user->firstname,
                                                    "email" => $user->email,
                                                    "lastname" => $user->lastname,
                                                    "country" => $user->country,
                                                    "street" => $user->street));
            break;
        case 'inbox':
            $qry = getInbox($_SESSION['userid']);
            while ($data = _assoc($qry)) {
                $msg_case['title'] = $data['title'];
                $msg_case['sender'] = $data['name'];
                $msg_case['gravatar'] = get_gravatar($data['email']);
                $msg_case['date'] = date("m.d.y H:i:s",$data['date']);

                if ($data['opened']) {
                    $case['inbox'] .= show('ucp/msg_inbox_read', $msg_case);
                } else {
                    $case['inbox'] .= show('ucp/msg_inbox_unread', $msg_case);
                } 
            }
            $disp = show('ucp/inbox', $case);
            break;
        case 'new_message':
            $case['input'] = show('allround/input_editor');
            $qry = db("SELECT name,id FROM users WHERE id != ".$_SESSION['userid']);
            while ($data = _assoc($qry)) {
                $case['options'] .= show("allround/select_option", array('option' => $data['name'], 'value' => $data['id']));
            }
            $disp = show('ucp/msg_new', $case);
            break;
        default:
            $disp = getNews($_SESSION['group_main_id']);
        break;
    }
}
switch ($do)
{
    case 'update_profile':
        $user = getUserInformations($_SESSION['userid'],"email,street,firstname,lastname,country,salt,pass,rounds");
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
    case 'msg_send':
        if (permTo('msg_send')) {
            if (sendMessage($_SESSION['userid'],$_POST['receiver'], $_POST['title'], $_POST['content'])) {
                $disp = msg(_msg_send_successful);
            } else {
                $disp = msg(_msg_send_failed);
            }
        } else {
            $disp = msg(_no_permissions);
        }
        break;
}
init($disp);

//Funktionen

function getMessage($userid, $msgid) {
    //
}

function getInbox($userid) {
    return db("SELECT m.opened,m.date,m.title,m.content,u.name,u.email FROM messages as m,users as u WHERE u.id = m.sender_id AND m.receiver_id = ".sqlInt($userid));
}

function getOutbox($userid) {
    //
}

function sendMessage($sender, $receiver, $content, $title) {
    return up('INSERT INTO messages ('
            . 'sender_id,'
            . 'receiver_id,'
            . 'opened,'
            . 'date,'
            . 'content,'
            . 'title'
            . ') VALUES ( '
            . sqlInt($sender).','
            . sqlInt($receiver).','
            . '0,'
            . time().','
            . sqlString($title).','
            . sqlString($content).')');
}