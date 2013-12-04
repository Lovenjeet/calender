<?php
//sanity check

if (!defined('LCC')) { exit('not permitted ('.substr(basename(__FILE__),0,-4).')'); } //lounch via script only
echo "<form id='event' name='event' method='post' action='index.php?xP=10'>
	<input type='hidden' name='mode' value='{$mode}'>
	<input type='hidden' name='eid' value='{$eid}'>
	<input type='hidden' name='evD' value='{$evD}'>
	<input type='hidden' name='oUid' value='{$oUid}'>
	<input type='hidden' name='editN' value='{$editN}'>
	<input type='hidden' name='chd' value='{$chd}'>
	<input type='hidden' name='xda' value='{$xda}'>
	<input type='hidden' name='ada' value='{$ada}'>
	<input type='hidden' name='mda' value='{$mda}'>
	<input type='hidden' name='edr' value=\"{$edr}\">
	<input type='hidden' name='own' value=\"{$own}\">\n";
if ($app and $privs > 3) { //manager or admin
	echo "<div class='apdLine'><input type='checkbox' name='apd' value='yes'".($apd ? " checked='checked'> " : "> ")."{$xx['evt_approved']}</div>\n";
}

echo "<table class='evtForm'>
	<col class='col1'><col class='col2'><col class='col3'><col>
	
	

	<tr>
	<td>Eleve:</td><td colspan='3'><input type='text' readOnly='true' name='tit' id='tit' style='width:22%' maxlength='64' value='".$tit."'>
	<input type='text' style='display:none;' id='eleve_id' name='eleve_id' value='".$eleve_id."' />&nbsp;&nbsp;<button type='button'   onclick='newEleve();' \>Rechercher Eleve</button></td>
	</tr>


	<tr>
	<td>{$xx['evt_category']}:</td>
	<td colspan='2'>\n<select name='cid' id='cid'>"; catMenu($cid); echo "</select>\n</td>
	<td>";
	if ($_SESSION['uid'] > 1 and $set['privEvents'] and $set['privEvents'] < 3) { //logged in
	echo "<input type='checkbox' name='pri' value='yes'".($pri ? " checked='checked'> " : "> ")."{$xx['evt_private']}\n";
	}
	echo "</td>\n
	</tr>
	
	
	
	<tr>
	<td>{$xx['evt_venue']}:</td>
	<td colspan='2'>\n<select name='circuit_id' id='circuit_id'>"; circuitMenu($circuit_id); echo "</select>\n</td></tr>
	<tr>

	
	
	
	
	<tr>
	<td>Employe:</td>
	<td colspan='2'>\n<select name='employee_id' id='employee_id'>"; employeMenu($emp_id); echo "</select>\n</td></tr>
	<tr>

	
	
	
	
	
	
	<td class='arrow'>{$xx['evt_description']}:<br><br><span onmouseover=\"pop('".htmlspecialchars($xx['evt_descr_help'])."', 'normal')\" onmouseout=\"pop()\">({$xx['evt_help']})</span></td>
	<td colspan='3'><textarea name='des' id='des' rows='3' cols='1' style='width:100%'>{$des}</textarea></td>
	</tr>
	<tr><td colspan='4'><hr></td></tr>
	<tr>
		<td>{$xx['evt_start_date']}:</td>
		<td><input type='text' name='sda' id='sda' value='{$sda}' size='8'><button title=\"{$xx['evt_select_date']}\" onclick=\"dPicker(0,'nill','sda','eda');return false;\">&larr;</button></td>
		<td id='dTimeS'{$hidden}><input type='text' name='sti' id='sti' value='{$sti}' size='6'><button title=\"{$xx['evt_select_time']}\" onclick=\"tPicker('sti');return false;\">&larr;</button></td>
		<td><input type='checkbox' onclick=\"hide_times(this);\" name='ald' value='all'{$chBoxed}> {$xx['evt_all_day']}</td>
	</tr>
	<tr>
		<td>{$xx['evt_end_date']}:</td>
		<td><input type='text' name='eda' id='eda' value='{$eda}' size='8'><button title=\"{$xx['evt_select_date']}\" onclick=\"dPicker(1,'nill','eda','sda');return false;\">&larr;</button></td>
		<td id='dTimeE'{$hidden}><input type='text' name='eti' id='eti' value='{$eti}' size='6'><button title=\"{$xx['evt_select_time']}\" onclick=\"tPicker('eti');return false;\">&larr;</button></td>
		<td></td>
	</tr>
	<tr>
		<td colspan='4'>{$repTxt}&nbsp;<button type='button' onclick=\"show('repBox');\">{$xx['evt_change']}</button><br></td>
	</tr>\n";
	if ($set['mailServer']) {
		echo "<tr><td colspan='4'><hr></td></tr>
			<tr>\n<td>{$xx['evt_notify']}:</td>
			<td colspan='3'>
			<input type='checkbox' name='non' value='yes'".($non ? " checked='checked'> " : '> ')."{$xx['evt_now_and_or']}&nbsp;
			<input type='text' name='not' style='width:20px' maxlength='2' value=\"{$not}\"> {$xx['evt_days_before_event']}
			<span class='floatR arrow' onmouseover=\"pop('".htmlspecialchars($xx['evt_mail_help'])."', 'normal')\" onmouseout=\"pop()\">({$xx['evt_help']})</span>
			</td>\n</tr>
			<tr>
			<td colspan='4'>
			<input type='text' name='nml' id='nml' style='width:100%' maxlength='255' value=\"{$nml}\">
			</td>\n</tr>\n";
	}
	echo "<tr>\n<td colspan='4'><hr></td>\n</tr>
		<tr>\n<td colspan='4'>{$xx['evt_added']}: ".IDtoDD($ada)." {$xx['by']} ";
	if ($privs > 3) { //manager or admin
		echo "<select name='uid' id='uid'>\n";
			userMenu($uid);
		echo "</select>\n";
	} else {
		echo $own;
	}
	if ($mda and $edr) { echo " - {$xx['evt_edited']}: ".IDtoDD($mda)." {$xx['by']} {$edr}"; }
	echo "</td>\n</tr>
