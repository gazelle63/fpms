<?php
$message = '<ul class="ul_float_a">';
$message .= '<li>';
$message .= form_open('fpms_admin/rules/add');
$message .= form_dropdown('rulestypesid', $rulestypesid);
$message .= form_input('rulesname', '', 'placeholder="rulesname"');
$message .= form_input('rulespoints', '', 'placeholder="rulespoints"');
$message .= form_input('rulesstatus', '', 'placeholder="rulesstatus"');
$message .= form_submit('submit', '添加');
$message .= form_close();
$message .= '</li>';
foreach ($rules as $item)
{
	$message .= '<li>';
	$message .= form_open('fpms_admin/rules/edit');
	$message .= form_hidden('rulesid', $item['rulesid']);
	$message .= form_dropdown('rulestypesid', $rulestypesid, $item['rulestypesid']);
	$message .= form_input('rulesname', $item['rulesname']);
	$message .= form_input('rulespoints', $item['rulespoints']);
	$message .= form_input('rulesstatus', $item['rulesstatus']);
	$message .= form_submit('submit', '修改'.$item['rulesid']);
	$message .= form_close();
	$message .= '</li>';
}
$message .= '</ul>';
echo $message;
?>
