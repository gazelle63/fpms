<style>
#redpackstatus {
	float: right;
	line-height: 30px;
	padding: 10px;
	overflow: hidden;
}
#redpackurl {
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
#redpackmessage {
	margin: 0 15px;
}
</style>

<div id="redpackstatus">
<?php
$message = '';
foreach ($accounts as $item)
{
	$message .= '开始编号：<input type="text" class="btn_input" action="redpackstartid" value="'.$item['redpackstartid'].'">　结束编号：<input type="text" class="btn_input" action="redpackendid" value="'.$item['redpackendid'].'">　红包开关：<input type="button" class="btn_switch" action="redpackstatus" value="'.$item['redpackstatus'].'">';
}
echo $message;
?>
</div>

<?php
echo anchor($redpacksurl, '刷新', 'id="redpackurl"');
?>

<div id="redpackmessage">
<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$message = '<tr><td width="80">编号</td><td width="280">昵称</td><td>商家订单号</td><td width="80">金额</td><td width="120">登记时间</td><td width="120">领取时间</td><td width="90">是否领取</td></tr>';
foreach ($redpacks as $item)
{
	$message .= '<tr>';
	$message .= '<td><input type="button" class="btn_click" value="'.$item['redpacksid'].'"></td>';
	$message .= '<td>'.$item['re_openid'].'</td>';
	$message .= '<td><input type="button" class="btn_click" action="mch_billno" mch_billno="'.$item['mch_billno'].'" value="更新红包信息">'.$item['mch_billno'].'</td>';
	$message .= '<td>'.$item['total_amount'].'</td>';
	$message .= '<td>'.date('m-d H:i', $item['re_time']).'</td>';
	if ($item['redpackstime'] > 0)
	{
		$message .= '<td>'.date('m-d H:i', $item['redpackstime']).'</td>';
	}
	else
	{
		$message .= '<td></td>';
	}
	$message .= '<td><input type="button" class="btn_switch" redpacksid="'.$item['redpacksid'].'" value="'.$item['redpacksstatus'].'"></td>';
	$message .= '</tr>';
}
echo $message;
?>
</table>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	var $redpacksurl = "<?php echo $redpacksurl;?>";

	$("#redpackstatus").on("change", ".btn_input", function(){
		$this = $(this);
		$action = $this.attr("action");
		$inputval = $this.val();
		$post_url = $redpacksurl + "/" + $action;
		$post_data = {"inputval" : $inputval};
		$.post($post_url, $post_data, function(data){
			$this.val(data.inputval);
		}, "json");
	});

	$("#redpackstatus").on("click", ".btn_switch", function(){
		$this = $(this);
		$action = $this.attr("action");
		$status = $this.val();
		$post_url = $redpacksurl + "/" + $action;
		$post_data = {"status" : $status};
		$.post($post_url, $post_data, function(data){
			$this.val(data.status);
		}, "json");
	});

	$("#redpackmessage").on("click", ".btn_switch", function(){
		$this = $(this);
		$redpacksid = $this.attr("redpacksid");
		$status = $this.val();
		$post_url = $redpacksurl + "/redpackmessage";
		$post_data = {"redpacksid" : $redpacksid, "status" : $status};
		$.post($post_url, $post_data, function(data){
			$this.val(data.status);
		}, "json");
	});

	$("#redpackmessage").on("click", ".btn_click", function(){
		$this = $(this);
		$inputval = $this.val();
		if ($inputval == "更新红包信息")
		{
			$action = $this.attr("action");
			$mch_billno = $this.attr("mch_billno");
			$post_url = $redpacksurl + "/" + $action;
			$post_data = {"mch_billno" : $mch_billno};
			$.post($post_url, $post_data, function(data){
				$this.val(data.status);
			}, "json");
		}
		else
		{
			$(this).parent().parent().prevUntil('tr:first-of-type').remove();
		}
	});

});
</script>
