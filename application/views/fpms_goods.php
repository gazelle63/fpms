<style>
body {background-color:#e5e5e5;}
#fansbox {
	text-align: center;
}
#fansbox img {
	width: 25%;
	border-radius: 50%;
}
#goodsbox li {
	background-color: #ffffff;
	padding: 2%;
	border: 1px solid #aaaaaa;
	margin: 2%;
	overflow: hidden;
}
#goodsbox li img {
	float: left;
	width: 35%;
	border: 1px solid #aaaaaa;
	margin-right: 2%;
}
#goodsbox li a {
	display: block;
	line-height: 2em;
	background-color: #009900;
	color: #ffffff;
	border-bottom: 5px solid #007700;
	border-radius: 5px;
	margin: 10px 2% 0px;
	text-decoration: none;
	text-align: center;
	clear: both;
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
<ul id="goodsbox">
<?php
$message = '';
foreach($goods as $item)
{
	$message .= '<li>';
	$message .= '<img src="'.$fans['headimgurl'].'">';
	$message .= '<p>奖品名称：'.$item['goodsname'].'</p>';
	$message .= '<p>需要积分：'.$item['goodspoints'].'</p>';
	$message .= '<p>当前折扣：'.$item['goodsdiscount'].'%</p>';
	$message .= '<p>剩余数量：'.$item['goodsamount'].'</p>';
	$message .= '<p>开始时间：'.date('Y-m-d H:i', $item['goodsltime']).'</p>';
	$message .= '<p>结束时间：'.date('Y-m-d H:i', $item['goodsrtime']).'</p>';
	$message .= anchor('fpms_goods/exchanges/'.$item['goodsid'], '我要兑换');
	$message .= '</li>';
}
	echo $message;
?>
</ul>
