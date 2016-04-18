<style>
body {background-color:#e5e5e5;}
#fansbox {
	text-align: center;
}
#fansbox img {
	width: 25%;
	border-radius: 50%;
}
#linkbox a {
	display: block;
	background-color: #ffffff;
	padding: 10px 20px;
	border-top: 1px solid #aaaaaa;
	border-bottom: 1px solid #aaaaaa;
	margin: 10px 0px;
}
</style>
<div id="fansbox">
<?php
	$message = '<img src="'.$fans['headimgurl'].'">';
	$message .= '<p>NICKNAME:'.$fans['nickname'].'</p>';
	$message .= '<p>FANSID:'.$fans['fansid'].'</p>';
	$message .= '<p>POINTS:'.$fans['points'].'</p>';
	echo $message;
?>
</div>
<div id="linkbox">
<?php
	$message = '';
	if ($fans['level'] == 1)
	{
		$message .= anchor('fpms_points/idents', '我要认证');
	}
	$message .= anchor('fpms_points/sign', '我要签到');
	$message .= anchor('fpms_goods/index', '积分兑换');
	echo $message;
?>
</div>
