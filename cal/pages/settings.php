<?php
/*
= Change Calendar Settings page =

© Copyright 2009-2013  Issam - www.Issam.eu

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

//sanity check
if (!defined('LCC')) { exit('not permitted ('.substr(basename(__FILE__),0,-4).')'); } //lounch via script only

//initialize
$adminLang = (file_exists('./lang/ai-'.strtolower($_SESSION['cL']).'.php')) ? $_SESSION['cL'] : "English";
require './lang/ai-'.strtolower($adminLang).'.php';

$msg = "";

if ($privs != 9) { //no admin
	echo "<p class='error'>{$ax['no_way']}</p>\n"; exit;
}

$defSet = array( //setting => default value, description
	'calendarTitle' => array('LuxCal Calendar','Calendar title displayed in the top bar'),
	'calendarUrl' => array('http://'.$_SERVER['SERVER_NAME'].rtrim(dirname($_SERVER["PHP_SELF"]),'/').'/index.php','Calendar link (URL)'),
	'calendarEmail' => array('calendar@email.com','Sender in and receiver of email notifications'),
	'backLinkUrl' => array('','Nav bar Back button URL (blank: no button, url: Back button)'),
	'timeZone' => array('Europe/Amsterdam','Calendar time zone'),
	'notifSender' => array(0,'Sender of notification emails (0:calendar, 1:user)'),
	'navButText' => array(0,'Navigation buttons with text or icons (0:icons, 1:text)'),
	'navTodoList' => array(1,'Display Todo List button in nav bar (0:no, 1:yes)'),
	'navUpcoList' => array(1,'Display Upco List button in nav bar (0:no, 1:yes)'),
	'rssFeed' => array(1,'Display RSS feed links in footer and HTML head (0:no, 1:yes)'),
	'userMenu' => array(1,'Display user filter menu'),
	'catMenu' => array(1,'Display category filter menu'),
	'langMenu' => array(0,'Display ui-language selection menu'),
	'defaultView' => array(2,'Calendar view at start-up (1:year, 2:month, 3:work month, 4:week, 5:work week 6:day, 7:upcoming, 8:changes)'),
	'language' => array('English','Default user interface language'),
	'privEvents' => array(1,'Private events (0:disabled 1:enabled, 2:default, 3:always)'),
	'details4All' => array(1,'Show event details to all users (0:no, 1:yes)'),
	'eventColor' => array(1,'Event colors (0:user color, 1:cat color)'),
	'selfReg' => array(0,'Self-registration (0:no, 1:yes)'),
	'selfRegPrivs' => array(1,'Self-reg rights (1:view, 2:post self, 3:post all)'),
	'selfRegNot' => array(0,'User self-reg notification to admin (0:no, 1:yes)'),
	'cookieExp' => array(30,'Number of days before a Remember Me cookie expires'),
	'yearStart' => array(0,'Start month in year view (1-12 or 0, 0:current month)'),
	'colsToShow' => array(3,'Number of months to show per row in year view'),
	'rowsToShow' => array(4,'Number of rows to show in year view'),
	'weeksToShow' => array(10,'Number of weeks to show in month view'),
	'workWeekDays' => array('12345','Days to show in work weeks (1:mo - 7:su)'),
	'lookaheadDays' => array(14,'Days to look ahead in upcoming view, todo list and RSS feeds'),
	'dwStartHour' => array(6,'Day/week view start hour'),
	'dwEndHour' => array(18,'Day/week view end hour'),
	'dwTimeSlot' => array(30,'Day/week time slot in minutes'),
	'dwTsHeight' => array(20,'Day/week time slot height in pixels'),
	'eventHBox' => array(1,'Event details hover box (0:no, 1:yes)'),
	'showAdEd' => array(1,'Show event owner (0:no, 1:yes)'),
	'showCatName' => array(1,'Show cat name in various views (0:no, 1:yes)'),
	'showLinkInMV' => array(1,'Show URL-links in month view (0:no, 1:yes)'),
	'monthInDCell' => array(0,'Show in month view month for each day (0:no, 1:yes)'),
	'dateFormat' => array('d.m.y','Date format: yyyy-mm-dd (y:yyyy, m:mm, d:dd)'),
	'MdFormat' => array('d M','Date format: dd month (d:dd, M:month)'),
	'MdyFormat' => array('d M y','Date format: dd month yyyy (d:dd, M:month, y:yyyy)'),
	'MyFormat' => array('M y','Date format: month yyyy (M:month, y:yyyy)'),
	'DMdFormat' => array('WD d M','Date format: weekday dd month (WD:weekday d:dd, M:month)'),
	'DMdyFormat' => array('WD d M y','Date format: weekday dd month yyyy (WD:weekday d:dd, M:month, y:yyyy)'),
	'timeFormat' => array('h:m','Time format (H:hh, h:h, m:mm, a:am|pm, A:AM|PM)'),
	'weekStart' => array(1,'Week starts on Sunday(0) or Monday(1)'),
	'weekNumber' => array(1,'Week numbers on(1) or off(0)'),
	'mailServer' => array(1,'Mail server (0:mail disabled, 1:PHP mail, 2:SMTP mail)'),
	'smtpServer' => array('','SMTP mail server name'),
	'smtpPort' => array(465,'SMTP port number'),
	'smtpSsl' => array(1,'Use SSL (Secure Sockets Layer) (0:no, 1:yes)'),
	'smtpAuth' => array(1,'Use SMTP authentication (0:no, 1:yes)'),
	'smtpUser' => array('','SMTP username'),
	'smtpPass' => array('','SMTP password'),
	'adminCronSum' => array(1,'Send cron job summary to admin (0:no, 1:yes)'),
	'chgEmailList' => array('','Recipient email addresses for calendar changes'),
	'chgNofDays' => array(1,'Number of days to look back for calendar changes'),
	'icsExport' => array(0,'Daily export of events in iCal format (0:no 1:yes)'),
	'eventExp' => array(0,'Number of days after due when an event expires / can be deleted (0:never)'),
	'maxNoLogin' => array(0,'Number of days not logged in, before deleting user account (0:never delete)'),
	'miniCalView' => array(1,'Mini calendar view (1:full month, 2:work month)'),
	'miniCalPost' => array(0,'Mini calendar event posting (0:no, 1:yes)'),
	'miniCalHBox' => array(1,'Mini calendar event hover box (0:no, 1:yes)'),
	'mCalUrlFull' => array('','Mini calendar link to full calendar'),
	'sideBarHBox' => array(1,'Sidebar event hover box (0:no, 1:yes)'),
	'showLinkInSB' => array(1,'Show URL-links in sidebar (0:no, 1:yes)'),
	'sideBarDays' => array(14,'Days to look ahead in sidebar')
);

if (isset($_POST["save"])) { //get posted settings
	foreach ($_POST['pSet'] as $key => $value) {
		$pSet[$key] = is_numeric($value) ? intval($value) : stripslashes(trim($value));
	}
} else { //get current settings
	foreach ($defSet as $key => $value) {
		$pSet[$key] = isset($set[$key]) ? $set[$key] : $value[0];
	}
}

$errors = array_fill(0, 30, ''); $i = 0; //init

if (isset($_POST["save"])) { //validate settings
	if (!$pSet['calendarTitle']) { $errors[$i] = ' class="inputError"'; } $i++;
	if (!$pSet['calendarUrl'] or !preg_match($rxCalURL,$pSet['calendarUrl'])) { $errors[$i] = " class='inputError'"; } $i++;
	if (substr($pSet['calendarUrl'],0,4) != 'http') { $pSet['calendarUrl'] = 'http://'.$pSet['calendarUrl']; }
	if (!$pSet['calendarEmail'] or !preg_match($rxEmailX, $pSet['calendarEmail'])) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['backLinkUrl'] and substr($pSet['backLinkUrl'],0,4) != 'http') { $pSet['backLinkUrl'] = 'http://'.$pSet['backLinkUrl']; }
	if (!$pSet['timeZone']) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['cookieExp'] < 1 or $pSet['cookieExp'] > 365) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['yearStart'] < 0 or $pSet['yearStart'] > 12) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['colsToShow'] < 1 or $pSet['colsToShow'] > 6) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['rowsToShow'] < 1 or $pSet['rowsToShow'] > 10) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['weeksToShow'] < 0 or $pSet['weeksToShow'] > 20) { $errors[$i] = " class='inputError'"; } $i++;
	if (!preg_match("/^[1-7]{1,7}$/", $pSet['workWeekDays'])) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['lookaheadDays'] < 1 or $pSet['lookaheadDays'] > 365) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['dwStartHour'] < 0 or $pSet['dwStartHour'] > 18 or $pSet['dwStartHour'] > ($pSet['dwEndHour'] - 4)) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['dwEndHour'] > 24 or $pSet['dwEndHour'] < 6 or $pSet['dwStartHour'] > ($pSet['dwEndHour'] - 4)) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['dwTsHeight'] < 10 or $pSet['dwTsHeight'] > 60) { $errors[$i] = " class='inputError'"; } $i++;
//the following regexs use lookahead assertion
	if (!preg_match ('%^([ymd])([^\da-zA-Z])(?!\1)([ymd])\2(?!(\1|\3))[ymd]$%',$pSet['dateFormat'])) { $errors[$i] = " class='inputError'"; } $i++;
	if (!preg_match ('%^([Md])[^\da-zA-Z]+(?!\1)[Md]$%',$pSet['MdFormat'])) { $errors[$i] = " class='inputError'"; } $i++;
	if (!preg_match ('%^([Myd])[^\da-zA-Z]+(?!\1)([Myd])[^\da-zA-Z]+(?!(\1|\2))[Myd]$%',$pSet['MdyFormat'])) { $errors[$i] = " class='inputError'"; } $i++;
	if (!preg_match ('%^([My])[^\da-zA-Z]+(?!\1)[My]$%',$pSet['MyFormat'])) { $errors[$i] = " class='inputError'"; } $i++;
	if (!preg_match ('%^(WD|[Md])[^\da-zA-Z]+(?!\1)(WD|[Md])[^\da-zA-Z]+(?!(\1|\2))(WD|[Md])$%',$pSet['DMdFormat'])) { $errors[$i] = " class='inputError'"; } $i++;
	if (!preg_match ('%^(WD|[Mdy])[^\da-zA-Z]+(?!\1)(WD|[Mdy])[^\da-zA-Z]+(?!(\1|\2))(WD|[Mdy])[^\da-zA-Z]+(?!(\1|\2\3))(WD|[Mdy])$%',$pSet['DMdyFormat'])) { $errors[$i] = " class='inputError'"; } $i++;
	if (!preg_match ('%^([Hhm])[^\da-zA-Z](?!\1)[Hhm](\s?[aA])?$%',$pSet['timeFormat'])) { $errors[$i] = " class='inputError'"; } $i++;
	if (!$pSet['smtpServer'] and $pSet['mailServer'] == 2) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['smtpPort'] < 0 or $pSet['smtpPort'] > 10025) { $errors[$i] = " class='inputError'"; } $i++; //10025 max port nr for SMTP
	if (!$pSet['smtpUser']  and $pSet['smtpAuth']  and $pSet['mailServer'] == 2) { $errors[$i] = " class='inputError'"; } $i++;
	if (!$pSet['smtpPass']  and $pSet['smtpAuth']  and $pSet['mailServer'] == 2) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['chgNofDays'] < 0 or $pSet['chgNofDays'] > 30) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['eventExp'] < 0 or $pSet['eventExp'] > 999) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['maxNoLogin'] < 0 or $pSet['maxNoLogin'] > 365) { $errors[$i] = " class='inputError'"; } $i++;
	if ($pSet['sideBarDays'] < 1 or $pSet['sideBarDays'] > 365) { $errors[$i] = " class='inputError'"; } $i++;

	if (!in_array(" class='inputError'",$errors)) { //no errors, save settings in database
		$result = dbQuery("DELETE FROM [db]settings");
		if ($result) {
			$values = '';
			foreach($pSet as $key => $value) {
				$values .= "('{$key}','".(is_string($value) ? mysql_real_escape_string($value) : $value)."','{$defSet[$key][1]}'),";
			}
			$values = rtrim($values,',');
			$result = dbQuery("INSERT INTO [db]settings VALUES {$values}"); //save
		}
		if ($result) {
			$msg = $ax['set_settings_saved'];
			unset($_SESSION['settings']); //force retrieve of settings
		} else {
			$msg = $ax['set_save_error'];
		}
	} else { //errors found
		$msg .= $ax['set_missing_invalid'];
	}
}

echo "<br><p class=\"error noPrint\">".(($msg) ? $msg : $ax['hover_for_details'])."</p>\n";
?>
<!-- display form fields -->
<form action="index.php" method="post">
<div class="scrollBoxSe">
<div class="centerBox">
<table>
<tr><td><table class='fieldBoxFix'>
<?php
$i = 0; //init errors index
echo "
	<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_general_settings']}&nbsp;</td></tr>
	
	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['calendarTitle_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['calendarTitle_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[calendarTitle]' size='45' value=\"{$pSet['calendarTitle']}\"></td></tr>
	
	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['calendarUrl_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['calendarUrl_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[calendarUrl]' size='45' value=\"{$pSet['calendarUrl']}\"></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['calendarEmail_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['calendarEmail_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[calendarEmail]' size='45' value=\"{$pSet['calendarEmail']}\"></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['backLinkUrl_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['backLinkUrl_label']}:</td>
	<td><input type='text' name='pSet[backLinkUrl]' size='45' value=\"{$pSet['backLinkUrl']}\"></td></tr>

	<tr><td class=\"labelFix\" onmouseover=\"pop('".htmlspecialchars($ax['timeZone_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['timeZone_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[timeZone]' size='24' value=\"{$pSet['timeZone']}\"> {$ax['see']}: <strong>[<a href='http://us3.php.net/manual/en/timezones.php' target='_blank'>{$ax['time_zones']}</a>]</strong></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['notifSender_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['notifSender_label']}:</td>
	<td><input type='radio' name='pSet[notifSender]' value='0'".($pSet['notifSender'] == 0 ? " checked='checked'" : '')."> {$ax['calendar']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[notifSender]' value='1'".($pSet['notifSender'] == 1 ? " checked='checked'" : '')."> {$ax['user']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['navButText_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['navButText_label']}:</td>
	<td><input type='radio' name='pSet[navButText]' value='0'".($pSet['navButText'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[navButText]' value='1'".($pSet['navButText'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['navTodoList_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['navTodoList_label']}:</td>
	<td><input type='radio' name='pSet[navTodoList]' value='0'".($pSet['navTodoList'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[navTodoList]' value='1'".($pSet['navTodoList'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['navUpcoList_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['navUpcoList_label']}:</td>
	<td><input type='radio' name='pSet[navUpcoList]' value='0'".($pSet['navUpcoList'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[navUpcoList]' value='1'".($pSet['navUpcoList'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['rssFeed_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['rssFeed_label']}:</td>
	<td><input type='radio' name='pSet[rssFeed]' value='0'".($pSet['rssFeed'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[rssFeed]' value='1'".($pSet['rssFeed'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_opanel_settings']}&nbsp;</td></tr>




	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['defaultView_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['defaultView_label']}:</td>
	<td><select name='pSet[defaultView]'>
	<option value='1'".($pSet['defaultView'] == "1" ? " selected='selected'" : '').">{$xx['hdr_year']}</option>
	<option value='2'".($pSet['defaultView'] == "2" ? " selected='selected'" : '').">{$xx['hdr_month_full']}</option>
	<option value='3'".($pSet['defaultView'] == "3" ? " selected='selected'" : '').">{$xx['hdr_month_work']}</option>
	<option value='4'".($pSet['defaultView'] == "4" ? " selected='selected'" : '').">{$xx['hdr_week_full']}</option>
	<option value='5'".($pSet['defaultView'] == "5" ? " selected='selected'" : '').">{$xx['hdr_week_work']}</option>
	<option value='6'".($pSet['defaultView'] == "6" ? " selected='selected'" : '').">{$xx['hdr_day']}</option>
	<option value='7'".($pSet['defaultView'] == "7" ? " selected='selected'" : '').">{$xx['hdr_upcoming']}</option>
	<option value='8'".($pSet['defaultView'] == "8" ? " selected='selected'" : '').">{$xx['hdr_changes']}</option>
	</select></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['language_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['language_label']}:</td>
	<td><select name='pSet[language]'>\n";
	$files = scandir("lang/");
	foreach ($files as $file) {
		if (substr($file, 0, 3) == "ui-") {
			$lang = strtolower(substr($file,3,-4));
			echo "\t<option value=\"{$lang}\"".(strtolower($pSet['language']) == $lang ? " selected='selected'" : '').">".ucfirst($lang)."</option>\n";
		}
	}
echo "</select></td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_event_settings']}&nbsp;</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['privEvents_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['privEvents_label']}:</td>
	<td><select name='pSet[privEvents]'>
	<option value='0'".($pSet['privEvents'] == "0" ? " selected='selected'" : '').">{$ax['disabled']}</option>
	<option value='1'".($pSet['privEvents'] == "1" ? " selected='selected'" : '').">{$ax['enabled']}</option>
	<option value='2'".($pSet['privEvents'] == "2" ? " selected='selected'" : '').">{$ax['default']}</option>
	<option value='3'".($pSet['privEvents'] == "3" ? " selected='selected'" : '').">{$ax['always']}</option>
	</select></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['details4All_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['details4All_label']}:</td>
	<td><input type='radio' name='pSet[details4All]' value='0'".($pSet['details4All'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[details4All]' value='1'".($pSet['details4All'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['eventColor_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['eventColor_label']}:</td>
	<td><input type='radio' name='pSet[eventColor]' value='0'".($pSet['eventColor'] == 0 ? " checked='checked'" : '')."> {$ax['owner_color']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[eventColor]' value='1'".($pSet['eventColor'] == 1 ? " checked='checked'" : '')."> {$ax['cat_color']}</td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_user_settings']}&nbsp;</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['selfReg_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['selfReg_label']}:</td>
	<td><input type='radio' name='pSet[selfReg]' value='0'".($pSet['selfReg'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[selfReg]' value='1'".($pSet['selfReg'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['selfRegPrivs_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['selfRegPrivs_label']}:</td>
	<td><input type='radio' name='pSet[selfRegPrivs]' value='1'".($pSet['selfRegPrivs'] == 1 ? " checked='checked'" : '')."> {$ax['view']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[selfRegPrivs]' value='2'".($pSet['selfRegPrivs'] == 2 ? " checked='checked'" : '')."> {$ax['post_own']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[selfRegPrivs]' value='3'".($pSet['selfRegPrivs'] == 3 ? " checked='checked'" : '')."> {$ax['post_all']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['selfRegNot_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['selfRegNot_label']}:</td>
	<td><input type='radio' name='pSet[selfRegNot]' value='0'".($pSet['selfRegNot'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[selfRegNot]' value='1'".($pSet['selfRegNot'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['cookieExp_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['cookieExp_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[cookieExp]' maxlength='3' size='2' value='{$pSet['cookieExp']}'> (1 - 365)</td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_view_settings']}&nbsp;</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['yearStart_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['yearStart_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[yearStart]' maxlength='2' size='2' value='{$pSet['yearStart']}'> (1 - 12 {$ax['or']} 0)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['colsToShow_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['colsToShow_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[colsToShow]' maxlength='1' size='2' value='{$pSet['colsToShow']}'> (1 - 6)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['rowsToShow_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['rowsToShow_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[rowsToShow]' maxlength='2' size='2' value='{$pSet['rowsToShow']}'> (1 - 10)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['weeksToShow_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['weeksToShow_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[weeksToShow]' maxlength='2' size='2' value='{$pSet['weeksToShow']}'> (2 - 20 {$ax['or']} 0 {$ax['or']} 1)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['workWeekDays_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['workWeekDays_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[workWeekDays]' maxlength='7' size='5' value='{$pSet['workWeekDays']}'> (1: {$wkDays_l[1]}, 2: {$wkDays_l[2]} .... 7: {$wkDays_l[7]})</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['lookaheadDays_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['lookaheadDays_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[lookaheadDays]' maxlength='3' size='2' value='{$pSet['lookaheadDays']}'> (1 - 365)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['dwStartHour_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['dwStartHour_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[dwStartHour]' maxlength='2' size='2' value='{$pSet['dwStartHour']}'> (0 - 18)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['dwEndHour_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['dwEndHour_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[dwEndHour]' maxlength='2' size='2' value='{$pSet['dwEndHour']}'> (6 - 24)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['dwTimeSlot_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['dwTimeSlot_label']}:</td>
	<td><select name='pSet[dwTimeSlot]'>
	<option value='10'".($pSet['dwTimeSlot'] == "10" ? " selected='selected'" : '').">10</option>
	<option value='15'".($pSet['dwTimeSlot'] == "15" ? " selected='selected'" : '').">15</option>
	<option value='20'".($pSet['dwTimeSlot'] == "20" ? " selected='selected'" : '').">20</option>
	<option value='30'".($pSet['dwTimeSlot'] == "30" ? " selected='selected'" : '').">30</option>
	<option value='60'".($pSet['dwTimeSlot'] == "60" ? " selected='selected'" : '').">60</option>
	</select> {$ax['minutes']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['dwTsHeight_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['dwTsHeight_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[dwTsHeight]' maxlength='2' size='2' value='{$pSet['dwTsHeight']}'> {$ax['pixels']} (10 - 60)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['eventHBox_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['eventHBox_label']}:</td>
	<td><input type='radio' name='pSet[eventHBox]' value='0'".($pSet['eventHBox'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[eventHBox]' value='1'".($pSet['eventHBox'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['showAdEd_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['showAdEd_label']}:</td>
	<td><input type='radio' name='pSet[showAdEd]' value='0'".($pSet['showAdEd'] == 0 ? " checked='checked'" : '')."> {$ax['no']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[showAdEd]' value='1'".($pSet['showAdEd'] == 1 ? " checked='checked'" : '')."> {$ax['yes']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['showCatName_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['showCatName_label']}:</td>
	<td><input type='radio' name='pSet[showCatName]' value='0'".($pSet['showCatName'] == 0 ? " checked='checked'" : '')."> {$ax['no']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[showCatName]' value='1'".($pSet['showCatName'] == 1 ? " checked='checked'" : '')."> {$ax['yes']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['showLinkInMV_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['showLinkInMV_label']}:</td>
	<td><input type='radio' name='pSet[showLinkInMV]' value='0'".($pSet['showLinkInMV'] == 0 ? " checked='checked'" : '')."> {$ax['no']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[showLinkInMV]' value='1'".($pSet['showLinkInMV'] == 1 ? " checked='checked'" : '')."> {$ax['yes']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['monthInDCell_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['monthInDCell_label']}:</td>
	<td><input type='radio' name='pSet[monthInDCell]' value='0'".($pSet['monthInDCell'] == 0 ? " checked='checked'" : '')."> {$ax['no']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[monthInDCell]' value='1'".($pSet['monthInDCell'] == 1 ? " checked='checked'" : '')."> {$ax['yes']}</td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_dt_settings']}&nbsp;</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['dateFormat_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['dateFormat_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[dateFormat]' size='4' value='{$pSet['dateFormat']}'> ({$ax['dateFormat_expl']})</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['MdFormat_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['MdFormat_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[MdFormat]' size='4' value='{$pSet['MdFormat']}'> ({$ax['MdFormat_expl']})</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['MdyFormat_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['MdyFormat_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[MdyFormat]' size='4' value='{$pSet['MdyFormat']}'> ({$ax['MdyFormat_expl']})</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['MyFormat_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['MyFormat_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[MyFormat]' size='4' value='{$pSet['MyFormat']}'> ({$ax['MyFormat_expl']})</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['DMdFormat_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['DMdFormat_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[DMdFormat]' size='7' value='{$pSet['DMdFormat']}'> ({$ax['DMdFormat_expl']})</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['DMdyFormat_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['DMdyFormat_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[DMdyFormat]' size='7' value='{$pSet['DMdyFormat']}'> ({$ax['DMdyFormat_expl']})</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['timeFormat_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['timeFormat_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[timeFormat]' size='4' value='{$pSet['timeFormat']}'> ({$ax['timeFormat_expl']})</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['weekStart_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['weekStart_label']}:</td>
	<td><input type='radio' name='pSet[weekStart]' value='0'".($pSet['weekStart'] == 0 ? " checked='checked'" : '')."> {$ax['sunday']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[weekStart]' value='1'".($pSet['weekStart'] == 1 ? " checked='checked'" : '')."> {$ax['monday']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['weekNumber_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['weekNumber_label']}:</td>
	<td><input type='radio' name='pSet[weekNumber]' value='0'".($pSet['weekNumber'] == 0 ? " checked='checked'" : '')."> {$ax['no']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[weekNumber]' value='1'".($pSet['weekNumber'] == 1 ? " checked='checked'" : '')."> {$ax['yes']}</td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_email_settings']}&nbsp;</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['mailServer_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['mailServer_label']}:</td>
	<td><input type='radio' name='pSet[mailServer]' value='0'".($pSet['mailServer'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[mailServer]' value='1'".($pSet['mailServer'] == 1 ? " checked='checked'" : '')."> {$ax['php_mail']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[mailServer]' value='2'".($pSet['mailServer'] == 2 ? " checked='checked'" : '')."> {$ax['smtp_mail']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['smtpServer_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['smtpServer_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[smtpServer]' size='45' value=\"{$pSet['smtpServer']}\"></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['smtpPort_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['smtpPort_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[smtpPort]' maxlength='5' size='4' value=\"{$pSet['smtpPort']}\"></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['smtpSsl_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['smtpSsl_label']}:</td>
	<td><input type='radio' name='pSet[smtpSsl]' value='0'".($pSet['smtpSsl'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[smtpSsl]' value='1'".($pSet['smtpSsl'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['smtpAuth_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['smtpAuth_label']}:</td>
	<td><input type='radio' name='pSet[smtpAuth]' value='0'".($pSet['smtpAuth'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[smtpAuth]' value='1'".($pSet['smtpAuth'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['smtpUser_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['smtpUser_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[smtpUser]' size='45' value=\"{$pSet['smtpUser']}\"></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['smtpPass_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['smtpPass_label']}:</td>
	<td><input type='password'{$errors[$i++]} name='pSet[smtpPass]' size='45' value=\"{$pSet['smtpPass']}\"></td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_perfun_settings']}&nbsp;</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['cronSummary_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['cronSummary_label']}:</td>
	<td><input type='radio' name='pSet[adminCronSum]' value='0'".($pSet['adminCronSum'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[adminCronSum]' value='1'".($pSet['adminCronSum'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['chgEmailList_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['chgEmailList_label']}:</td>
	<td><input type='text' name='pSet[chgEmailList]' size='45' value=\"{$pSet['chgEmailList']}\"></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['chgNofDays_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['chgNofDays_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[chgNofDays]' maxlength='2' size='2' value='{$pSet['chgNofDays']}'> (0 - 30)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['icsExport_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['icsExport_label']}:</td>
	<td><input type='radio' name='pSet[icsExport]' value='0'".($pSet['icsExport'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[icsExport]' value='1'".($pSet['icsExport'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['eventExp_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['eventExp_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[eventExp]' maxlength='3' size='2' value='{$pSet['eventExp']}'> (1 - 999 {$ax['or']} 0)</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['maxNoLogin_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['maxNoLogin_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[maxNoLogin]' maxlength='3' size='2' value='{$pSet['maxNoLogin']}'> (0 - 365)</td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_minical_settings']}&nbsp;</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['miniCalView_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['miniCalView_label']}:</td>
	<td><select name='pSet[miniCalView]'>
	<option value='1'".($pSet['miniCalView'] == '1' ? " selected='selected'" : '').">{$xx['hdr_month_full']}</option>
	<option value='2'".($pSet['miniCalView'] == '2' ? " selected='selected'" : '').">{$xx['hdr_month_work']}</option>
	</select></td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['miniCalPost_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['miniCalPost_label']}:</td>
	<td><input type='radio' name='pSet[miniCalPost]' value='0'".($pSet['miniCalPost'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[miniCalPost]' value='1'".($pSet['miniCalPost'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['miniCalHBox_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['miniCalHBox_label']}:</td>
	<td><input type='radio' name='pSet[miniCalHBox]' value='0'".($pSet['miniCalHBox'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[miniCalHBox]' value='1'".($pSet['miniCalHBox'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['mCalUrlFull_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['mCalUrlFull_label']}:</td>
	<td><input type='text' name='pSet[mCalUrlFull]' size='45' value='{$pSet['mCalUrlFull']}'></td></tr>\n";
?>
</table></td></tr>
<tr><td><table class='fieldBoxFix'>
<?php
echo "<tr><td class='legend' colspan='2'>&nbsp;{$ax['set_sidebar_settings']}&nbsp;</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['sideBarHBox_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['sideBarHBox_label']}:</td>
	<td><input type='radio' name='pSet[sideBarHBox]' value='0'".($pSet['sideBarHBox'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[sideBarHBox]' value='1'".($pSet['sideBarHBox'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['showLinkInSB_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['showLinkInSB_label']}:</td>
	<td><input type='radio' name='pSet[showLinkInSB]' value='0'".($pSet['showLinkInSB'] == 0 ? " checked='checked'" : '')."> {$ax['disabled']}&nbsp;&nbsp;&nbsp;
	<input type='radio' name='pSet[showLinkInSB]' value='1'".($pSet['showLinkInSB'] == 1 ? " checked='checked'" : '')."> {$ax['enabled']}</td></tr>

	<tr><td class='labelFix' onmouseover=\"pop('".htmlspecialchars($ax['sideBarDays_text'])."', 'normal')\" onmouseout=\"pop()\">{$ax['sideBarDays_label']}:</td>
	<td><input type='text'{$errors[$i++]} name='pSet[sideBarDays]' maxlength='3' size='2' value='{$pSet['sideBarDays']}'> (1 - 365)</td></tr>\n";
?>
</table></td></tr>
</table>
</div>
</div>
<input class='button saveSettings noPrint' type='submit' name='save' value="<?php echo $ax['set_save_settings']; ?>">
</form>
