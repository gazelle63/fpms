<style>
#templateurl {
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
#templatemessage {
	margin: 0 15px;
}
#templatemessage input.btn_input {
	width: 110px;
}
</style>

<?php
echo anchor($templatesurl, '刷新', 'id="templateurl"');
?>

<div id="templatemessage">
<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$message = '<tr><td width="70">编号</td><td width="70">类型</td><td>模板</td><td>p1 总背景</td><td>p2 顶部图</td><td>p3 留言背景</td><td>p4 星光图</td><td>p5 顶图宽度</td><td>p6 顶图高度</td><td>p7 顶部高度</td><td>p8 底部宽度</td><td>p9 底部高度</td><td width="90">启用</td></tr>';
foreach ($templates as $item)
{
	$message .= '<tr>';
	$message .= '<td><input type="button" class="btn_click" value="'.$item['templatesid'].'"></td>';
	$message .= '<td>'.$templatestype[$item['templatestype']].'</td>';
	$message .= '<td>'.$item['templatesurl'].'</td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p1" value="'.$item['p1'].'"></td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p2" value="'.$item['p2'].'"></td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p3" value="'.$item['p3'].'"></td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p4" value="'.$item['p4'].'"></td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p5" value="'.$item['p5'].'"></td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p6" value="'.$item['p6'].'"></td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p7" value="'.$item['p7'].'"></td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p8" value="'.$item['p8'].'"></td>';
	$message .= '<td><input type="text" class="btn_input" templatesid="'.$item['templatesid'].'" action="p9" value="'.$item['p9'].'"></td>';
	$message .= '<td><input type="button" class="btn_switch" templatesid="'.$item['templatesid'].'" value="'.$item['templatesstatus'].'"></td>';
	$message .= '</tr>';
}
echo $message;
?>
</table>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	var $templatesurl = "<?php echo $templatesurl;?>";

	$("#templatemessage").on("change", ".btn_input", function(){
		$this = $(this);
		$templatesid = $this.attr("templatesid");
		$action = $this.attr("action");
		$inputval = $this.val();
		$post_url = $templatesurl + "/templateaction";
		$post_data = {"templatesid" : $templatesid, "action" : $action, "inputval" : $inputval};
		$.post($post_url, $post_data, function(data){
			alert(data.action+" 已修改为 "+data.inputval);
		}, "json");
	});

	$("#templatemessage").on("click", ".btn_switch", function(){
		$this = $(this);
		$templatesid = $this.attr("templatesid");
		$status = $this.val();
		$post_url = $templatesurl + "/templatemessage";
		$post_data = {"templatesid" : $templatesid, "status" : $status};
		$.post($post_url, $post_data, function(data){
			$this.val(data.status);
		}, "json");
	});


});
</script>
