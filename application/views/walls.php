<style>
#wallstatus {
	float: right;
	line-height: 30px;
	padding: 10px;
	overflow: hidden;
}
#wallurl {
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
#wallmessage {
	margin: 0 15px;
}
</style>

<div id="wallstatus">
<?php
$message = '';
foreach ($accounts as $item)
{
	$message .= '历史数据：<input type="text" class="btn_input" action="walltime" value="'.$item['walltime'].'">s　不需要审核：<input type="button" class="btn_switch" action="wallverify" value="'.$item['wallverify'].'">　微信墙开关：<input type="button" class="btn_switch" action="wallstatus" value="'.$item['wallstatus'].'">';
}
echo $message;
?>
</div>

<?php
echo anchor($wechatwallurl, '微信墙', 'id="wallurl" target="_blank"');
?>

<div id="wallmessage">
<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$lastid = 0;
$message = '<tr><td width="70">编号</td><td width="200">昵称</td><td>信息</td><td width="120">时间</td><td width="90">审核</td></tr>';
foreach ($infos as $item)
{
	$message .= '<tr>';
	$message .= '<td><input type="button" class="btn_click" value="'.$item['infosid'].'"></td>';
	if ($item['nickname'] == '')
	{
		$message .= '<td><input type="button" class="btn_click" action="wallfans" infosid="'.$item['infosid'].'" value="更新用户信息"></td>';
	}
	else
	{
		$message .= '<td>'.$item['nickname'].'</td>';
	}
	if ($item['infostype'] == 2)
	{
		$message .= '<td><img src="'.base_url('uploads/weixin/'.$item['infomation']).'" height="25"></td>';
	}
	else
	{
		$message .= '<td>'.$item['infomation'].'</td>';
	}
	$message .= '<td>'.date('m-d H:i:s', $item['infostime']).'</td>';
	$message .= '<td><input type="button" class="btn_switch" infosid="'.$item['infosid'].'" value="'.$item['infosstatus'].'"></td>';
	$message .= '</tr>';
	$lastid = $item['infosid'];
}
echo $message;
?>
</table>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	var $wallsurl = "<?php echo $wallsurl;?>";
	var lastid = <?php echo $lastid;?>;

	$("#wallstatus").on("change", ".btn_input", function(){
		$this = $(this);
		$action = $this.attr("action");
		$inputval = $this.val();
		$post_url = $wallsurl + "/" + $action;
		$post_data = {"inputval" : $inputval};
		$.post($post_url, $post_data, function(data){
			$this.val(data.inputval);
		}, "json");
	});

	$("#wallstatus").on("click", ".btn_switch", function(){
		$this = $(this);
		$action = $this.attr("action");
		$status = $this.val();
		$post_url = $wallsurl + "/" + $action;
		$post_data = {"status" : $status};
		$.post($post_url, $post_data, function(data){
			$this.val(data.status);
		}, "json");
	});

	$("#wallmessage").on("click", ".btn_switch", function(){
		$this = $(this);
		$infosid = $this.attr("infosid");
		$status = $this.val();
		$post_url = $wallsurl + "/wallmessage";
		$post_data = {"infosid" : $infosid, "status" : $status};
		$.post($post_url, $post_data, function(data){
			$this.val(data.status);
		}, "json");
	});

	$("#wallmessage").on("click", ".btn_click", function(){
		$this = $(this);
		$inputval = $this.val();
		if ($inputval == "更新用户信息")
		{
			$action = $this.attr("action");
			$infosid = $this.attr("infosid");
			$post_url = $wallsurl + "/" + $action;
			$post_data = {"infosid" : $infosid};
			$.post($post_url, $post_data, function(data){
				$this.val(data.inputval);
			}, "json");
		}
		else
		{
			$(this).parent().parent().prevUntil('tr:first-of-type').remove();
		}
	});

	setInterval(function(){
		$.post($wallsurl + "/json", {"lastid": lastid}, function(data){
			if (data.wall_num > 0)
			{
				new_infos = '';
				$.each(data.wall_msg, function(i){
					infosid = data.wall_msg[i]["infosid"];
					infosstatus = data.wall_msg[i]["infosstatus"];
					infostime = data.wall_msg[i]["infostime"];
					infostime = new Date(infostime*1000);
					infostime = infostime.getHours()+":"+infostime.getMinutes()+":"+infostime.getSeconds();
					headimgurl = data.wall_msg[i]["headimgurl"];
					nickname = data.wall_msg[i]["nickname"];
					infostype = data.wall_msg[i]["infostype"];
					if (infostype == "1")
					{
						infomation = data.wall_msg[i]["infomation"];
					}
					else
					{
						infomation = "<img src=\""+data.wall_msg[i]["infomation"]+"\">";
					}
					new_infos = new_infos  + "<tr><td><input type=\"button\" class=\"btn_click\" value=\""+infosid+"\"></td><td>"+nickname+"</td><td>"+infomation+"</td><td>"+infostime+"</td><td><input type=\"button\" class=\"btn_switch\" infosid=\""+infosid+"\" value=\""+infosstatus+"\"></td></tr>";
				});
				$("#wallmessage > table").append(new_infos);
				lastid = infosid;
			}
		}, "json");
	}, 15000);
});
</script>
