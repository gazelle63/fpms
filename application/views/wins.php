<style>
#winstatus {
	float: right;
	line-height: 30px;
	padding: 10px;
	overflow: hidden;
}
#winurl {
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
#winmessage {
	margin: 0 15px;
}
</style>

<div id="winstatus">
<?php
$message = '';
foreach ($accounts as $item)
{
	$message .= '中奖历史：<input type="text" class="btn_input" action="wintime" value="'.$item['wintime'].'">s　多次中奖：<input type="button" class="btn_switch" action="winstatus" value="'.$item['winstatus'].'">';
}
echo $message;
?>
</div>

<?php
echo anchor($winsurl, '刷新', 'id="winurl"');
?>

<div id="winmessage">
<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$lasttime = 0;
$r = 200;
$g = 200;
$b = 200;
$message = '<tr><td width="70">编号</td><td width="200">昵称</td><td>信息</td><td width="70">排名</td><td width="70">密码</td><td width="70">时间</td><td width="90">审核</td></tr>';
foreach ($wins as $item)
{
	if ($lasttime != intval($item['winstime']))
	{
		$r = mt_rand(200,255);
		$g = mt_rand(200,255);
		$b = mt_rand(200,255);
		$lasttime = intval($item['winstime']);
	}
	$message .= '<tr style="background-color:rgb('.$r.','.$g.','.$b.');">';
	$message .= '<td><input type="button" class="btn_click" value="'.$item['winsid'].'"></td>';
	$message .= '<td>'.$item['fansid'].'</td>';
	$message .= '<td>'.$item['winsdata'].'</td>';
	$message .= '<td>'.$item['winsrank'].'</td>';
	$message .= '<td style="color:rgb('.$r.','.$g.','.$b.');">'.$item['winstoken'].'</td>';
	$message .= '<td>'.date('H:i:s', $item['winstime']).'</td>';
	$message .= '<td><input type="button" class="btn_switch" winsid="'.$item['winsid'].'" value="'.$item['winsstatus'].'"></td>';
	$message .= '</tr>';
}
echo $message;
?>
</table>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	var $winsurl = "<?php echo $winsurl;?>";

	$("#winstatus").on("change", ".btn_input", function(){
		$this = $(this);
		$action = $this.attr("action");
		$inputval = $this.val();
		$post_url = $winsurl + "/" + $action;
		$post_data = {"inputval" : $inputval};
		$.post($post_url, $post_data, function(data){
			window.location.reload();
		}, "json");
	});

	$("#winstatus").on("click", ".btn_switch", function(){
		$this = $(this);
		$action = $this.attr("action");
		$status = $this.val();
		$post_url = $winsurl + "/" + $action;
		$post_data = {"status" : $status};
		$.post($post_url, $post_data, function(data){
			$this.val(data.status);
		}, "json");
	});

	$("#winmessage").on("click", ".btn_switch", function(){
		$this = $(this);
		$winsid = $this.attr("winsid");
		$status = $this.val();
		$post_url = $winsurl + "/winmessage";
		$post_data = {"winsid" : $winsid, "status" : $status};
		$.post($post_url, $post_data, function(data){
			$this.val(data.status);
		}, "json");
	});

	$("#winmessage").on("click", ".btn_click", function(){
		$(this).parent().parent().prevUntil('tr:first-of-type').remove();
	});

});
</script>
