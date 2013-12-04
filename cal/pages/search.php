<?php
/*
= text search script =

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
$schText = (isset($_POST["schText"])) ? $_POST["schText"] : ""; //search text
$eF = isset($_POST['eF']) ? $_POST['eF'] : array(0); //field filter
if (isset($_POST['eF1'])) $eF[] = 1;
if (isset($_POST['eF2'])) $eF[] = 2;
if (isset($_POST['eF3'])) $eF[] = 3;
$catName = (isset($_POST["catName"])) ? $_POST["catName"] : ""; //category filter
$empName = (isset($_POST["empName"])) ? $_POST["empName"] : ""; //category filter
$circuitName = (isset($_POST["circuitName"])) ? $_POST["circuitName"] : ""; //category filter
$fromDda = (isset($_POST["fromDda"])) ? DDtoID($_POST["fromDda"]) : ""; //from event due date
$tillDda = (isset($_POST["tillDda"])) ? DDtoID($_POST["tillDda"]) : ""; //untill event due date


/* functions */

function catList($selCat) {
	global $xx;
	
	$where = ' WHERE status >= 0'.($_SESSION['uid'] == 1 ? " AND public > 0" : '');
	$rSet = dbQuery("SELECT name, color, background FROM [db]categories{$where} ORDER BY sequence");
	if ($rSet !== false) {
		echo "<option value='*'>{$xx['sch_all_cats']}&nbsp;</option>\n";
		while ($row = mysql_fetch_assoc($rSet)) {
			$row["name"] = stripslashes($row["name"]);
			$selected = ($selCat == $row["name"]) ? " selected='selected'" : '';
			$catColor = ($row['color'] ? "color:".$row['color'].";" : '').($row['background'] ? "background-color:".$row['background'].";" : '');
			echo "<option value=\"{$row["name"]}\"".($catColor ? " style='{$catColor}'" : '')."{$selected}>{$row["name"]}</option>\n";
		}
	}
}
function empList($selEmployee) {
	global $xx;
	$rSet = dbQuery("SELECT EMPLOYEID, EMPLOYENOM FROM [db]employe ORDER BY EMPLOYENOM");
	if ($rSet !== false) {
		echo "<option value='*'>{$xx['sch_all_emps']}&nbsp;</option>\n";
		while ($row=mysql_fetch_assoc($rSet)) {
			$selected = ($selEmployee == $row['EMPLOYEID']) ? " selected='selected'" : '';
			echo "<option value='{$row['EMPLOYEID']}'" .$selected.">".stripslashes($row['EMPLOYENOM'])."</option>\n";
		}
	}
}

function circuitList($selCircuit) {
	global $xx;
	$rSet = dbQuery("SELECT CIRCUITID, CIRCUITNOM FROM [db]circuit ORDER BY CIRCUITNOM");
	if ($rSet !== false) {
		echo "<option value='*'>{$xx['sch_all_circuits']}&nbsp;</option>\n";
		while ($row=mysql_fetch_assoc($rSet)) {
			$selected = ($selCircuit == $row['CIRCUITID']) ? " selected='selected'" : '';
			echo "<option value='{$row['CIRCUITID']}'" .$selected.">".stripslashes($row['CIRCUITNOM'])."</option>\n";
		}
	}
}




