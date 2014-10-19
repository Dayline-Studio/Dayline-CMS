<?php
function editbutton()
{
    global $meta;
    if (permTo('site_edit') && isset($meta['page_id']) && $meta['page_id'] == 3) {
        $class = !$_SESSION['prev_mode'] ? 'glyphicon-user' : 'glyphicon-pencil';
        return '<a href="'.Auth::getCurrentUrl().'&do=swap_prev_mode">
                    <div id="edit_site">
                        <span class="glyphicon '.$class.'"></span>
                    </div>
                </a>';
    }
    return '';
}