<?php
if ($do == "")
{
	switch ($action)
	{
		default:
                    $content = show("acp/acp_counter");
                break;
	}
}
switch ($do)
{
    case 'reset':
            if (permTo('reset_counter')) {
                if (up('TRUNCATE `counter`') && up('TRUNCATE `counter_user`')) {
                    $content = msg(_counter_reset_successful);
                } else { 
                    $content = msg(_counter_reset_failed);
                }
            } else $content = msg(_no_permissions);
            break;
}