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
#ident_msg {
	color: #ff0000;
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
<div id="centerbox">
<?php
	$message = '<p id="ident_msg">'.$msg.'<p>';
	$message .= validation_errors();
	$message .= form_open();
	$message .= form_hidden('fansid', $fans['fansid']);
	$message .= form_input(array('name'=>'name', 'value'=>set_value('name'), 'placeholder'=>'请输入 真实姓名'));
	$message .= form_input(array('name'=>'cellphone', 'value'=>set_value('cellphone'), 'placeholder'=>'请输入 手机号码'));
	$message .= form_button('gc', '发送验证码');
	$message .= form_input(array('name'=>'captcha', 'value'=>set_value('captcha'), 'placeholder'=>'请输入 短信验证码'));
	$message .= form_submit('submit', '我要认证');
	$message .= form_close();
	echo $message;
?>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/jquery.js'); ?>"></script>
<script type="text/javascript">
var cellphone = 0;
$(function(){
	$("form").on('click', 'button', function(){
		$this = $(this);
		cellphone = $("input[name='cellphone']").val();
		$.post("<?php echo site_url('fpms_points/idents/captcha'); ?>", {"cellphone": cellphone}, function(data){
			$("#ident_msg").text(data);
			//alert(data);
			$this.prop("disabled", TRUE);
		});
	});
})
</script>
