<?php
function editbutton()
{
    global $meta;
    if (permTo('site_edit') && isset($meta['page_id']) && $meta['page_id'] == 3) {
        return '<a href="'.Auth::getCurrentUrl().'&do=swap_prev_mode">
                    <div id="edit_site">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </div>
                </a>';
    }
    return '';
}