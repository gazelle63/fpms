<?php
$message = '<ul class="ul_float_a">';
$message .= '<li>';
$message .= form_open('fpms_admin/types/add');
$message .= form_dropdown('typestable', $typestable);
$message .= form_input('typesname', '', 'placeholder="typesname"');
$message .= form_submit('submit', '添加');
$message .= form_close();
$message .= '</li>';
foreach ($types as $item)
{
	$message .= '<li>';
	$message .= form_open('fpms_admin/types/edit');
	$message .= form_hidden('typesid', $item['typesid']);
	$message .= form_dropdown('typestable', $typestable, $item['typestable']);
	$message .= form_input('typesname', $item['typesname']);
	$message .= form_submit('submit', '修改'.$item['typesid']);
	$message .= form_close();
	$message .= '</li>';
}
$message .= '</ul>';
echo $message;
?>
