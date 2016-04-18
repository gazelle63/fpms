<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$message = '<tr><td width="80">infosid</td><td width="200">openid</td><td width="200">publicid</td><td>infomation</td><td width="80">infostype</td><td width="120">infostime</td><td width="80">infosstatus</td></tr>';
foreach ($infos as $item)
{
	$message .= '<tr>';
	$message .= '<td>'.$item['infosid'].'</td>';
	$message .= '<td>'.$item['openid'].'</td>';
	$message .= '<td>'.$item['publicid'].'</td>';
	if ($item['infostype'] == 2)
	{
		$message .= '<td>'.base_url($item['infomation']).'</td>';
	}
	else
	{
		$message .= '<td>'.$item['infomation'].'</td>';
	}
	$message .= '<td>'.$item['infostype'].'</td>';
	$message .= '<td>'.date('m-d H:i', $item['infostime']).'</td>';
	$message .= '<td>'.$item['infosstatus'].'</td>';
	$message .= '</tr>';
}
echo $message;
?>
</table>
