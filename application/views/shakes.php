<style>
#shakestatus {
	float: right;
	line-height: 30px;
	padding: 10px;
	overflow: hidden;
}
#shakeurl {
	float: left;
	background-color: #75b936;
	color: #ffffff;
	width: 100px;
	line-height: 30px;
	text-align: center;
	text-decoration: none;
	margin: 10px 15px;
	border-radius: 15px;
	overflow: hidden;
}
#shakemessage {
	margin: 0 15px;
}
.btn_span {
	background-color: #75b936;
	color: #ffffff;
	font-size: 0.9em;
	text-align: center;
	width: 5em;
	padding: 1px 10px 2px;
	border: 0;
	margin-left: 15px;
	cursor: pointer;
	border-radius: 15px;
	overflow: hidden;
	transition: all 0.3s ease-in 0.1s;
}
</style>

<div id="shakestatus">
<?php
$message = '';
foreach ($accounts as $item)
{
	$message .= '获奖人数：<input type="text" class="btn_input" action="shakewin" value="'.$item['shakewin'].'">　最大摇晃数：<input type="text" class="btn_input" action="shakemax" value="'.$item['shakemax'].'">　比赛时间：<input type="text" class="btn_input" action="shaketime" value="'.$item['shaketime'].'">s　摇一摇状态：<input type="button" class="btn_switch" action="shakestatus" value="'.$item['shakestatus'].'">';
}
echo $message;
?>
</div>

<?php
echo anchor($wechatshakeurl, '摇一摇', 'id="shakeurl" target="_blank"');
?>

<div id="shakemessage">
<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$message = '<tr><td width="70">编号</td><td width="200">昵称</td><td>摇晃数<span class="btn_span" action="shakeempty">清空数据</span><span class="btn_span" action="shakequit">结束游戏</span></td><td width="70">时间</td></tr>';
foreach ($fans as $item)
{
	$message .= '<tr>';
	$message .= '<td><input type="button" class="btn_click" value="'.$item['fansid'].'"></td>';
	$message .= '<td>'.$item['nickname'].'</td>';
	$message .= '<td>'.$item['shakes'].'</td>';
	$message .= '<td>'.date('H:i:s', $item['lasttime']).'</td>';
	$message .= '</tr>';
}
echo $message;
?>
</table>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	var $shakesurl = "<?php echo $shakesurl;?>";

	$("#shakestatus").on("change", ".btn_input", function(){
		$this = $(this);
		$action = $this.attr("action");
		$inputval = $this.val();
		$post_url = $shakesurl + "/" + $action;
		$post_data = {"inputval" : $inputval};
		$.post($post_url, $post_data, function(data){
			$this.val(data.inputval);
		}, "json");
	});

	$("#shakestatus").on("click", ".btn_switch", function(){
		$this = $(this);
		$action = $this.attr("action");
		$status = $this.val();
		$post_url = $shakesurl + "/" + $action;
		$post_data = {"status" : $status};
		$.post($post_url, $post_data, function(data){
			$this.val(data.status);
		}, "json");
	});

	$("#shakemessage").on("click", ".btn_span", function(){
		$this = $(this);
		$action = $this.attr("action");
		$post_url = $shakesurl + "/" + $action;
		$post_data = {};
		$.post($post_url, $post_data, function(data){
			window.location.reload();
		}, "json");
	});

});
</script>
