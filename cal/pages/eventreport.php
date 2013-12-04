<?php
//sanity check
if (!defined('LCC')) { exit('not permitted ('.substr(basename(__FILE__),0,-4).')'); } //lounch via script only

if ($app and $apd) { //manager or admin
	echo "<div class='apdLine'>{$xx['evt_apd_locked']}</div>\n";
}

$eColor = ($col or $bco) ? " style='color:{$col}; background:{$bco};'" : '';
echo "<table class='evtForm'>
	<tr>\n<td>{$xx['evt_title']}:</td><td><span{$eColor}>{$tit}</span></td>\n</tr>
	<tr>\n<td>{$xx['evt_venue']}:</td><td>{$ven}</td>\n</tr>
	<tr>\n<td>{$xx['evt_category']}:</td><td>{$cnm}".($pri ? "&nbsp;&nbsp;&nbsp;{$xx['evt_private']}" : '')."</td>\n</tr>
	<tr>\n<td>{$xx['evt_description']}:</td><td>{$desHtml}</td>\n</tr>
	<tr>\n<td colspan='2'><hr></td>\n</tr>
	<tr>\n<td>{$xx['evt_date_time']}:</td><td>{$sda}";
if ($ald) {
	echo ($eda ? " - {$eda}" : '')." {$xx['at_time']} {$xx['evt_all_day']}";
} else {
	echo " {$xx['at_time']} {$sti}";
	if ($eda) { echo " - {$eda}"; }
	if ($eti) { echo ($eda ? " {$xx['at_time']} " : ' - ').$eti; }
}
echo "</td>\n</tr>\n";
if ($r_t) {
	echo "<tr>\n<td colspan='2'>{$repTxt}<br></td>\n</tr>\n";
}
if ($not != "" and ($privs > 2 or ($privs == 2 and $uid == $_SESSION['uid']))) { //has rights to see email list
	echo "<tr>\n<td colspan='2'><hr></td></tr>\n
		<tr>\n<td>{$xx['evt_notify']}:</td>\n<td>{$not} {$xx['evt_days_before_event']}</td>\n</tr>
		<tr>\n<td colspan='2'>{$nml}</td></tr>\n";
}
if ($set['showAdEd']) {
	echo "<tr>\n<td colspan='2'><hr></td>\n</tr>
		<tr>\n<td colspan='2'>{$xx['evt_added']}: ".IDtoDD($ada)." {$xx['by']} {$own}";
	if ($mda and $edr) {
		echo " - {$xx['evt_edited']}: ".IDtoDD($mda)." {$xx['by']} {$edr}";
	}
	echo "</td>\n</tr>\n";
}
?>
</table>

<?php
echo "<div class='floatC'><button onClick=\"javascript:self.close();\">{$xx["evt_close"]}</button></div>\n";
?>