</table>\n";





echo "<div class='repBox' id='repBox'>
	<div class='floatC'><b>{$xx['evt_set_repeat']}</b><hr></div>
	<div>
		<input type='radio' name='r_t' value='0'".(!$r_t ? " checked='checked'>" : '> ')."{$xx['evt_no_repeat']}
		<br>
		<input type='radio' name='r_t' value='1'".($r_t == "1" ? " checked='checked'>" : '> ')."{$xx['evt_repeat']}
		<select name='ri1' id='ri1'>
			<option value='1'".($ri1 == '1' ? " selected='selected'>" : '>').$xx['evt_interval1_1']."</option>
			<option value='2'".($ri1 == '2' ? " selected='selected'>" : '>').$xx['evt_interval1_2']."</option>
			<option value='3'".($ri1 == '3' ? " selected='selected'>" : '>').$xx['evt_interval1_3']."</option>
			<option value='4'".($ri1 == '4' ? " selected='selected'>" : '>').$xx['evt_interval1_4']."</option>
			<option value='5'".($ri1 == '5' ? " selected='selected'>" : '>').$xx['evt_interval1_5']."</option>
			<option value='6'".($ri1 == '6' ? " selected='selected'>" : '>').$xx['evt_interval1_6']."</option>
		</select>
		<select name='rp1' id='rp1'>
			<option value='1'".($rp1 == '1' ? " selected='selected'>" : '>').$xx['evt_period1_1']."</option>
			<option value='2'".($rp1 == '2' ? " selected='selected'>" : '>').$xx['evt_period1_2']."</option>
			<option value='3'".($rp1 == '3' ? " selected='selected'>" : '>').$xx['evt_period1_3']."</option>
			<option value='4'".($rp1 == '4' ? " selected='selected'>" : '>').$xx['evt_period1_4']."</option>
		</select>
		<br>
		<input type='radio' name='r_t' value='2'".($r_t == "2" ? 'checked="checked"> ' : '> ')."{$xx['evt_repeat_on']}
		<select name='ri2' id='ri2'>
			<option value='1'".($ri2 == '1' ? " selected='selected'>" : '>').$xx['evt_interval2_1']."</option>
			<option value='2'".($ri2 == '2' ? " selected='selected'>" : '>').$xx['evt_interval2_2']."</option>
			<option value='3'".($ri2 == '3' ? " selected='selected'>" : '>').$xx['evt_interval2_3']."</option>
			<option value='4'".($ri2 == '4' ? " selected='selected'>" : '>').$xx['evt_interval2_4']."</option>
			<option value='5'".($ri2 == '5' ? " selected='selected'>" : '>').$xx['evt_interval2_5']."</option>
		</select>
		<select name='rp2' id='rp2'>
			<option value='1'".($rp2 == '1' ? " selected='selected'>" : '>').$wkDays[1]."</option>
			<option value='2'".($rp2 == '2' ? " selected='selected'>" : '>').$wkDays[2]."</option>
			<option value='3'".($rp2 == '3' ? " selected='selected'>" : '>').$wkDays[3]."</option>
			<option value='4'".($rp2 == '4' ? " selected='selected'>" : '>').$wkDays[4]."</option>
			<option value='5'".($rp2 == '5' ? " selected='selected'>" : '>').$wkDays[5]."</option>
			<option value='6'".($rp2 == '6' ? " selected='selected'>" : '>').$wkDays[6]."</option>
			<option value='7'".($rp2 == '7' ? " selected='selected'>" : '>').$wkDays[7]."</option>
		</select>
		{$xx['of']} 
		<select name='rpm' id='rpm'>
			<option value='0'".($rpm == '0' ? " selected='selected'>" : '>').$xx['evt_each_month']."</option>
			<option value='1'".($rpm == '1' ? " selected='selected'>" : '>').$months[0]."</option>
			<option value='2'".($rpm == '2' ? " selected='selected'>" : '>').$months[1]."</option>
			<option value='3'".($rpm == '3' ? " selected='selected'>" : '>').$months[2]."</option>
			<option value='4'".($rpm == '4' ? " selected='selected'>" : '>').$months[3]."</option>
			<option value='5'".($rpm == '5' ? " selected='selected'>" : '>').$months[4]."</option>
			<option value='6'".($rpm == '6' ? " selected='selected'>" : '>').$months[5]."</option>
			<option value='7'".($rpm == '7' ? " selected='selected'>" : '>').$months[6]."</option>
			<option value='8'".($rpm == '8' ? " selected='selected'>" : '>').$months[7]."</option>
			<option value='9'".($rpm == '9' ? " selected='selected'>" : '>').$months[8]."</option>
			<option value='10'".($rpm == '10' ? " selected='selected'>" : '>').$months[9]."</option>
			<option value='11'".($rpm == '11' ? " selected='selected'>" : '>').$months[10]."</option>
			<option value='12'".($rpm == '12' ? " selected='selected'>" : '>').$months[11]."</option>
		</select>
		<br><br>{$xx['evt_until']} 
		<input type='text' name='rul' id='rul' value='{$rul}' size='8'>
		<button title=\"{$xx['evt_select_date']}\" onclick=\"dPicker(1,'nill','rul','sda');return false;\">&larr;</button> ({$xx['evt_blank_no_end']})
	</div>
	<div class='floatC'><hr><input type='submit' name='refresh' value=\"{$xx['evt_set']}\"></div>
</div>
<div class='floatC'>\n";
if ($mode == "add" or $mode == "add_exe") {
	echo "<input type='submit' name='add_exe_cls' value=\"{$xx['evt_add_close']}\">
		&nbsp;&nbsp;<input type='submit' name='add_exe' value=\"{$xx['evt_add']}\">";
} else {
	echo "<input type='submit' name='upd_exe_cls' value=\"{$xx['evt_save_close']}\">
		&nbsp;&nbsp;<input type='submit' name='upd_exe' value=\"{$xx['evt_save']}\">
		&nbsp;&nbsp;<input type='submit' name='add_exe' value=\"{$xx['evt_clone']}\">
		&nbsp;&nbsp;<input type='submit' name='del_exe' value=\"{$xx['evt_delete']}\">\n";
}
echo "&nbsp;&nbsp;<button type='button' onclick=\"done(1,0);\">{$xx['evt_close']}</button>
</div>
</form>\n";
?>
<script>$I("tit").focus();</script>




<?php
/////////////////////////////////////////////////////////////////////////////////

$fp = fopen('data.txt', 'w');
//fwrite($fp, catMenu($cid));

fwrite($fp, $xx['evt_add_close']);


fclose($fp);

/////////////////////////////////////////////////////////////////////////////////






