<?php
/*
= check calendar for email reminders to be send =

© Copyright 2009-2013  Issam - www.Issam.eu

-------------------------------------------------------------------
 This script depends on and is executed via the file lcalcron.php.
 See lcalcron.php header for details.
-------------------------------------------------------------------

This file is part of the LuxCal Web Calendar.

 --------- THIS SCRIPT USES THE FOLLOWING CALENDAR SETTINGS --------
calendarTitle: The calendar title is part of the reminder email
calendarUrl: A calendar hyperlink is added to the reminder email
eventColor: Specifies event colors (0:user color, 1:cat color)
showAdEd: Show added/edited details (0:no, 1:yes)
 -------------------------------------------------------------------
*/

//
//Send email notification
//
function notify(&$evt,$date,$daysDue) {
	global $emlStyle, $set, $xx, $ax;

	$todayD = date("Y-m-d", time()+43200); //today 12:00
	
	//compose email message
	$dateTime = IDtoDD($date).' '.$xx['at_time'].' '.(($evt['sti'] or $evt['eti']) ? ITtoDT($evt['sti']) : $ax['cro_all_day']);
	if ($evt['eti']) { $dateTime .= " - ".ITtoDT($evt['eti']); }
	$status = '';
	if ($evt['cbx']) { $status .= $evt['clb'].': '.(strpos($evt['chd'], $date) ? $evt['cmk'] : '- -'); }
	$subject = $set['calendarTitle']." - {$ax['cro_due_in']} {$daysDue} {$ax['cro_days']}: ".$evt['tit'];
	if ($set['eventColor']) {
		$eStyle = ($evt['cco'] ? "color:{$evt['cco']};" : '').($evt['cbg'] ? "background-color:{$evt['cbg']};" : '');
	} else {
		$eStyle = $evt['uco'] ? "background-color:{$evt['uco']};" : '';
	}
	$eStyle = $eStyle ? " style=\"{$eStyle}\"" : '';
	if ($set['showAdEd']) {
		$adEdData = "<tr><td>{$xx['vws_added']}:</td><td>".IDtoDD($evt['ada'])." ({$evt['una']})</td></tr>\n";
		if ($evt['mda'] and $evt['edr']) { $adEdData .= "<tr><td>{$xx['vws_edited']}:</td><td>".IDtoDD($evt['mda'])." ({$evt['edr']})</td></tr>\n"; }
	} else {
		$adEdData = '';
	}
	$calUrl = $set['calendarUrl'].(strpos($set['calendarUrl'],'?',6) ? '&' : '?').'cD='.$date;
	$msgText = "
<html>
<head>\n<title>{$set['calendarTitle']} {$ax['cro_mailer']}</title>
<style type='text/css'>
body, p, table {{$emlStyle}}
td {vertical-align:top;}
</style>
</head>
<body>
<p>{$set['calendarTitle']} {$ax['cro_mailer']} ".IDtoDD($todayD)."</p>
<p>{$ax['cro_event_due_in']} {$daysDue} {$ax['cro_days']}:</p>
<table>
	<tr><td>{$ax['cro_title']}:</td><td><b><span{$eStyle}>{$evt['tit']}</span></b></td></tr>
	".($evt['cbx'] ? "<tr><td>{$ax['cro_status']}:</td><td>{$status}</td></tr>" : '')."
	<tr><td>{$ax['cro_category']}:</td><td>{$evt['cnm']}</td></tr>
	<tr><td>{$ax['cro_date_time']}:</td><td>{$dateTime}</td></tr>
	<tr><td>{$ax['cro_venue']}:</td><td>".(($evt['ven']) ? $evt['ven'] : "- -")."</td></tr>
	<tr><td>{$ax['cro_description']}:</td><td>".(($evt['des']) ? $evt['des'] : "- -")."</td></tr>
{$adEdData}
</table>
<p><a href='{$calUrl}'>{$ax['cro_open_calendar']}</a></p>
</body>
</html>
";
	//send notification
	$sender = $set['notifSender'] ? $evt['uid'] : 0;
	$sent = sendMail($subject, $msgText, $evt['rml'], $sender);
	$sentTo = $sent ? "{$sent} - {$ax['cro_subject']}: {$subject}" : "Sending mail failed. See logs/luxcal.log for details";
	return $sentTo;
}

//
//main function
//
function cronNotify() {
	global $evtList;
	//initialize
	$todayT = time()+43200; //today 12:00
	$todayD00 = date("Y-m-d", $todayT); //today
	$todayD30 = date("Y-m-d", $todayT + 2592000); //today + 30 days
	$sentTo = '';

	//set filter
	$filter = 'notify >= 0';

	//set session params
	$_SESSION['uid'] = 0; //all users

	//retrieve and process events
	retrieve($todayD00,$todayD30,'',$filter);

	if ($evtList) {
		foreach($evtList as $date => &$events) {
			$daysDue = round((mktime(12,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4)) - $todayT) / 86400);
			foreach ($events as $evt) {
				if (($daysDue == $evt['rem'] or $date == $todayD00) and $evt['rml']) { //due and mail adresses to notify
					$sentTo .= notify($evt,$date,$daysDue); //send reminder email
				}
			}
		}
	}
	return $sentTo;
}
?>
