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

function searchForm() {
	
	global $xx, $set, $schText, $eF, $catName, $fromDda, $tillDda;
	
	echo "<form action='index.php?cP=97' method='post'>
		<table class='fieldBox'>
		<tr><td class='legend' colspan='2'>&nbsp;{$xx['sch_define_search']}&nbsp;</td></tr>
		
		<tr>\n<td class='label'>{$xx['sch_search_text']}:</td>
		<td><input type='text' name='schText' id='schText' value=\"{$schText}\" maxlength='50' size='30'></td>\n</tr>
		<tr><td colspan='2'><hr></td></tr>
		
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
	
	return '';
}
	
function searchText() {
	global $xx, $set, $schText, $eF, $catName, $fromDda,$evtList, $tillDda;
	
	//set event filter
	$evtList = array();
	$record = array();
	$schTextEsc = '%'.mysql_real_escape_string($schText).'%';
	$schTextEsc = str_replace('&', '%', $schTextEsc);
	$filter = '';
	
	
	
	
	

	 $filter .=" AND (ELEVEPRENOM LIKE '{$schTextEsc}' OR ELEVENOM LIKE '{$schTextEsc}' OR ELEVETELEPHONE LIKE '{$schTextEsc}')"; //All fields
	
	
	//echo $filter;
	
	//$filter = substr($filter,5).")";
	//echo $filter;
	//set event date range
//	$sDate = ($fromDda) ? $fromDda : date('Y-m-d',time()-31536000); //-1 year
	//$eDate = ($tillDda) ? $tillDda : date('Y-m-d',time()+31536000); //+1 year
	 $where = " WHERE ELEVEPRENOM != '' ".$filter;
	 
	 $query="SELECT * FROM mycal_eleve{$where} ORDER BY ELEVEID";
	 $eleve = dbQuery($query);
	//retrieve_eleve('',$filter); //grab events
	if ($eleve !== false) {
		
		while ($row = mysql_fetch_assoc($eleve)) 
		{
		
		 $evtList[$row['ELEVEID']] = array(
        "ELEVEID"=>$row['ELEVEID'],
		"ELEVEPRENOM"=>$row['ELEVEPRENOM'],
		"ELEVENOM"=>$row['ELEVENOM'],
        "ELEVETELEPHONE"=>$row['ELEVETELEPHONE']);
		
			
			//print_r($row);
		}
		//echo "<pre>";
	//	print_r($evtList);
	}
	
	
/*	ksort($evtList);
	foreach ($evtList as &$events) 
	{
		
		usort($events, 'sortEvt');
		
	}
	print_r($evtList);*/
	//display header
	
	
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
		//print_r($evtList);
		echo "<table><tr><th>\n{$xx['set_event_student_name']}\n</th><th>\n{$xx['set_event_student_telephone']}\n</th><th></th></tr>";
		
		foreach($evtList as &$events) {
			
			?>
            <tr>
            <td><?php echo $events['ELEVENOM']; ?></td>
            <td><?php echo $events['ELEVETELEPHONE']; ?></td>
					<td width='150'><button type='button'  onclick="selectedEleve('<?php echo $events['ELEVEID']; ?>','<?php echo $events['ELEVENOM']; ?>' ),window.close()">Select Eleve</button></td>
					<?php echo "</tr>";
			}
	} else {
		echo $xx['sch_no_results']."\n";
	}
	echo "</div>\n";
}

//control logic

$msg = ''; //init
if (isset($_POST["search"])) {
//	$msg = validateForm();
}
echo "<p class='error'>{$msg}</p>\n";
if (isset($_POST["search"]) and !$msg) {
	searchText(); //search
	
	searchForm(); //define search
	
	showMatches(); 
	
	
	
} else {
	echo "<div class='scrollBoxAd'>
		<aside class='aside'>\n{$xx['sch_instructions']}\n</aside>
		<div class='centerBox'>\n";
	searchForm(); //define search
	echo "</div>\n</div>\n";
}
?>
