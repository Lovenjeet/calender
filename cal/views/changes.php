<?php
/*
= view calendar changes (added / edited / deleted events) since specified date =

� Copyright 2009-2013  Issam - www.Issam.eu

This file is part of the LuxCal Web Calendar.

The LuxCal Web Calendar is free software: you can redistribute it and/or modify it under 
the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 3 of the License, or (at your option) any later version.

The LuxCal Web Calendar is distributed in the hope that it will be useful, but WITHOUT 
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with the LuxCal 
Web Calendar. If not, see: http://www.gnu.org/licenses/.
*/

require './common/retrieve2.php';

function showGrid(&$events,$date) {
	global $privs, $wkDays, $set, $xx, $months;
	foreach ($events as $evt) {
		switch ($evt['r_t']) { //make repeat text
			case 0: $repeat = ''; break;
			case 1: $repeat = $xx['evt_repeat'].' '.$xx['evt_interval1_'.$evt['r_i']].' '.$xx['evt_period1_'.$evt['r_p']]; break;
			case 2: $repeat = $xx['evt_repeat_on'].' '.$xx['evt_interval2_'.$evt['r_i']].' '.$wkDays[$evt['r_p']].' '.$xx['of'].' '.($evt['r_m'] ? $months[$evt['r_m']-1] : $xx['evt_each_month']);
		}
		if ($evt['r_t'] > 0 and $evt['r_u']) {
			$repeat .= " {$xx['evt_until']} ".IDtoDD($evt['r_u']);
		}
		$mayEdit = ($privs > 2 or ($privs == 2 and $evt['uid'] == $_SESSION['uid'])) ? true : false;
		echo "<table>\n<tr>
			<td class='lMarginCh'>".(($evt['sts'] < 0) ? $xx['chg_deleted'] : ($evt['mda'] > $evt['ada'] ? $xx['chg_edited'] : $xx['chg_added']))."</td>\n";
		if ($evt['app'] and !$evt['apd']) { $eBoxStyle = 'border-left:2px solid #ff0000;'; }
		$eBoxStyle = $eBoxStyle ? " style='{$eBoxStyle}'" : '';
		echo "<td class='eBox'{$eBoxStyle}>".IDtoDD($evt['sda']);
		if ($evt['sti']) echo " {$xx['at_time']} ".ITtoDT($evt['sti']);
		if ($evt['eda'] or $evt['eti']) echo ' -';
		if ($evt['eda']) echo ' '.IDtoDD($evt['eda']).($evt['eti'] ? " {$ax['at_time']}" : '');
		if ($evt['eti']) echo ' '.ITtoDT($evt['eti']);
		echo $evt['ald'] ? ' '.$xx['vws_all_day'] : '';
		if ($repeat) { echo '. '.$repeat; }
		if ($set['eventColor']) {
			$eStyle = ($evt['cco'] ? "color:{$evt['cco']};" : '').($evt['cbg'] ? "background-color:{$evt['cbg']};" : '');
		} else {
			$eStyle = $evt['uco'] ? "background-color:{$evt['uco']};" : '';
		}
		$eStyle = $eStyle ? " style='{$eStyle}'" : '';
		if ($evt['sts'] >= 0 and $mayEdit) {
			echo "<h6><span class='point'{$eStyle} onclick=\"editE({$evt['eid']},'{$date}');\">{$evt['tit']}</span></h6>\n";
		} else {
			echo "<h6><span{$eStyle}>{$evt['tit']}</span></h6>\n";
		}
		if ($set['details4All'] or $mayEdit) {
			if ($evt['ven']) { echo "{$xx['vws_venue']}: {$evt['ven']}<br>\n"; }
			if ($evt['des']) { echo "{$evt['des']}<br>\n"; }
			if ($evt['rem'] >= 0 and $mayEdit) { echo "# {$xx['chg_notify']}: {$evt['rem']} {$xx['chg_days']} #<br>\n"; }
		}
		if ($set['showCatName']) { echo "{$xx['evt_category']}: {$evt['cnm']}<br>\n"; }
		if ($set['showAdEd']) {
			echo "{$xx['vws_added']}: ".IDtoDD($evt['ada'])." ({$evt['una']})<br>\n";;
			if ($evt['mda'] and $evt['edr']) { echo "{$xx['vws_edited']}: ".IDtoDD($evt['mda'])." ({$evt['edr']})<br>\n";; }
		}
		echo "</td>\n</tr>\n</table>\n<br>\n";
	}
}

//sanity check
if (!defined('LCC')) { exit('not permitted ('.substr(basename(__FILE__),0,-4).')'); } //lounch via script only

//main program
$evtList = array();
$fromD = (isset($_POST['fromD'])) ? DDtoID($_POST['fromD']) : date('Y-m-d');
$fromD = min($fromD, date('Y-m-d'));

grabChanges($fromD,0); //query db for changes

//display header
echo "<div class='headCh'>
	<form method='post' id='selectD' name='selectD' action='index.php'>{$xx['chg_from_date']}: 
	<input type='text' name='fromD' id='fromD' value='".IDtoDD($fromD)."' size='10'>
	<button class='noPrint' title=\"{$xx['chg_select_date']}\" onclick=\"dPicker(0,'selectD','fromD');return false;\">&larr;</button>
	</form>
</div>\n";

//display changes
echo "<div id='scrollBox'".($mobile ? '' : " class='scrollBoxCh'").">
	<div class='eventBg'>\n";
if ($fromD != date('Y-m-d')) {
	echo "<h4>".makeD($fromD,2)." - ".makeD(date('Y-m-d'),2)."</h4>\n<br>\n";
}
if ($evtList) {
	foreach($evtList as $date => &$events) {
		echo "<br><h6>".$xx['chg_changed_on']." ".makeD($date,5)."</h6><br>\n";
		showGrid($events,$date);
	}
} else {
	echo "<br>{$xx['chg_no_changes']}<br>\n";
}
echo "</div>\n<br>
</div>\n";
?>
