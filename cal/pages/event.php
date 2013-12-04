<?php
/*
= LuxCal add/edit event page =

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
?>

<script>
function hide_times(t) {
	if (t.checked) {
		$I("dTimeS").style.visibility = "hidden";
		$I("dTimeE").style.visibility = "hidden";
	} else {
		$I("dTimeS").style.visibility = "visible";
		$I("dTimeE").style.visibility = "visible";
		t.form.sti.value = t.form.eti.value = "";
	}
}
</script>

<?php
function catMenu($selCat) {
	$where = ' WHERE status >= 0'.($_SESSION['uid'] == 1 ? " AND public > 0" : "");
	$rSet = dbQuery("SELECT category_id, name, color, background FROM [db]categories{$where} ORDER BY sequence");
	if ($rSet !== false) {
		while ($row=mysql_fetch_assoc($rSet)) {
			$selected = ($selCat == $row['category_id']) ? " selected='selected'" : '';
			$catColor = ($row['color'] ? "color:{$row['color']};" : '').($row['background'] ? "background-color:{$row['background']};" : '');
			echo "<option value='{$row['category_id']}'".($catColor ? " style='{$catColor}'" : '').$selected.">".stripslashes($row['name'])."</option>\n";
		}
	}
}

function employeMenu($selEmployee) {
	$rSet = dbQuery("SELECT EMPLOYEID, EMPLOYENOM FROM [db]employe ORDER BY EMPLOYENOM");
	if ($rSet !== false) {
		while ($row=mysql_fetch_assoc($rSet)) {
			$selected = ($selEmployee == $row['EMPLOYEID']) ? " selected='selected'" : '';
			echo "<option value='{$row['EMPLOYEID']}'" .$selected.">".stripslashes($row['EMPLOYENOM'])."</option>\n";
		}
	}
}

function circuitMenu($selCircuit) {
	$rSet = dbQuery("SELECT CIRCUITID, CIRCUITNOM FROM [db]circuit ORDER BY CIRCUITNOM");
	if ($rSet !== false) {
		while ($row=mysql_fetch_assoc($rSet)) {
			$selected = ($selCircuit == $row['CIRCUITID']) ? " selected='selected'" : '';
			echo "<option value='{$row['CIRCUITID']}'" .$selected.">".stripslashes($row['CIRCUITNOM'])."</option>\n";
		}
	}
}


function userMenu($selUser) {
	global $userEml;
	$rSet = dbQuery("SELECT user_id, user_name FROM [db]users WHERE status >= 0 ORDER BY user_name");
	if ($rSet !== false) {
		while ($row=mysql_fetch_assoc($rSet)) {
			$selected = ($selUser == $row['user_id']) ? " selected='selected'" : '';
			echo "<option value='{$row['user_id']}'{$selected}>".stripslashes($row['user_name'])."</option>\n";
		}
	}
}

function notifyNow($what) { //notify added/edited/deleted event
	global $xx, $set, $nml, $apd, $app, $tit, $cid, $sda, $ven, $desX, $sda, $eda, $ada, $mda, $sti, $eti, $r_t, $ald, $chd, $edr, $uid, $own, $repTxt;
	
	$emlStyle = "background:#FFFFDD; color:#000099; font:12px arial, sans-serif;"; //email body style definition
	
	//get category
	$rSet = dbQuery("SELECT name,color,background,chbox,chlabel,chmark FROM [db]categories WHERE category_id = $cid");
	$row = mysql_fetch_assoc($rSet);
	
	//compose email message
	$noteText = $what == 'add_exe' ? $xx['evt_event_added'] : ($what == 'upd_exe' ? $xx['evt_event_edited'] : $xx['evt_event_deleted']);
	$dateTime = $sda;
	if ($sti) $dateTime .= ' '.$xx['at_time'].' '.$sti;
	if ($eda or $eti) $dateTime .= ' -';
	if ($eda) $dateTime .= ' '.$eda;
	if ($eda and $eti) $dateTime .= ' '.$xx['at_time'];
	if ($eti) $dateTime .= ' '.$eti;
	$dateTime .= ($ald == 'all' ? ' '.$xx['evt_all_day'] : '').($r_t ? ' ('.$repTxt.')' : '');
	$evD = DDtoID($sda);
	$status = '';
	if (!$eda and !$r_t) { //no multi-day and not repeating
		if ($row['chbox']) { $status .= $row['chlabel'].': '.(strpos($chd, $evD) ? $row['chmark'] : '- -'); }
	}
	$subject = 'ssss'." - ".$noteText.": ".'ssss';
	$catColor = ($row['color'] ? "color:".$row['color'].";" : "").($row['background'] ? "background-color:".$row['background'].";" : "");
	$style = $catColor ? " style=\"".$catColor."\"" : "";
	$eBoxStyle = ' style="padding-left:5px;'.(($app and !$apd) ? ' border-left:2px solid #ff0000;' : '').'"';
	if ($set['showAdEd']) {
		$adEdData = '<tr><td>'.$xx['vws_added'].':</td><td>'.IDtoDD($ada).' ('.$own.')'."</td></tr>\n";
		if ($mda and $edr) { $adEdData .= '<tr><td>'.$xx['vws_edited'].':</td><td>'.IDtoDD($mda).' ('.$edr.')'."</td></tr>\n"; }
	} else {
		$adEdData = '';
	}
	$calUrl = $set['calendarUrl'].(strpos($set['calendarUrl'],'?',6) ? '&' : '?').'cD='.$evD;
	$msgText = "
<html>
<head>\n<title>{$set['calendarTitle']} {$xx['evt_mailer']}</title>
<style type='text/css'>
body, p, table {{$emlStyle}}
td {vertical-align:top;}
</style>
</head>
<body>
<p>{$set['calendarTitle']} {$xx['evt_mailer']} ".IDtoDD(date("Y-m-d"))."</p>
<p>{$noteText}:</p>
<table{$eBoxStyle}>
	<tr><td>WWWWWWWW:</td><td><b><span{$style}>eeeeee</span></b></td></tr>
	".($status ? "<tr><td>{$xx['evt_status']}:</td><td>{$status}</td></tr>" : '')."
	<tr><td>{$xx['evt_category']}:</td><td>".stripslashes($row['name'])."</td></tr>
	<tr><td>{$xx['evt_date_time']}:</td><td>{$dateTime}</td></tr>
	<tr><td>{$xx['evt_venue']}:</td><td>".($ven ? $ven : "- -")."</td></tr>
	<tr><td>{$xx['evt_description']}:</td><td>".($desX ? $desX : "- -")."</td></tr>
{$adEdData}
</table>
<p><a href=\"{$calUrl}\">{$xx['evt_open_calendar']}</a></p>
</body>
</html>
";
	//send notifications
	if ($nml) { //email address(es) to notify
		$sender = $set['notifSender'] ? $uid : 0;
		sendMail($subject, $msgText, $nml, $sender);
	}
}

//get input params
$eid = isset($_REQUEST['eid']) ? $_REQUEST['eid'] : 0;
$evD = isset($_REQUEST['evD']) ? $_REQUEST['evD'] : '';
$evTs = isset($_REQUEST['evTs']) ? $_REQUEST['evTs'] : '';
$evTe = isset($_REQUEST['evTe']) ? $_REQUEST['evTe'] : '';

//sanity check
if (!defined('LCC') or
		(isset($eid) and !preg_match('%^\d{1,8}$%', $eid)) or
		(!empty($evD) and !preg_match('%^\d{2,4}-\d{2}-\d{2,4}$%', $evD)) or
		(!empty($evTs) and !preg_match('%^\d{2}:\d{2}$%', $evTs)) or
		(!empty($evTe) and !preg_match('%^\d{2}:\d{2}$%', $evTe)) or
		(isset($_GET['mode']) and !preg_match('%^(add|edit)$%', $_GET['mode']))
	) { exit('not permitted ('.substr(basename(__FILE__),0,-4).')'); }

//check/update mode
$refresh = $close = false;
if (isset($_POST['refresh'])) {
	$refresh = true;
} else {
	if     (isset($_POST['add_exe_cls'])) { $mode = 'add_exe'; $close = true; }
	elseif (isset($_POST['add_exe'])) { $mode = 'add_exe'; }
	elseif (isset($_POST['upd_exe_cls'])) { $mode = 'upd_exe'; $close = true; }
	elseif (isset($_POST['upd_exe'])) { $mode = 'upd_exe'; }
	elseif (isset($_POST['del_exe_cls'])) { $mode = 'del_exe'; $close = true; }
	elseif (isset($_POST['del_exe'])) { $mode = 'del_exe'; }
}

$editN = (isset($_POST['editN'])) ? $_POST['editN'] : 0;
if (isset($_POST['edit_nx'])) { $editN = 2; } //edit series
if (isset($_POST['edit_1x'])) { $editN = 1; } //edit occurence

$eMsg = $wMsg = $cMsg = NULL;

//init event data
if ($mode == 'edit' and !$refresh) { //show/edit event
	$rSet = dbQuery("
		SELECT e.*,c.name,c.approve,c.color,c.background,u.user_name
		FROM [db]events e
		INNER JOIN [db]categories c ON c.category_id = e.category_id
		INNER JOIN [db]users u ON u.user_id = e.user_id
		WHERE e.event_id = $eid");
		
		
		

	
	
	
	$row = mysql_fetch_assoc($rSet);
	$ada = $row['a_date'];
	$mda = $row['m_date'][0] != '9' ? $row['m_date'] : "";
	$edr = stripslashes($row['editor']);
	$tit = stripslashes($row['title']);
	
	
	$emp = stripslashes($row['employe']);// Issam
	
	
	
	$ven = stripslashes($row['venue']);
	
	
	


	
	
	
	$desHtml = stripslashes($row['description']);
	$des = preg_replace_callback($rxULink,create_function('$m','return $m[1].($m[2]!=$m[3]?" [".$m[3]."]":"");'),$desHtml); //decode URL tags
	$des = preg_replace($rxIMGTags,"$1",$des); //decode IMG tags
	$des = str_replace(array("<br>", "<br />"), "\r\n", $des); //replace <br> by crlf
	$cid = $row['category_id'];
	$eleve_id = $row['eleve_id'];
	$emp_id = $row['employee_id'];
	$circuit_id = $row['circuit_id'];
	$uid = $row['user_id'];
	$nml = ($row['not_mail']) ? $row["not_mail"] : $umail;
	$apd = $row['approved'];
	$pri = $row['private'];
	if ($editN == 1) {
		$sda = IDtoDD($evD);
		$eda = "";
		$r_t = 0;
	} else {
		$sda = IDtoDD($row['s_date']);
		$eda = ($row['e_date'][0] != "9") ? IDtoDD($row['e_date']) : "";
		$r_t = $row['r_type'];
	}
	$xda = $row['x_dates'];
	$sti = ITtoDT(substr($row['s_time'],0,5));
	$eti = ($row['e_time'][0] != "9") ? ITtoDT(substr($row['e_time'],0,5)) : "";
	$ri1 = $ri2 = $rp1 = $rp2 = 0;
	if ($r_t == 1) {
		$ri1 = $row['r_interval'];
		$rp1 = $row['r_period'];
	} elseif ($r_t == 2) {
		$ri2 = $row['r_interval'];
		$rp2 = $row['r_period'];
	}
	$rpm = $row['r_month'];
	$rul = ($row['r_until'][0] != "9") ? IDtoDD($row['r_until']) : '';
	$chd = $row['checked'];
	$not = $row['notify'];
	$cnm = stripslashes($row['name']);
	$app = $row['approve'];
	$col = $row['color'];
	$bco = $row['background'];
	$own = stripslashes($row['user_name']);
} else { //add
	$uid = isset($_POST['uid']) ? $_POST['uid'] : (isset($_POST['oUid']) ? $_POST['oUid'] : $_SESSION['uid']);
	$tit = isset($_POST['tit']) ? strip_tags(stripslashes($_POST['tit']),'<b><i><u><s><center>') : '';
	
	$ven = isset($_POST['ven']) ? strip_tags(stripslashes($_POST['ven']),'<b><i><u><s><center>') : '';
	
	$emp = isset($_POST['emp']) ? strip_tags(stripslashes($_POST['emp']),'<b><i><u><s><center>') : '';
	
	$des = isset($_POST['des']) ? strip_tags(stripslashes($_POST['des']),'<a><b><i><u><s><img>') : '';
	$cid = isset($_POST['cid']) ? $_POST['cid'] : 0;
	$emp_id = isset($_POST['employee_id']) ? $_POST['employee_id'] : 0;
	$eleve_id = isset($_POST['eleve_id']) ? $_POST['eleve_id'] : 0;
	$circuit_id = isset($_POST['circuit_id']) ? $_POST['circuit_id'] : 0;
	//die($eleve_id );
	$cid = isset($_POST['cid']) ? $_POST['cid'] : 0;
	$cid = isset($_POST['cid']) ? $_POST['cid'] : 0;
	$nml = isset($_POST['nml']) ? $_POST['nml'] : $umail;
	$apd = isset($_POST['apd']) ? ($_POST['apd'] == 'yes' ? 1 : 0) : 0;
	$pri = isset($_POST['pri']) ? ($_POST['pri'] == 'yes' ? 1 : 0) : ($set['privEvents'] >= 2 ? 1 : 0);
	$sda = isset($_POST['sda']) ? $_POST['sda'] : '';
	$eda = isset($_POST['eda']) ? $_POST['eda'] : '';
	$xda = isset($_POST['xda']) ? $_POST['xda'] : '';
	$sti = isset($_POST['sti']) ? $_POST['sti'] : '';
	$eti = isset($_POST['eti']) ? $_POST['eti'] : '';
	$r_t = isset($_POST['r_t']) ? $_POST['r_t'] : 0;
	$ri1 = isset($_POST['ri1']) ? $_POST['ri1'] : 0;
	$rp1 = isset($_POST['rp1']) ? $_POST['rp1'] : 0;
	$ri2 = isset($_POST['ri2']) ? $_POST['ri2'] : 0;
	$rp2 = isset($_POST['rp2']) ? $_POST['rp2'] : 0;
	$rpm = isset($_POST['rpm']) ? $_POST['rpm'] : 0;
	$rul = isset($_POST['rul']) ? $_POST['rul'] : '';
	$not = isset($_POST['not']) ? $_POST['not'] : -1;
	$ada = isset($_POST['ada']) ? $_POST['ada'] : '';
	$mda = isset($_POST['mda']) ? $_POST['mda'] : '';
	$edr = isset($_POST['edr']) ? $_POST['edr'] : '';
	$chd = isset($_POST['chd']) ? $_POST['chd'] : '';
	$own = isset($_POST['own']) ? $_POST['own'] : $uname;
	
	
	
	
	
	
	$rSet = dbQuery("SELECT approve FROM [db]categories WHERE category_id = $cid LIMIT 1");
	if ($rSet !== false) {
		$row = mysql_fetch_assoc($rSet);
		$app = stripslashes($row['approve']);
	} else {
		$app = 0;
	}
}






	
	
	
$non = isset($_POST['non']) ? ($_POST['non'] == 'yes' ? 1 : 0) : 0; //notify now
$oUid = isset($_POST['oUid']) ? $_POST['oUid'] : $uid; //remember original user ID
$todayD = date("Y-m-d");

//check rights
$mayEdit = ($privs > 2 or ($privs == 2 and $uid == $_SESSION['uid'])) ? true : false; //edit rights
$mayView = (!$pri or $privs > 2 or $uid == $_SESSION['uid']) ? true : false; //view rights

//make repeat text
switch ($r_t) {
	case 0: $repTxt = $xx['evt_no_repeat']; break;
	case 1: $repTxt = $xx['evt_repeat'].' '.$xx['evt_interval1_'.$ri1].' '.$xx['evt_period1_'.$rp1]; break;
	case 2: $repTxt = $xx['evt_repeat_on'].' '.$xx['evt_interval2_'.$ri2].' '.$wkDays[$rp2].' '.$xx['of'].' '.($rpm ? $months[$rpm-1] : $xx['evt_each_month']);
}
if ($r_t > 0 and $rul) {
	$repTxt .= ' '.$xx['evt_until'].' '.$rul;
}

if ($mode == "add" and !$refresh) { //add event - preset date/times if available
	if (isset($evD)) { $sda = IDtoDD($evD); }
	if (isset($evTs)) { $sti = ITtoDT($evTs); }
	if (isset($evTe)) { $eti = ITtoDT($evTe); }
}

//all day event?
$ald = isset($_POST['ald']) ? $_POST['ald'] : '';
if (DTtoIT($sti) == "00:00" and DTtoIT($eti) == "23:59") { $ald = 'all'; }
if ($ald == 'all') { $sti = $eti = ''; }

if ($mayEdit) { //last minute rights check
	//add/update event
	if ($mode == "add_exe" or $mode == "upd_exe") {
	//validate input fields
		$desX = preg_replace_callback($rxURL,
		create_function('$m','return " <a class=\'link\' href=\'".($m[1]?$m[1]:"http://").$m[2]."\' target=\'_blank\'>".($m[3]?$m[3]:$m[2])."</a>";'),$des); //create HTML links
		$desX = preg_replace_callback($rxIMG,
		create_function('$m','return " <img src=\'".(!empty($m[2]) ? $m[2] : \'thumbnails/\')."{$m[3]}\' alt=\'{$m[3]}\'>";'),$desX); //create image links
		$desX = str_replace(array("\r\n", "\n", "\r"), "<br>", $desX); //replace newline by <br>
		do {
			if (!$eleve_id) { $eMsg .= $xx['evt_no_title']."<br>"; }
			if ($sda) {
				$sdate = DDtoID($sda);
				if (!$sdate) { $eMsg .= $xx['evt_bad_date'].": ".$sda."<br>"; break; }
			} else { $eMsg .= $xx['evt_no_start_date']."<br>"; break; }
			if ($eda and $eda != $sda) {
				$edate = DDtoID($eda);
				if (!$edate) {
					$eMsg .= $xx['evt_bad_date'].": ".$eda."<br>"; break;
				} elseif ($edate < $sdate) {
					$eMsg .= $xx['evt_end_before_start_date']."<br>"; break;
				}
			} else { $eMsg .= $xx['evt_no_end_date']."<br>"; break; // validation for end date 
     //$edate = "9999-00-00";
	  }
			if ($ald == "all") {
				$stime = "00:00";
				$etime = "23:59";
			} else {
				if ($sti) {
					$stime = DTtoIT($sti);
					if (!$stime) { $eMsg .= $xx['evt_bad_time'].": ".$sti."<br>"; break; }
				} else { $eMsg .= $xx['evt_no_start_time']."<br>"; break; }
				if ($eti) {
					$etime = DTtoIT($eti);
					if (!$etime) {
						$eMsg .= $xx['evt_bad_time'].": ".$eti."<br>"; break;
					} elseif (($edate[0] == '9' or $edate == $sdate) and $etime < $stime) {
						$eMsg .= $xx['evt_end_before_start_time']."<br>"; break;
					}
					if ($stime == $etime and $edate[0] == '9') { $etime = '99:00'; }
				} else {
					$etime = ($edate[0] != '9') ? '23:59' : '99:00';
				}
			}
			if ($r_t > 0 and $rul) {
				$runtil = DDtoID($rul);
				if (!$runtil) {
					$eMsg .= $xx['evt_bad_rdate'].": ".$rul."<br>";
				} elseif ($runtil < $sdate) {
					$eMsg .= $xx['evt_until_before_start_date']."<br>";
				}
			} else {
				$runtil = "9999-00-00";
			}
			if ($not == '-' or $not == '' or !$set['mailServer']) {
				$not = -1;
			} elseif (!ctype_digit($not)) {
				$eMsg .= $xx['evt_not_days_invalid']."<br>";
			} elseif ($not >= 0 and $sdate > $todayD and $sdate <= date("Y-m-d",time() + 86400 * $not)) {
				$wMsg .= $xx['evt_not_in_past']."<br>";
			}
			if ((($not >= 0 and $sdate > $todayD) or $non) and strlen($nml) < 5) {
				$eMsg .= $xx['evt_eml_list_missing']."<br>";
			}
			if (strlen($nml) > 255) { $eMsg .= $xx['evt_eml_list_too_long']."<br>"; }
		} while (false);
	}

	//Notify now
	if ($non and !$eMsg  and !$refresh) { notifyNow($mode); }

	//update database
	if (($mode == "add_exe" or $mode == "upd_exe") and !$eMsg) { //no errors
		$titEsc = mysql_real_escape_string($tit);
		$venEsc = mysql_real_escape_string($ven);
		$desEsc = mysql_real_escape_string($desX);

		//if owner changed, default not_mail = owner email
		if ($uid != $oUid) {
			$rSet = dbQuery("SELECT email FROM [db]users WHERE user_id = $uid");
			if ($rSet !== false) {
				$row=mysql_fetch_assoc($rSet);
				$nml = $row['email'];
			}
			$oUid = $uid; //set original user ID to current user
		}

		//set repeat params
		$r_i = $r_t == 1 ? $ri1 : ($r_t == 2 ? $ri2 : 0);
		$r_p = $r_t == 1 ? $rp1 : ($r_t == 2 ? $rp2 : 0);
		$r_m = $rpm;
	
		//update tables
		if ($mode == "add_exe") { //add new event
		
			$q = "INSERT INTO [db]events VALUES (NULL,DEFAULT,'$titEsc','$desEsc','$emp', '$cid','$venEsc',$uid,DEFAULT,$apd,$pri,DEFAULT,'$sdate','$edate',DEFAULT,'$stime','$etime',$r_t,$r_i,$r_p,$r_m,'$runtil',$not,'$nml','".$todayD."',DEFAULT,DEFAULT,$emp_id,$eleve_id,$circuit_id)";
			$result = dbQuery($q); //add to events table
			$eid = mysql_insert_id(); //set id to new event
			$cMsg .= $xx['evt_confirm_added'];
		} else { //update event
			$edr = $uname;
			$mda = $todayD;
			if ($editN != 1) { //update the series
				$q = "UPDATE [db]events
					SET title='$titEsc', venue='$venEsc', description='$desEsc', employe='$emp' , approved=$apd, private=$pri, category_id=$cid, user_id=$uid, not_mail='$nml', editor='$uname', s_date='$sdate', e_date='$edate', s_time='$stime', e_time='$etime', r_type=$r_t, r_interval=$r_i, r_period=$r_p, r_month=$r_m, r_until='$runtil', notify=$not, m_date='$todayD',employee_id = '$emp_id',eleve_id = '$eleve_id',circuit_id = 
					'$circuit_id'
					WHERE event_id=$eid";
									$result = dbQuery($q); //update events table
				$cMsg .= $xx['evt_confirm_saved'];
			} else { //update 1 occurrence
				$xda .= ';'.$evD;
				$result = dbQuery("UPDATE [db]events SET x_dates='$xda', editor='$uname', m_date='$todayD' WHERE event_id=$eid"); //exclude date from series
				$q = "INSERT INTO [db]events VALUES (NULL,DEFAULT,'$titEsc','$desEsc','$emp',$cid,'$venEsc',$uid,'".$uname."',$apd,$pri,DEFAULT,'$sdate','$edate',DEFAULT,'$stime','$etime',$r_t,$r_i,$r_p,$r_m,'$runtil',$not,'$nml','".$ada."','".$todayD."',DEFAULT)";
				$result = dbQuery($q); //add new event
				$eid = mysql_insert_id(); //set id to new event
				$editN = 0;
				$cMsg .= $xx['evt_confirm_added'];
			}
		}
		//refresh calendar and close event window 
		if ($close) {
			echo "\n<script>done(1,1);</script>\n";
		} else {
			echo "\n<script>done(0,1);</script>\n"; //don't close window
			$mode = "edit";
		}
	}

	//delete event
	if ($mode == "del_exe") {
		if ($editN != 1) { //delete series
			$result = dbQuery("UPDATE [db]events SET status = -1, m_date = '".$todayD."' WHERE event_id = $eid");
		} else { //delete occurence
			$xda .= ';'.$evD;
			$result = dbQuery("UPDATE [db]events SET x_dates='$xda' WHERE event_id = $eid"); //exclude date from series
			$editN = 0;
		}
		$cMsg = $xx['evt_confirm_deleted'];
		//refresh calendar and close event window 
		if ($close) {
			echo "\n<script>done(1,1);</script>\n";
		} else {
			echo "\n<script>done(0,1);</script>\n"; //don't close window
			$mode = "add";
		}
	}
}

if ($ald == "all") {
	$hidden = " style=\"visibility: hidden;\"";
	$chBoxed= " checked=\"checked\"";
} else {
	$hidden = $chBoxed = '';
}

if ($not == -1) { $not = ''; }

if ($eMsg) echo '<p class="error">'.$eMsg."</p>\n";
if ($wMsg) echo '<p class="warning">'.$wMsg."</p>\n";
if ($cMsg) echo '<p class="confirm">'.$cMsg."</p>\n";

if (strpos($mode,'exe') === false or $mayEdit) {
	if (($r_t > 0 or $sda < $eda) and $mayEdit and !$editN and !$refresh and !$eMsg and !$cMsg) {
		require './pages/eventform0.php'; //ask series or occurence
	} elseif ($mayEdit and !($app and $apd and $privs < 4)) {
		require './pages/eventform1.php';
	} elseif ($mayView) {
		require './pages/eventreport.php';
	} else {
		exit('no view rights ('.substr(basename(__FILE__),0,-4).')');
	}
} else {
	echo $xx['no_way'];
}
?>
