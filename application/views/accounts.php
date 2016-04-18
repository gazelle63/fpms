<?php
$message = '<ul class="ul_float_a">';
$message .= '<li>';
$message .= form_open('fpms_admin/accounts/add');
$message .= form_input('accountsname', '', 'placeholder="accountsname"');
$message .= form_input('accountsqrcode', '', 'placeholder="accountsqrcode"');
$message .= form_input('publicid', '', 'placeholder="publicid"');
$message .= form_input('token', '', 'placeholder="token"');
$message .= form_input('appid', '', 'placeholder="appid"');
$message .= form_input('appsecret', '', 'placeholder="appsecret"');
$message .= form_input('encodingaeskey', '', 'placeholder="encodingaeskey"');
$message .= form_input('cjyun', '', 'placeholder="设1为微信矩阵透传"');
$message .= form_submit('submit', '添加');
$message .= form_close();
$message .= '</li>';
foreach ($accounts as $item)
{
	$message .= '<li>';
	$message .= form_open('fpms_admin/accounts/edit');
	$message .= form_hidden('accountsid', $item['accountsid']);
	$message .= form_input('accountsname', $item['accountsname']);
	$message .= form_input('accountsqrcode', $item['accountsqrcode']);
	$message .= form_input('publicid', $item['publicid']);
	$message .= form_input('token', $item['token']);
	$message .= form_input('appid', $item['appid']);
	$message .= form_input('appsecret', $item['appsecret']);
	$message .= form_input('encodingaeskey', $item['encodingaeskey']);
	$message .= form_input('cjyun', $item['cjyun']);
	$message .= form_submit('submit', '修改'.$item['accountsid']);
	$message .= form_close();
	$message .= '</li>';
}
$message .= '</ul>';
echo $message;
?>
