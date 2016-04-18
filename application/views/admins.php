<?php
$message = '<ul class="ul_float_a">';
$message .= '<li>';
$message .= form_open('fpms_admin/admins/add');
$message .= form_dropdown('adminslevel', $adminslevel);
$message .= form_input('accountsid', '', 'placeholder="accountdis"');
$message .= form_input('adminsname', '', 'placeholder="adminsname"');
$message .= form_input('adminspass', '', 'placeholder="adminspass"');
$message .= form_submit('submit', '添加');
$message .= form_close();
$message .= '</li>';
foreach ($admins as $item)
{
	$message .= '<li>';
	$message .= form_open('fpms_admin/admins/edit');
	$message .= form_hidden('adminsid', $item['adminsid']);
	$message .= form_dropdown('adminslevel', $adminslevel, $item['adminslevel']);
	$message .= form_input('accountsid', $item['accountsid']);
	$message .= form_input('adminsname', $item['adminsname']);
	$message .= form_input('adminspass', '', 'placeholder="不修改密码请留空"');
	$message .= form_submit('submit', '修改'.$item['adminsid']);
	$message .= form_close();
	$message .= '</li>';
}
$message .= '</ul>';
echo $message;
?>
