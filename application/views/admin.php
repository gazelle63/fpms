<style>html, body {width: 100%; height:100%; overflow: hidden;}body {background-color:#e5e5e5;}</style>
<div id="nav">
<?php
$message = anchor('fpms_admin/helps', $this->session->userdata("adminsname"), 'target="mainiframe"');;
foreach($nav as $key=>$value)
{
	$message .= anchor($value, $key, 'target="mainiframe"');
}
echo $message;
?>
</div>
<div id="main">
	<iframe name="mainiframe" src="<?php echo site_url('fpms_admin/helps');?>" frameborder="0" width="100%" height="100%"></iframe>
</div>
