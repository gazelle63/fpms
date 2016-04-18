<style>
body {
	margin: 2em;
}
h1 {
	margin: 2em 0 1em;
}
img {
	border-radius: 5px;
	box-shadow: 0 0 10px #990000;
	margin: 1em;
}
</style>
<?php
$msg = '<h1>● 账号完善</h1>';
if ($appsecret == '')
{
	$msg .= '<p style="color:#990000;">请提供 PUBLICID & APPID & APPSECRET 给管理员</p>';
}
else
{
	$msg .= '<p>已提供 PUBLICID & APPID & APPSECRET 给管理员了</p>';
}
$msg .= '<img src="'.base_url('uploads/helps_05.png').'">';
$msg .= '<h1>● 接入系统</h1>';
$msg .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;URL： <input value="'.base_url('fpms/wechat/'.$publicid).'"></p><p>TOKEN： <input value="'.$token.'"></p>';
$msg .= '<img src="'.base_url('uploads/helps_04.png').'">';
$msg .= '<h1>● 微信墙</h1>';
$msg .= '<img src="'.base_url('uploads/helps_01.png').'">';
$msg .= '<h1>● 摇一摇</h1>';
$msg .= '<p>URL： <input value="'.$oauthurl.'"> / 没有网页授权接口的请添加为事件菜单(key=wechatshake)</p>';
$msg .= '<img src="'.base_url('uploads/helps_06.png').'">';
$msg .= '<img src="'.base_url('uploads/helps_02.png').'">';
$msg .= '<h1>● 中奖名单</h1>';
$msg .= '<img src="'.base_url('uploads/helps_03.png').'">';
echo $msg;
 ?>
