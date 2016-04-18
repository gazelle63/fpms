<style>
table td img {
	height: 35px;
}
</style>
<div id="fansmessage">
<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$message = '<tr><td width="80">fansid</td><td width="200">headimgurl</td><td>nickname</td><td width="80">points</td><td width="120">lasttime</td><td width="80">level</td><td width="200">openid</td></tr>';
foreach ($fans as $item)
{
	$message .= '<tr>';
	$message .= '<td>'.$item['fansid'].'</td>';
	$message .= '<td><img src="'.$item['headimgurl'].'"></td>';
	$message .= '<td>'.$item['nickname'].'</td>';
	$message .= '<td>'.$item['points'].'</td>';
	$message .= '<td>'.$item['lasttime'].'</td>';
	$message .= '<td>'.$item['level'].'</td>';
	$message .= '<td><input type="button" class="btn_click" action="refreshfans" value="'.$item['openid'].'"></td>';
	$message .= '</tr>';
}
echo $message;
?>
</table>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	var $fansurl = "<?php echo $fansurl;?>";

	$("#fansmessage").on("click", ".btn_click", function(){
		$this = $(this);
		$action = $this.attr("action");
		$inputval = $this.val();
		$post_url = $fansurl + "/" + $action;
		$post_data = {"inputval" : $inputval};
		$.post($post_url, $post_data, function(data){
			$this.val(data.err);
		}, "json");
	});

});
</script>