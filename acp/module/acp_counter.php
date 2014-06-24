<?php
if ($do == "") {
    switch ($action) {
        default:
            $disp = show("acp/acp_counter");
            break;
    }
}
switch ($do) {
    case 'reset':
        if (permTo('reset_counter')) {
            if (up('TRUNCATE `counter`') && up('TRUNCATE `counter_user`')) {
                $disp = msg(_counter_reset_successful);
            } else {
                $disp = msg(_counter_reset_failed);
            }
        } else $disp = msg(_no_permissions);
        break;
}