function searchForm() {
	global $xx, $set, $schText, $eF, $catName, $fromDda, $tillDda;
	
	echo "<form action='index.php' method='post'>
		<table class='fieldBox'>
		<tr><td class='legend' colspan='2'>&nbsp;{$xx['sch_define_search']}&nbsp;</td></tr>
		<tr>\n<td class='label'>{$xx['sch_search_text']}:</td>
		<td><input type='text' name='schText' id='schText' value=\"{$schText}\" maxlength='50' size='30'></td>\n</tr>
		<tr><td colspan='2'><hr></td></tr>
		<tr>\n<td class='label'>{$xx['sch_event_fields']}:</td>
		<td><input type='checkbox' id='eF0' name='eF[]' value='0' onclick=\"checkA(\'eF\',this);\"".(in_array(0, $eF) ? " checked='checked'" : '')."> 
		<label for='eF0'>{$xx['sch_all_fields']}</label></td>\n</tr>
		<tr>\n<td></td><td><input type='checkbox' id='eF1' name='eF[]' value='1' onclick=\"checkN(\'eF\',this);\"".(in_array(1, $eF) ? " checked='checked'" : '')."> 
		<label for='eF1'>{$xx['sch_title']}</label></td>\n</tr>
		<tr>\n<td></td><td><input type='checkbox' id='eF2' name='eF[]' value='2' onclick=\"checkN(\'eF\',this);\"".(in_array(2, $eF) ? " checked='checked'" : '')."> 
	<label for='eF2'>{$xx['sch_description']}</label></td>\n</tr>
	<tr>\n<td></td><td><input type='checkbox' id='eF3' name='eF[]' value='3' onclick=\"checkN(\'eF\',this);\"".(in_array(3, $eF) ? " checked='checked'" : '')."> 
	<label for='eF3'>{$xx['sch_venue']}</label></td>\n</tr>
	<tr><td class='label'>{$xx['sch_event_cat']}:</td><td><select name='catName'>\n";
	catList($catName);
	echo "</select></td></tr>";
	
	
	echo "<tr><td class='label'>{$xx['sch_event_emp']}:</td><td><select name='empName'>\n";
	empList($empName);
	echo "</select></td></tr>";
	
	echo "<tr><td class='label'>{$xx['sch_event_circuit']}:</td><td><select name='circuitName'>\n";
	circuitList($circuitName);
	echo "</select></td></tr>";
	
	
	echo 	"<tr>\n<td class='label'>{$xx['sch_occurring_between']}:</td><td>
		<input type='text' name='fromDda' id='fromDda' value='".IDtoDD($fromDda)."' size='8'>
		<button title=\"{$xx['sch_select_start_date']}\" onclick=\"dPicker(1,'nill','fromDda');return false;\">&larr;</button> &#8211; 
		<input type='text' name='tillDda' id='tillDda' value='".IDtoDD($tillDda)."' size='8'>
		<button title=\"{$xx['sch_select_end_date']}\" onclick=\"dPicker(1,'nill','tillDda');return false;\">&larr;</button></td>\n</tr>
		</table>
		<input type='submit' name='search' value=\"{$xx['sch_search']}\">
		<input type='button' onclick=\"window.location.href='index.php?cP={$set['defaultView']}'\" value=\"{$xx['sch_calendar']}\">
		</form>
		<div style='clear:right'></div>
		<script>$I(\"schText\").focus();</script>";
}

function validateForm() {
	global $xx, $schText, $fromDda, $tillDda;
	
	$schText = trim(str_replace('%', '', $schText),'&');
	if (strlen(str_replace('_', '', $schText)) < 2) { return $xx['sch_invalid_search_text']; }
	if ($fromDda === false) { return $xx['sch_bad_start_date']; }
	if ($tillDda === false) { return $xx['sch_bad_end_date']; }
	return '';
}
	
function searchText() {
	global $xx, $set, $schText, $eF, $catName,$empName,$circuitName, $fromDda, $tillDda;
	
	//set event filter
	$schTextEsc = '%'.mysql_real_escape_string($schText).'%';
	$schTextEsc = str_replace('&', '%', $schTextEsc);
	$filter = '';
	if ($catName != '*') { $filter .= " AND c.name = '".mysql_real_escape_string($catName)."'"; }
	
	if ($empName != '*') { $filter .= " AND e.employee_id = '".mysql_real_escape_string($empName)."'"; }
	if ($circuitName != '*') { $filter .= " AND e.circuit_id = '".mysql_real_escape_string($circuitName)."'"; }
	
	$filter .= " AND (";
	if (in_array(0, $eF)) { $filter .= "e.title LIKE '{$schTextEsc}' OR e.description LIKE '{$schTextEsc}' OR e.venue LIKE '{$schTextEsc}'"; //All fields
	} else {
		if (in_array(1, $eF)) { $filter .= "e.title LIKE '{$schTextEsc}'"; } //Title
		if (in_array(2, $eF)) { $filter .= ((substr($filter, -1) == '(') ? '' : ' OR ')."e.description LIKE '{$schTextEsc}'"; } //Description
		if (in_array(3, $eF)) { $filter .= ((substr($filter, -1) == '(') ? '' : ' OR ')."e.venue LIKE '{$schTextEsc}'"; } //Venue
	}
	$filter = substr($filter,5).")";
	
	//set event date range
	$sDate = ($fromDda) ? $fromDda : date('Y-m-d',time()-31536000); //-1 year
	$eDate = ($tillDda) ? $tillDda : date('Y-m-d',time()+31536000); //+1 year

	retrieve($sDate,$eDate,'',$filter); //grab events

	//display header
	$fields = '';
	if (in_array(0, $eF)) { $fields = "{$xx['sch_title']} + {$xx['sch_description']} + {$xx['sch_venue']}";
	} else {
		if (in_array(1, $eF)) { $fields .= $xx['sch_title']; }
		if (in_array(2, $eF)) { $fields .= ($fields ? ' + ' : '').$xx['sch_description']; }
		if (in_array(3, $eF)) { $fields .= ($fields ? ' + ' : '').$xx['sch_venue']; }
	}
	echo "<div class='headSh'>
		<form id='event' name='event' method='post' action='index.php'>
		<input type='hidden' name='schText' value=\"{$schText}\">\n";
	foreach ($eF as $key => $value) { echo "<input type='hidden' name='eF[]' value=\"{$value}\">\n";	}
	echo "<input type='hidden' name='catName' value=\"{$catName}\">
		<input type='hidden' name='fromDda' value='".IDtoDD($fromDda)."'>
		<input type='hidden' name='tillDda' value='".IDtoDD($tillDda)."'>
		<input class='floatR noPrint' type='submit' name='newSearch' value=\"{$xx['sch_new_search']}\">
		<span class='floatR'>&nbsp;&nbsp;</span>
		<input class='floatR noPrint' type='button' onclick=\"window.location.href='index.php?cP={$set['defaultView']}'\" value=\"{$xx['sch_calendar']}\">
		</form>
		{$xx['sch_search_text']}: <b>{$schText}</b><br>
		{$xx['sch_event_fields']}: <b>{$fields}</b><br>
		{$xx['sch_event_cat']}: <b>".($catName != '*' ? $catName : $xx['sch_all_cats'])."</b><br>
		{$xx['sch_occurring_between']}: <b>".makeD($sDate,2)." - ".makeD($eDate,2)."</b>
		</div>\n";
}

function showMatches() {
	global $privs, $set, $xx, $evtList, $schText;

	//display matching events
	echo '<div class="eventBg">'."\n";
	if ($evtList) {
		$match = '%('.str_replace(array('_','&'), array('.','[^<>]+?'), $schText).')(?![^<]*>)%i'; //convert to regex (?!: neg.look-ahead condition)
		$evtDone = array();
		$lastDate = '';
		foreach($evtList as $date => &$events) {
			foreach ($events as $evt) {
				if (!$evt['mde'] or !in_array($evt['eid'],$evtDone)) { //!mde or mde not processed
					$evtDone[] = $evt['eid'];
					$evtDate = $evt['mde'] ? makeD($evt['sda'],5)." - ".makeD($evt['eda'],5) : makeD($date,5);
					$evtTime = $evt['ald'] ? $xx['vws_all_day'] : ITtoDT($evt['sti']).($evt['eti'] ? ' - '.ITtoDT($evt['eti']) : '');
					$mayEdit = ($privs > 2 or ($privs == 2 and $evt['uid'] == $_SESSION['uid'])) ? true : false;
					$mayCheck = ($privs > 3 or ($privs > 1 and $evt['uid'] == $_SESSION['uid'])) ? true : false;
					if ($set['eventColor']) {
						$eStyle = ($evt['cco'] ? "color:{$evt['cco']};" : '').($evt['cbg'] ? "background-color:{$evt['cbg']};" : '');
					} else {
						$eStyle = $evt['uco'] ? "background-color:{$evt['uco']};" : '';
					}
					$eStyle = $eStyle ? ' style="'.$eStyle.'"' : '';
					$chBox = '';
					if ($evt['cbx']) { $chBox .= !$evt['mde'] ? (strpos($evt['chd'], $date) ? $evt['cmk'] : '&#9744;') : '?'; }
					if ($chBox) {
						$attrib = ($mayCheck and !$evt['mde']) ? " class='chkBox point' onclick=\"checkE({$evt['eid']},'{$date}');\" title=\"{$xx['vws_check_mark']}\"" : 'class="chkBox arrow"';
						$chBox = "<span{$attrib}>".trim($chBox)."</span> ";
					}
					if ($evt['app'] and !$evt['apd']) { $eBoxStyle = 'border-left:2px solid #ff0000;'; }
					$eBoxStyle = $eBoxStyle ? " style='{$eBoxStyle}'" : '';
					echo $lastDate != $evtDate ? "<br><h6><a href='index.php?cP=2&amp;cD=$date' title=\"{$xx['sch_calendar']}\">{$evtDate}</a></h6>\n" : "<br>\n";
					echo "<table>\n<tr>
						<td class='lMarginUp'>{$evtTime}</td>
						<td class='eBox'{$eBoxStyle}>";
					if ($set['details4All'] or $mayEdit) {
						echo "<h6>{$chBox}<span class='point'{$eStyle} onclick=\"editE({$evt['eid']},'{$date}');\">".preg_replace($match, '<mark>$1</mark>',$evt['tit'])."</span></h6>\n";
						if ($evt['ven']) { echo $xx['vws_venue'].": ".preg_replace($match, '<mark>$1</mark>',$evt['ven'])."<br>\n"; }
						if ($evt['des']) { echo preg_replace($match,'<mark>$1</mark>',$evt['des'])."<br>\n"; }
					} else {
						echo "<h6>{$chBox}<span{$eStyle}>".preg_replace($match,'<mark>$1</mark>',$evt['tit'])."</span></h6>\n";
					}
					if ($set['showCatName']) { echo "{$xx['evt_category']}: {$evt['cnm']}<br>\n"; }
					if ($set['showAdEd']) {
						echo "{$xx['vws_added']}: ".IDtoDD($evt['ada'])." ({$evt['una']})<br>\n";
						if ($evt['mda'] and $evt['edr']) { echo "{$xx['vws_edited']}: ".IDtoDD($evt['mda'])." ({$evt['edr']})<br>\n"; }
					}
					echo "</td></tr></table>\n";
					$lastDate = $evtDate;
				}
			}
		}
	} else {
		echo $xx['sch_no_results']."\n";
	}
	echo "</div>\n";
}

//control logic

$msg = ''; //init
if (isset($_POST["search"])) {
	$msg = validateForm();
}
echo "<p class='error'>{$msg}</p>\n";
if (isset($_POST["search"]) and !$msg) {
	searchText(); //search
	echo "<div class='scrollBoxSh'>\n";
	showMatches(); //show results
	echo "</div>\n";
} else {
	echo "<div class='scrollBoxAd'>
		<aside class='aside'>\n{$xx['sch_instructions']}\n</aside>
		<div class='centerBox'>\n";
	searchForm(); //define search
	echo "</div>\n</div>\n";
}
?>
