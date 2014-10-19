<?php
// Include CMS System
include "../dc-inc/base.php";
//------------------------------------------------


$te = new TemplateEngine('site/modules/calendar_show');

$today = time();
$day_count = date("t", $today);
$this_month_start = mktime(0,0,0,date('n', $today),1,date('n', $today));

$start_day = date('N',$this_month_start);

$disp = "";

$start_stamp = mktime(0,0,0,date('n', $today),1-$start_day,date('n', $today));

$k = 0;
for ($i = 1; $i < $day_count+$start_day ;$i++) {

    $day = '<td>'.date('j',mktime(0,0,0,date('n', $today),1-$start_day+$i,date('n', $today))).'</td>';
    if ($k == 7) {
        $disp .= "</tr><tr>$day";
        $k = 0;
    } else {
        $disp .= $day;
    }
    $k++;
}

$te->add_var('cal', '<tr>'.$disp.'</tr>');
echo $te->render();
