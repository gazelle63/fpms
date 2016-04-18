<table width="100%" border="1" cellspacing="1" cellpadding="4">
<?php
$message = '<tr><td width="80">exchangesid</td><td width="200">fansid</td><td width="200">goodsid</td><td>goodspoints</td><td width="80">goodscash</td><td width="120">exchangestime</td><td width="80">exchangesstatus</td></tr>';
foreach ($exchanges as $item)
{
	$message .= '<tr>';
	$message .= '<td>'.$item['exchangesid'].'</td>';
	$message .= '<td>'.$item['fansid'].'</td>';
	$message .= '<td>'.$item['goodsid'].'</td>';
	$message .= '<td>'.$item['goodspoints'].'</td>';
	$message .= '<td>'.$item['goodscash'].'</td>';
	$message .= '<td>'.$item['exchangestime'].'</td>';
	$message .= '<td>'.$item['exchangesstatus'].'</td>';
	$message .= '</tr>';
}
echo $message;
?>
</table>
